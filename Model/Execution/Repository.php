<?php

namespace Mobbex\Subscriptions\Model;

class ExecutionRepository
{
    /** @var \Psr\Log\LoggerInterface */
    public $logger;

    /** @var \Mobbex\Subscriptions\Model\ExecutionFactory */
    public $factory;

    /** @var \Mobbex\Subscriptions\Model\ExecutionResource */
    public $resource;

    /** @var \Mobbex\Subscriptions\Model\ExecutionCollectionFactory */
    public $collectionFactory;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Mobbex\Subscriptions\Model\ExecutionFactory $factory,
        \Mobbex\Subscriptions\Model\ExecutionResource $resource,
        \Mobbex\Subscriptions\Model\ExecutionCollectionFactory $collectionFactory
    ) {
        $this->logger            = $logger;
        $this->factory           = $factory;
        $this->resource          = $resource;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get execution from the value and field given.
     * 
     * @param mixed $value The value to match.
     * @param string $field The field name where search. Primary key by default.
     * 
     * @return \Mobbex\Subscriptions\Model\Execution 
     */
    public function get($value, $field = null)
    {
        $execution = $this->factory->create();
        $this->resource->load($execution, $value, $field);
        return $execution;
    }

    /**
     * Save execution to db.
     * 
     * @param \Mobbex\Subscriptions\Model\Execution $execution
     * 
     * @return \Mobbex\Subscriptions\Model\Execution
     */
    public function save($execution)
    {
        $this->resource->save($execution);
        return $execution;
    }
}