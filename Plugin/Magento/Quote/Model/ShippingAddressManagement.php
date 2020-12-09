<?php

namespace Sacsi\CustomAttribute\Plugin\Magento\Quote\Model;

class ShippingAddressManagement
{
    protected $logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Quote\Model\ShippingAddressManagement $subject
     * @param $cartId
     * \Magento\Quote\Api\Data\AddressInterface $address
     * @return array
     */
    public function beforeAssign(
        \Magento\Quote\Model\ShippingAddressManagement $subject,
        $cartId,
        \Magento\Quote\Api\Data\AddressInterface $address
    ) {
        $extAttributes = $address->getExtensionAttributes();
        if (!empty($extAttributes)) {
            try {
                $address->setDpto($extAttributes->getDpto());
                $address->setPiso($extAttributes->getPiso());
            } catch (\Exception $e) {
                $this->logger->critical($e->getMessage());
            }
        }
        return [$cartId, $address];
    }
}