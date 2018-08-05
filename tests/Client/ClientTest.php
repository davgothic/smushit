<?php

use DavGothic\SmushIt\Client\Client;

/**
 * Corresponding Class to test SmushIt class
 *
 * @author David Hancock <davgothic@gmail.com>
 */
class ClientTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var Client The Client object.
     */
    private $client;

    public function setUp()
    {
        $this->client = $this->getMockForAbstractClass(Client::class);
    }

    public function testSettingTimeout()
    {
        $timeout = 10;
        $this->client->setTimeout($timeout);

        $this->assertEquals($timeout, $this->client->getTimeout());
    }

    public function testSettingUserAgent()
    {
        $userAgent = 'Test User Agent';
        $this->client->setUserAgent($userAgent);

        $this->assertEquals($userAgent, $this->client->getUserAgent());
    }

}
