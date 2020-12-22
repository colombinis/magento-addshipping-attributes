<?php
namespace Sacsi\CustomAttribute\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Directory\Helper\Data as HelperData;
use Magento\Directory\Model\RegionFactory;
use Magento\Customer\Api\Data\RegionInterface;
use Magento\Customer\Api\Data\RegionInterfaceFactory;

class SalesOrderPlaceBefore implements ObserverInterface
{
    /**
     * @var \Magento\Customer\Api\Data\RegionInterfaceFactory
     */
    protected $regionDataFactory;


    /**
     * @var RegionFactory
     */
    protected $regionFactory;


    /**
     * @var HelperData
     */
    protected $helperData;

     /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $_dataObjectHelper;


    /**
     * @var \Magento\Customer\Api\Data\AddressInterfaceFactory
     */
    protected $_addressDataFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    private $_addressRepository;

    protected $_logger;

    /**
     * SalesOrderPlaceBefore constructor.
     * @param \Magento\Customer\Model\Session $customer
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\Metadata\FormFactory $formFactory
     * @param AddressInterfaceFactory $addressDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param RegionFactory $regionFactory
     * @param HelperData $helperData
     * @param RegionInterfaceFactory $regionDataFactory
     */
    public function __construct(
        \Magento\Customer\Model\Session $customer,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Psr\Log\LoggerInterface $logger,
        AddressInterfaceFactory $addressDataFactory,
        DataObjectHelper $dataObjectHelper,
        RegionFactory $regionFactory,
        HelperData $helperData,
        RegionInterfaceFactory $regionDataFactory
    )
    {
        $this->_customerSession = $customer;
        $this->_checkoutSession = $checkoutSession;
        $this->_addressRepository = $addressRepository;
        $this->_logger = $logger;
        $this->_addressDataFactory = $addressDataFactory;
        $this->_dataObjectHelper = $dataObjectHelper;
        $this->regionFactory = $regionFactory;
        $this->helperData = $helperData ;
        $this->regionDataFactory = $regionDataFactory;
    }

    /**
     * Add Custom attributes in quote address
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer               = $this->_customerSession->getCustomer();
        $order                  = $observer->getEvent()->getOrder();
        $shippingAddress        = $order->getShippingAddress();
        $billingAddress         = $order->getBillingAddress();


        //add custom attributes in user address
        if(!empty($order->getCustomerId())){
            $address = $this->_extractAddress($shippingAddress,$order->getCustomerId());

            $debug_customAttr = $address->getCustomAttributes();
            $address->setCustomAttribute('piso', $shippingAddress->getPiso());
            $address->setCustomAttribute('dpto', $shippingAddress->getDpto());

            $this->_addressRepository->save($address);
/*
            $customerAddress = $this->addressRepository->getById($customer->getCustomerAddressId());
            $hasChange = false;
            $debug_customAttributes = $customerAddress->getCustomAttributes();

            $customerAddress->setCustomAttribute('piso', $shippingAddress->getPiso());
            $customerAddress->setCustomAttribute('dpto', $shippingAddress->getDpto());

            // if($hasChange) {
                $this->addressRepository->save($customerAddress);
            // }
*/
        }

        return $this;
    }

    /**
     * Extract address from request
     *
     * @return \Magento\Customer\Api\Data\AddressInterface
     */
    protected function _extractAddress($address,$customerId)
    {
        $attributeValues = $address->getData();

        $attributeValues['street']=Array($attributeValues['street']); //cast to array

        $this->updateRegionData($attributeValues);
        $addressDataObject = $this->_addressDataFactory->create();
        $this->_dataObjectHelper->populateWithArray(
            $addressDataObject,
            $attributeValues,
            '\Magento\Customer\Api\Data\AddressInterface'
        );
        $addressDataObject->setCustomerId($customerId);
        /*
        TODO set is Default shipping/billing
        $addressDataObject->setCustomerId($this->_getSession()->getCustomerId())
            ->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
            ->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false));
        */
        return $addressDataObject;
    }


        /**
     * Update region data
     *
     * @param array $attributeValues
     * @return void
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function updateRegionData(&$attributeValues)
    {
        if ($this->helperData->isRegionRequired($attributeValues['country_id'])) {
            $newRegion = $this->regionFactory->create()->load($attributeValues['region_id']);
            $attributeValues['region_code'] = $newRegion->getCode();
            $attributeValues['region'] = $newRegion->getDefaultName();
        } else {
            $attributeValues['region_id'] = null;
        }

        $regionData = [
            RegionInterface::REGION_ID => !empty($attributeValues['region_id']) ? $attributeValues['region_id'] : null,
            RegionInterface::REGION => !empty($attributeValues['region']) ? $attributeValues['region'] : null,
            RegionInterface::REGION_CODE => !empty($attributeValues['region_code'])
                ? $attributeValues['region_code']
                : null,
        ];

        $region = $this->regionDataFactory->create();
        $this->_dataObjectHelper->populateWithArray(
            $region,
            $regionData,
            '\Magento\Customer\Api\Data\RegionInterface'
        );
        $attributeValues['region'] = $region;
    }

}
