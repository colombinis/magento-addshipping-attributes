<?php
namespace Sacsi\CustomAttribute\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Exception\LocalizedException;

class AddPisoDepto2Address implements ObserverInterface
{

    public function __construct(
        \Magento\Framework\DataObject\Copy $objectCopyService
    ) {
        $this->objectCopyService = $objectCopyService;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();

        // if ($quote->getBillingAddress()) {
        //     $order->getBillingAddress()->setPiso($quote->getBillingAddress()->getPiso());
        //     $order->getBillingAddress()->setDpto($quote->getBillingAddress()->getDpto());
        // }

        // if (!$quote->isVirtual()) {
        //     $order->getShippingAddress()->setPiso($quote->getShippingAddress()->getPiso());
        //     $order->getShippingAddress()->setDpto($quote->getShippingAddress()->getDpto());
        // }

        $this->objectCopyService->copyFieldsetToTarget(
            'extra_checkout_shipping_address_fields',
            'to_order_address',
            $quote,
            $order
        );

        $this->objectCopyService->copyFieldsetToTarget(
            'extra_checkout_billing_address_fields',
            'to_customer_address',
            $quote,
            $order
        );
        return $this;
    }

}