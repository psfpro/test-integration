<?php

namespace App\Decorator;

use App\Integration\DataProviderInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

class LoggerDataProviderDecorator implements DataProviderInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var DataProviderInterface
     */
    private $dataProvider;

    /**
     * AbstractDataProviderDecorator constructor.
     * @param DataProviderInterface $dataProvider
     * @param LoggerInterface $logger
     */
    public function __construct(DataProviderInterface $dataProvider, LoggerInterface $logger)
    {
        $this->dataProvider = $dataProvider;
        $this->logger = $logger;
    }

    /**
     * @param array $request
     *
     * @return array
     * @throws \Exception
     */
    public function get(array $request): array
    {
        try {
            return $this->dataProvider->get($request);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            throw $e;
        }
    }
}