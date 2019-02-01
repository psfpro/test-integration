<?php


namespace App\Integration;


interface DataProviderInterface
{
    /**
     * @param array $request
     *
     * @return array
     * @throws \Exception
     */
    public function get(array $request): array;
}