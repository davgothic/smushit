<?php

namespace DavGothic\SmushIt\Client;

use DavGothic\SmushIt\SmushIt;
use GuzzleHttp\Client as GuzzleClient;

/**
 * A Guzzle SmushIt client
 *
 * @codeCoverageIgnore
 */
class Guzzle extends Client
{

    /**
     * @var GuzzleClient|null
     */
    private $guzzleClient;

    /**
     * Guzzle constructor.
     *
     * @param GuzzleClient|null $guzzleClient A GuzzleClient instance.
     */
    public function __construct(GuzzleClient $guzzleClient = null)
    {
        if (null === $guzzleClient) {
            $this->guzzleClient = new GuzzleClient(['timeout' => $this->timeout]);
        } else {
            $this->guzzleClient = $guzzleClient;
        }
    }

    /**
     * @inheritdoc
     */
    public function execute($requestType, $imageLocation)
    {
        if (self::TYPE_LOCAL === $requestType) {
            $res = $this->guzzleClient->post(SmushIt::SMUSH_URL, [
                'multipart' => [
                    [
                        'name'     => 'files',
                        'contents' => fopen($imageLocation, 'rb'),
                    ],
                ],
            ]);
        } else {
            $res = $this->guzzleClient->get(SmushIt::SMUSH_URL, [
                'query' => [
                    'img' => $imageLocation,
                ],
            ]);
        }

        return $res->getBody();
    }

}
