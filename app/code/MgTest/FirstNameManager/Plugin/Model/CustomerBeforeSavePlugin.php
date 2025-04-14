<?php

namespace MgTest\FirstNameManager\Plugin\Model;

use Magento\Customer\Model\Customer;
use Psr\Log\LoggerInterface;


class CustomerBeforeSavePlugin
{
    protected $logger;

    /**
     * Constructor for CustomerBeforeSavePlugin.
     *
     * @param LoggerInterface $logger Logger for logging information.
     */

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Before save method for customer model.
     *
     * @param Customer $subject
     * @return void
     */
    public function beforeSave(Customer $subject)
    {
        $firstname = $subject->getFirstname();

        $firstname = strpos($firstname, ' ')!==false ? 
                strstr($firstname, ' ', true): $firstname;
        $subject->setFirstname($firstname);

    }
}