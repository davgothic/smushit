<?php

namespace DavGothic\SmushIt;

use DavGothic\SmushIt\Exception\SmushItException;

/**
 * SmushIt - A PHP client for the Yahoo! Smush.it web service.
 *
 * @version    2.0.0
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
    const USER_AGENT = 'ShushIt PHP Client/2.0.0 (+http://github.com/davgothic/SmushIt)';

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
     * @return \stdClass
     *
     *  src       = Source location of the input image.
     *  src_size  = Size of the source image in bytes.
     *  dest      = Temporary location of the compressed image.
     *  dest_size = Size of the compressed image in bytes.
     *  percent   = How much smaller the compressed image is.
     *  expires   = The date when the file will be deleted from the server
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
     * @return \stdClass
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
     * @return \stdClass
     */
    private function smushFile()
    {
        if ( ! is_file($this->imageLocation) || ! is_readable($this->imageLocation)) {
            throw new SmushItException('Could not read image file', 500, $this->imageLocation);
        }

        $file = new \CURLFile($this->imageLocation);

        curl_setopt($this->curl, CURLOPT_URL, self::SMUSH_URL);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, array('files' => $file));
        $jsonStr = curl_exec($this->curl);

        return $this->parseResponse($jsonStr);
    }

    /**
     * Parse the response from the Smush.it web service.
     *
     * @param  string $jsonStr A JSON string response from the Smush.it web service.
     *
     * @throws SmushItException
     * @return \stdClass
     */
    private function parseResponse($jsonStr)
    {
        $result = json_decode($jsonStr);

        if (null === $result) {
            throw new SmushItException('Bad response received from the Smush.it service.', 500, $this->imageLocation);
        }

        if (isset($result->error)) {
            throw new SmushItException($result->error_long, $result->error, $this->imageLocation);
        }

        return $result;
    }

}
