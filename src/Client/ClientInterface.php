<?php

namespace DavGothic\SmushIt\Client;

interface ClientInterface
{

    /**
     * Get the user agent string used for the request.
     *
     * @return string
     */
    public function getUserAgent();

    /**
     * Set the user agent string to use for the request.
     *
     * @param string $userAgent The user agent string.
     */
    public function setUserAgent($userAgent);

    /**
     * Get the request timeout.
     *
     * @return int
     */
    public function getTimeout();

    /**
     * Set the request timeout.
     *
     * @param int $timeout The timeout in seconds.
     */
    public function setTimeout($timeout);

    /**
     * @param int $requestType
     * @param string $imageLocation
     *
     * @return mixed
     */
    public function execute($requestType, $imageLocation);

}
