<?php

namespace MgTest\FirstNameManager\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use MgTest\CustomerLogger\Logger\Logger;
use Magento\Customer\Api\Data\CustomerInterface;

/**
 * CustomerSaveAfterObserver 
 */
class CustomerSaveAfterObserver implements ObserverInterface
{
    protected $logger;

    /**
     * @param \MgTest\CustomerLogger\Logger\Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var CustomerInterface $customer */
        $customer = $observer->getEvent()->getCustomer();

        $firstname = $customer->getFirstname();
        $lastname = $customer->getLastname();
        $email = $customer->getEmail();
        $currentDateTime = date('Y-m-d H:i:s');

        $logMessage = sprintf(
            "Customer Data:\nDate/Time: %s,\nFirst Name: %s,\nLast Name: %s,\nEmail: %s",
            $currentDateTime,
            $firstname,
            $lastname,
            $email
        );

        $this->logger->info($logMessage);
    }
}