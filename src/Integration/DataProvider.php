<?php

namespace App\Integration;

class DataProvider implements DataProviderInterface
{
    private $host;
    private $user;
    private $password;

    /**
     * @param string $host
     * @param string $user
     * @param string $password
     */
    public function __construct(string $host, string $user, string $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @param array $request
     *
     * @return array
     */
    public function get(array $request): array
    {
        if (empty($request)) {
            throw new \InvalidArgumentException('Empty request');
        }
        // returns a response from external service

        return [];
    }
}