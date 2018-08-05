<?php

namespace DavGothic\SmushIt\Client;

use DavGothic\SmushIt\SmushIt;

/**
 * A cURL SmushIt client
 *
 * @codeCoverageIgnore
 */
class Curl extends Client
{
    /**
     * Curl constructor.
     *
     * @throws \RuntimeException If the cURL PHP extension is not loaded.
     */
    public function __construct()
    {
        // @codeCoverageIgnoreStart
        if (!extension_loaded('curl')) {
            throw new \RuntimeException('The cURL PHP extension was not found.');
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * @inheritdoc
     */
    public function execute($requestType, $imageLocation)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);

        if (self::TYPE_LOCAL === $requestType) {
            curl_setopt($curl, CURLOPT_URL, SmushIt::SMUSH_URL);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, ['files' => new \CURLFile($imageLocation)]);
        } else {
            curl_setopt($curl, CURLOPT_URL, SmushIt::SMUSH_URL . http_build_query([
                    'img' => $imageLocation,
                ]));
        }

        return curl_exec($curl);
    }

}
