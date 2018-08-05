<?php

use DavGothic\SmushIt\Exception\SmushItException;
use DavGothic\SmushIt\SmushIt;

/**
 * Corresponding Class to test SmushIt class
 *
 * @author David Hancock <davgothic@gmail.com>
 */
class SmushItTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var SmushIt The SmushIt object.
     */
    private $smushIt;

    public function setUp()
    {
        $responseArray = [
            'src'       => 'http://static0.resmush.it/output/1262dc777d8b239cfdf5f528a4032f02/source.png',
            'dest'      => 'http://static1.resmush.it/output/a9ba82e7ba18e9482e085fadb126edad/output.png',
            'src_size'  => 455200,
            'dest_size' => 158075,
            'percent'   => 65,
            'format'    => 'png',
            'expires'   => 'Sun, 19 Mar 2017 18:00:33 +0100',
            'generator' => 'reSmush.it rev.1.4.22.20170224',
        ];

        $client = $this->getMock(\DavGothic\SmushIt\Client\ClientInterface::class);

        $client->expects($this->any())
               ->method('execute')
               ->willReturn(json_encode($responseArray));

        /** @var \DavGothic\SmushIt\Client\Client $client */
        $this->smushIt = new SmushIt($client);
    }

    public function testIfRemoteImageSmushed()
    {
        $image  = 'https://example.org/image.jpg';
        $result = $this->smushIt->compress($image);
        $this->assertTrue(isset($result->dest));
    }

    public function testIfLocalImageSmushed()
    {
        $image  = 'tests/data/test.png';
        $result = $this->smushIt->compress($image);
        $this->assertTrue(isset($result->dest));
    }

    public function testIfExceptionThrownForInvalidImagePath()
    {
        $this->setExpectedException(SmushItException::class);
        $this->smushIt->compress('');
    }

    public function testIfExceptionThrownOnEmptyResponse()
    {
        $this->setExpectedException(SmushItException::class);
        $this->invokeMethod($this->smushIt, 'parseResponse', [null]);
    }

    public function testIfExceptionThrownOnErrorResponse()
    {
        $this->setExpectedException(SmushItException::class);
        $this->invokeMethod($this->smushIt, 'parseResponse', ['{"error":400,"error_long":"message"}']);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object $object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method     = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

}
