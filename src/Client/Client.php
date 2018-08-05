<?php

namespace DavGothic\SmushIt\Client;

abstract class Client implements ClientInterface
{

    const TYPE_LOCAL  = 0;
    const TYPE_REMOTE = 1;

    /**
     * @var string
     */
    protected $userAgent;

    /**
     * @var int
     */
    protected $timeout = 10;

    /**
     * @inheritdoc
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @inheritdoc
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @inheritdoc
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @inheritdoc
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }

}
