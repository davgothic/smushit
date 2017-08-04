<?php

namespace DavGothic\SmushIt\Exception;

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
     * @param int $code The exception code.
     * @param string $image Location of the image.
     */
    public function __construct($message, $code, $image)
    {
        $this->image = $image;
        parent::__construct($message, $code);
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
