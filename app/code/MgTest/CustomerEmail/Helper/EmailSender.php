<?php

namespace MgTest\CustomerEmail\Helper;

use Magento\Framework\Mail\Template\TransportBuilder;
use MgTest\CustomerLogger\Logger\Logger;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface as Config;
use Magento\Customer\Api\Data\CustomerInterface;

/**
 * EmailSender Helper
 */
class EmailSender
{
    protected $transportBuilder;
    protected $logger;
    protected $storeManager;
    protected $inlineTranslation;
    protected $config;

    const PATH_EMAIL_SUPPORT = 'trans_email/ident_support/email';

    /**
     * __construct
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \MgTest\CustomerEmail\Helper\Logger\Logger $logger
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\App\Config\ScopeConfigInterface as Config
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        Logger $logger,
        StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,
        Config $config
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->config = $config;
    }

    /**
     * Sends a notification to customer support when a new customer registers.
     * @param CustomerInterface $customerData
     * @return bool
     */
    public function sendNewCustomerNotification(CustomerInterface $customerData)
    {
        $storeId = $this->storeManager->getStore()->getId();
        $customerSupportEmail = $this->config->getValue(
            self::PATH_EMAIL_SUPPORT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if (!$customerSupportEmail) {
            $this->logger->warning('Customer Support Email not configured.');
            return false;
        }

        try {
            $templateId = 'customer_support_new_registration';

            $templateVars = [
                'subject' => 'New Customer Registration',
                'customer_name' => $customerData->getFirstname() . ' ' . $customerData->getLastname(),
                'customer_email' => $customerData->getEmail(),
            ];

            $this->inlineTranslation->suspend();
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions([
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => $storeId
                    ])
                ->setTemplateVars($templateVars)
                ->setFrom(['name' => 'Customer Regisrtation', 'email' => 'noreply@test.com'])
                ->addTo($customerSupportEmail, 'Customer Support')
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
            return true;

        } catch (\Exception $e) {
            $this->logger->error('Error sending email: ' . $e->getMessage());
            return false;
        }
    }
}