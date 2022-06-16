<?php

namespace Mobbex\Subscriptions\Model;

class ExecutionRepository extends \Mobbex\Subscriptions\Model\Repository
{
    /** @var \Mobbex\Subscriptions\Model\ExecutionFactory */
    public $factory;

    /** @var \Mobbex\Subscriptions\Model\ExecutionResource */
    public $resource;

    /** @var \Mobbex\Subscriptions\Model\ExecutionCollectionFactory */
    public $collectionFactory;

    public function __construct(
        \Mobbex\Subscriptions\Model\ExecutionFactory $factory,
        \Mobbex\Subscriptions\Model\ExecutionResource $resource,
        \Mobbex\Subscriptions\Model\ExecutionCollectionFactory $collectionFactory
    ) {
        $this->factory           = $factory;
        $this->resource          = $resource;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Build an Execution.
     * 
     * @param array $props {
     *     @type string $uid              Primaty key. UID generated in Mobbex.
     *     @type string $subscription_uid UID of parent subscription.
     *     @type string $subscriber_uid   UID of parent subscriber.
     *     @type int    $status           Payment status.
     *     @type float  $total            Amount charged.
     *     @type string $date             Date of payment.
     *     @type string $data             Encoded webhook data.
     * }
     * 
     * @return \Mobbex\Subscriptions\Model\Execution
     */
    public function build($props = [])
    {
        return parent::build(...func_get_args());
    }

    /**
     * Get execution from the value and field given.
     * 
     * @param mixed $value The value to match.
     * @param string $field The field name where search. Primary key by default.
     * 
     * @return \Mobbex\Subscriptions\Model\Execution 
     */
    public function get($value = null, $field = null)
    {
        return parent::get(...func_get_args());
    }

    /**
     * Save execution to db.
     * 
     * @param \Mobbex\Subscriptions\Model\Execution $execution
     * @param object $module No use cases here =(.
     * 
     * @return \Mobbex\Subscriptions\Model\Execution
     */
    public function save($execution, $module = null)
    {
        return parent::save(...func_get_args());
    }
}