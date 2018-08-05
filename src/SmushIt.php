<?php

namespace DavGothic\SmushIt;

use DavGothic\SmushIt\Client\Client;
use DavGothic\SmushIt\Client\ClientInterface;
use DavGothic\SmushIt\Exception\SmushItException;

/**
 * SmushIt - A PHP client for the Yahoo! Smush.it web service.
 *
 * @version    3.0.1
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
    const USER_AGENT = 'ShushIt PHP Client/3.0.1 (+http://github.com/davgothic/SmushIt)';
    /**
     * @var int How often it is allowed to send requests. In microseconds.
     */
    public $requestInterval = 1000000;
    /**
     * @var string location of the image.
     */
    private $imageLocation;
    /**
     * @var ClientInterface The client object.
     */
    private $client;
    /**
     * @var int Time of last request.
     */
    private $requestTime;

    /**
     * Create a new SmushIt instance.
     *
     * @param ClientInterface $client The client to use for the request.
     *
     * @throws \RuntimeException
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $imageLocation The location of the image.
     *
     * @throws \DavGothic\SmushIt\Exception\SmushItException
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
        // @codeCoverageIgnoreStart
        if (!empty($this->requestTime)) {
            $sinceLast = ((microtime(true) - $this->requestTime) * 1000000);

            if ($sinceLast < $this->requestInterval) {
                // Sleep it off
                usleep($this->requestInterval - $sinceLast);
            }
        }
        // @codeCoverageIgnoreEnd

        $this->requestTime = microtime(true);

        $this->imageLocation = $imageLocation;

        if (null === $this->client->getUserAgent()) {
            $this->client->setUserAgent(self::USER_AGENT);
        }

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
     * @throws \DavGothic\SmushIt\Exception\SmushItException
     * @return \stdClass
     */
    private function smushUrl()
    {
        $jsonStr = $this->client->execute(Client::TYPE_REMOTE, $this->imageLocation);

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
        if (!is_file($this->imageLocation) || !is_readable($this->imageLocation)) {
            throw new SmushItException('Could not read image file', 500, $this->imageLocation);
        }

        $jsonStr = $this->client->execute(Client::TYPE_LOCAL, $this->imageLocation);

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
