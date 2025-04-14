<?php

namespace MgTest\CustomerLogger\Logger\Handler;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger as Monolog;

/**
 * CustomerLogger for customer logs
 */
class CustomerLogger extends Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Monolog::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/customer.log';
}