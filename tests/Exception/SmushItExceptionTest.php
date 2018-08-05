<?php

use DavGothic\SmushIt\Exception\SmushItException;

/**
 * Corresponding Class to test SmushItException class
 *
 * @author David Hancock <davgothic@gmail.com>
 */
class SmushItExceptionTest extends \PHPUnit\Framework\TestCase
{

    public function testExceptionImagePathDoesNotChange()
    {
        $imagePath = '/test/image/path.png';
        $exception = new SmushItException('', 0, $imagePath);
        $this->assertSame($imagePath, $exception->getImage());
    }

}
