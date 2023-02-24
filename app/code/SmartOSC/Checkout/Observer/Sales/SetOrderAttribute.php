<?php

namespace SmartOSC\Checkout\Observer\Sales;

use PHPUnit\Exception;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Api\CustomerRepositoryInterface;

class SetOrderAttribute implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var CheckoutSession
     */
    protected $session;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param CheckoutSession $session
     * @param CustomerSession $customerSession
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        CheckoutSession $session,
        CustomerSession $customerSession,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->logger = $logger;
        $this->session = $session;
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        try {
            $quote = $this->session->getQuote();
            $order = $observer->getData('order');
            $hobbies = $quote->getData('hobbies');
            $income = $quote->getData('income');
            $order->setData('hobbies', $hobbies);
            $order->setData('income', $income);

            if ($this->customerSession->isLoggedIn()) {
                $currentCustomerId = $this->customerSession->getCustomerId();
                $customer = $this->customerRepository->getById($currentCustomerId);
                $customer->setCustomAttribute('hobbies', $hobbies);
                $customer->setCustomAttribute('income', '1');

                $this->customerRepository->save($customer);
            }

            $order->save();
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}