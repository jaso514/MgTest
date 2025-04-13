<?php

namespace MgTest\Test\Plugin;

use Magento\Customer\Controller\Account\CreatePost;
use Magento\Framework\Controller\Result\Redirect;
use Psr\Log\LoggerInterface;

/**
 * Class CustomerRegisterPlugin
 * @package MgTest\Test\Plugin
 * @see \Magento\Customer\Controller\Account\CreatePost
*/
class CustomerRegisterPlugin
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Before execute method.
     *
     * @param CreatePost $subject
     * @return void
     */
    public function beforeExecute(CreatePost $subject)
    {
        $postData = $subject->getRequest()->getParams();

        // Equivalente a console.log en PHP usando el logger de Magento
        $this->logger->info('Datos del POST de registro:');
        $this->logger->info(print_r($postData, true));

        // Aquí puedes agregar tu lógica adicional para procesar los datos del registro ANTES de que se guarden.
    }

    /**
     * After execute method.
     *
     * @param CreatePost $subject
     * @param Redirect $result
     * @return Redirect
     */
    public function afterExecute(CreatePost $subject, Redirect $result)
    {
        return $result;
    }

}