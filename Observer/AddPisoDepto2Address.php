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
        $quote = $observer->getEvent()->getQuote();
        $quoteBillingAddress = $quote->getBillingAddress();
        $quoteShippingAddress_1 = $quote->getShippingAddress();
        $quoteShippingAddress = $quote->getBillingAddress();

        $order = $observer->getEvent()->getOrder();
        $orderBillingAddress = $order->getBillingAddress();
        $orderShippingAddress = $order->getShippingAddress();

        // $saveInAddressBook = $quoteShippingAddress_1->getSaveInAddressBook() ? 1 : 0;
        // $sameAsBilling = $quoteShippingAddress_1->getSameAsBilling() ? 1 : 0;
        // $getPiso = $quoteShippingAddress_1->getPiso();

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
            $quoteBillingAddress,
            $orderBillingAddress
        );

        $this->objectCopyService->copyFieldsetToTarget(
            'extra_checkout_billing_address_fields',
            'to_customer_address',
            $quoteShippingAddress,
            $orderShippingAddress
        );

        return $this;
    }

}