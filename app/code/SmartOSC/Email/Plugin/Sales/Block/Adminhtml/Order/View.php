<?php

namespace SmartOSC\Email\Plugin\Sales\Block\Adminhtml\Order;

use Magento\Sales\Block\Adminhtml\Order\View as OrderView;
use Magento\Framework\UrlInterface;
use Magento\Framework\AuthorizationInterface;

class View
{
    /** @var \Magento\Framework\UrlInterface */
    protected $_urlBuilder;

    /** @var \Magento\Framework\AuthorizationInterface */
    protected $_authorization;

    /**
     * @param UrlInterface $url
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        UrlInterface $url,
        AuthorizationInterface $authorization
    ) {
        $this->_urlBuilder = $url;
        $this->_authorization = $authorization;
    }

    /**
     * @param OrderView $view
     * @return void
     */
    public function beforeSetLayout(OrderView $view)
    {
        $url = $this->_urlBuilder->getUrl('smartosc/email/send', ['id' => $view->getOrderId()]);
        $message = _('Are you sure you want to send email?');
        $view->addButton(
            'smartosc_send_email',
            [
                'label' => __('Send Custom Email'),
                'class' => 'button',
                'onclick' => "confirmSetLocation('{$message}', '{$url}')"
            ],
        );
    }
}