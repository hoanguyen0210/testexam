<?php

namespace SmartOSC\Email\Controller\Adminhtml\Email;

use Magento\Backend\App\Action\Context;
use SmartOSC\Email\Helper\Data as Helper;
use \Psr\Log\LoggerInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;

class Send extends \Magento\Backend\App\Action
{
    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var RedirectFactory
     */
    protected $redirectFactory;

    /**
     * @var MessageManagerInterface
     */
    protected $massageManager;

    /**
     * @param Context $context
     * @param Helper $helper
     * @param LoggerInterface $logger
     * @param RedirectFactory $redirectFactory
     * @param MessageManagerInterface $massageManager
     */
    public function __construct(
        Context $context,
        Helper $helper,
        LoggerInterface $logger,
        RedirectFactory $redirectFactory,
        MessageManagerInterface $massageManager
    ) {
        $this->helper = $helper;
        $this->logger = $logger;
        $this->redirectFactory = $redirectFactory;
        $this->massageManager = $massageManager;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $this->helper->sendEmail();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
        $this->massageManager->addSuccessMessage(__('Send Email success.'));
        return $resultRedirect->setPath('sales/order/index');
    }
}