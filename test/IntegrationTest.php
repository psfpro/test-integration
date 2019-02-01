<?php

namespace Test;

use App\Integration\DataProvider;
use App\Decorator\CacheDataProviderDecorator;
use App\Decorator\LoggerDataProviderDecorator;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class IntegrationTest extends TestCase
{
    public function testDataProvider()
    {
        $dataProvider = new DataProvider('host', 'user', 'pass');
        $cache = new ArrayAdapter();
        $cacheDecorator = new CacheDataProviderDecorator($dataProvider, $cache, 3600);
        $logger = new TestLogger();
        $loggerDecorator = new LoggerDataProviderDecorator($cacheDecorator, $logger);
        try {
            $loggerDecorator->get(['method' => 'test', 'data' => 123]);
            $loggerDecorator->get([]);
        } catch (\Exception $e) {
            $this->assertEquals('Empty request', $e->getMessage());
        }

        $this->assertCount(2, $cache->getValues());
        $this->assertCount(1, $logger->records);
    }
}
