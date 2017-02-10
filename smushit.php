<?php

/**
 * SmushIt - A PHP client for the Yahoo! Smush.it web service.
 *
 * @version    1.3
 * @author     David Hancock <davgothic@gmail.com>
 * @author     Elan Ruusamäe <glen@delfi.ee>
 * @copyright  (c) 2011 David Hancock
 * @license    MIT
 * @link       http://github.com/davgothic/SmushIt
 */
class SmushIt
{

    // URL to the Smush.it web service.
    const SMUSH_URL = 'http://api.resmush.it/ws.php?';

    // User agent string to set for the request.
    const USER_AGENT = 'ShushIt PHP Client/1.3 (+http://github.com/davgothic/SmushIt)';

    /**
     * @var string location of the image.
     */
    private $imageLocation;

    /**
     * @var resource The cURL handle.
     */
    private $curl;

    /**
     * @var int Time of last request.
     */
    private $requestTime;

    /**
     * @var int How often it is allowed to send requests. In microseconds.
     */
    public $requestInterval = 1000000;

    /**
     * Create a new SmushIt instance.
     *
     * @throws \RuntimeException
     */
    public function __construct()
    {
        if ( ! extension_loaded('json')) {
            throw new \RuntimeException('The JSON extension was not found.');
        }

        if ( ! extension_loaded('curl')) {
            throw new \RuntimeException('The cURL extension was not found.');
        }

        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($this->curl, CURLOPT_USERAGENT, self::USER_AGENT);
    }

    /**
     * @param string $imageLocation The location of the image.
     *
     * @return object
     *
     *  src       = Source location of the input image.
     *  src_size  = Size of the source image in bytes.
     *  dest      = Temporary location of the compressed image.
     *  dest_size = Size of the compressed image in bytes.
     *  percent   = How much smaller the compressed image is.
     */
    public function compress($imageLocation)
    {
        // Check if we should throttle the request to once per $requestInterval
        if ( ! empty($this->requestTime)) {
            $sinceLast = ((microtime(true) - $this->requestTime) * 1000000);

            if ($sinceLast < $this->requestInterval) {
                // Sleep it off
                usleep($this->requestInterval - $sinceLast);
            }
        }

        $this->requestTime = microtime(true);

        $this->imageLocation = $imageLocation;

        if (preg_match('/https?:\/\//', $this->imageLocation) === 1) {
            $result = $this->smushUrl();
        } else {
            $result = $this->smushFile();
        }

        return $result;
    }

    /**
     * Compress a remote image using the Smush.it web service.
     *
     * @return object
     */
    private function smushUrl()
    {
        curl_setopt($this->curl, CURLOPT_URL, self::SMUSH_URL . http_build_query(array('img' => $this->imageLocation)));
        $jsonStr = curl_exec($this->curl);

        return $this->parseResponse($jsonStr);
    }

    /**
     * Compress a local image using the Smush.it web service.
     *
     * @throws SmushItException
     * @return object
     */
    private function smushFile()
    {
        if ( ! is_file($this->imageLocation) || ! is_readable($this->imageLocation)) {
            throw new SmushItException('Could not read image file', $this->imageLocation);
        }

        curl_setopt($this->curl, CURLOPT_URL, self::SMUSH_URL);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, array('files' => '@' . $this->imageLocation));
        $jsonStr = curl_exec($this->curl);

        return $this->parseResponse($jsonStr);
    }

    /**
     * Parse the response from the Smush.it web service.
     *
     * @param  string $jsonStr A JSON string response from the Smush.it web service.
     *
     * @throws SmushItException
     * @return object
     */
    private function parseResponse($jsonStr)
    {
        $result = json_decode($jsonStr);

        if (is_null($result)) {
            throw new SmushItException('Bad response received from the Smush.it service.', $this->imageLocation);
        }

        if (isset($result->error)) {
            throw new SmushItException($result->error, $this->imageLocation);
        }

        return $result;
    }

}

/**
 * SmushIt exception.
 */
class SmushItException extends \Exception
{

    /**
     * @var string Location of the image.
     */
    private $image;

    /**
     * Creates a new exception.
     *
     * @param string $message Error message.
     * @param string $image   Location of the image.
     */
    public function __construct($message, $image)
    {
        $this->image = $image;
        parent::__construct($message);
    }

    /**
     * Get the location of the image.
     *
     * @return string
     */
    final public function getImage()
    {
        return $this->image;
    }

}
