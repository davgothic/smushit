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
            curl_setopt($curl, CURLOPT_POSTFIELDS, array('files' => new \CURLFile($imageLocation)));
        } else {
            curl_setopt($curl, CURLOPT_URL, SmushIt::SMUSH_URL . http_build_query(array(
                    'img' => $imageLocation
                )));
        }

        return curl_exec($curl);
    }

}
