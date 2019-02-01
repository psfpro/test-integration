<?php

namespace App\Decorator;

use App\Integration\DataProviderInterface;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

class CacheDataProviderDecorator implements DataProviderInterface
{
    /**
     * @var DataProviderInterface
     */
    private $dataProvider;
    /**
     * @var CacheItemInterface
     */
    private $cache;
    /**
     * @var int
     */
    private $ttl;

    /**
     * AbstractDataProviderDecorator constructor.
     * @param DataProviderInterface $dataProvider
     * @param CacheItemPoolInterface $cache
     * @param int $ttl
     */
    public function __construct(DataProviderInterface $dataProvider, CacheItemPoolInterface $cache, int $ttl)
    {
        $this->dataProvider = $dataProvider;
        $this->cache = $cache;
        $this->ttl = $ttl;
    }

    /**
     * @param array $request
     *
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Exception
     */
    public function get(array $request): array
    {
        $cacheKey = $this->getCacheKey($request);
        $cacheItem = $this->cache->getItem($cacheKey);
        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $result = $this->dataProvider->get($request);

        $cacheItem
            ->set($result)
            ->expiresAfter($this->ttl);

        $this->cache->save($cacheItem);

        return $result;
    }

    /**
     * @param array $input
     * @return string
     */
    private function getCacheKey(array $input): string
    {
        return md5(json_encode($input));
    }
}