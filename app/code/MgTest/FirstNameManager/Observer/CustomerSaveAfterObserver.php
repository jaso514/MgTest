<?php

namespace MgTest\FirstNameManager\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use MgTest\CustomerLogger\Logger\Logger;
use Magento\Customer\Api\Data\CustomerInterface;
use MgTest\CustomerEmail\Helper\EmailSender;

/**
 * CustomerSaveAfterObserver 
 */
class CustomerSaveAfterObserver implements ObserverInterface
{
    protected $logger;
    private $emailSender;
    /**
     * @param \MgTest\CustomerLogger\Logger\Logger $logger
     */
    public function __construct(
        Logger $logger,
        EmailSender $emailSender
    ) {
        $this->logger = $logger;
        $this->emailSender = $emailSender;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var CustomerInterface $customer */
        $customer = $observer->getEvent()->getCustomer();
        $this->logCustomerData($customer);
        $this->sendCustomerSupportEmail($customer);
    }

        /**
         * Log customer data
         *
         * @param CustomerInterface $customer
         * @return void
         */
    private function logCustomerData(CustomerInterface $customer): void 
    {

        $firstname = $customer->getFirstname();
        $lastname = $customer->getLastname();
        $email = $customer->getEmail();
        $currentDateTime = date('Y-m-d H:i:s');

        $logMessage = sprintf(
            "Customer Registration Data:\nDate/Time: %s,\nFirst Name: %s,\nLast Name: %s,\nEmail: %s",
            $currentDateTime,
            $firstname,
            $lastname,
            $email
        );

        $this->logger->info($logMessage);
    }

    private function sendCustomerSupportEmail(CustomerInterface $customer): void
    {
        $this->emailSender->sendNewCustomerNotification($customer);
    }
}