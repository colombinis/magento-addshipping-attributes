<?php
namespace Sacsi\CustomAttribute\Plugin\Magento\Quote\Model;

use Psr\Log\LoggerInterface;

class BillingAddressManagement
{

    /**
     * @var LoggerInterface $logger
     */
    protected $logger;

    /**
     * BillingAddressManagement constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Quote\Model\BillingAddressManagement $subject
     * @param $cartId
     * \Magento\Quote\Api\Data\AddressInterface $address
     * @return array
     */
    public function beforeAssign(
        \Magento\Quote\Model\BillingAddressManagement $subject,
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
