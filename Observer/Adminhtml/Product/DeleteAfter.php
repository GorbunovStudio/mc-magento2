<?php

namespace Ebizmarts\MailChimp\Observer\Adminhtml\Product;

use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\ConfigurableFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;
use Ebizmarts\MailChimp\Helper\Sync as SyncHelper;

class DeleteAfter implements ObserverInterface
{
    /**
     * @var \Ebizmarts\MailChimp\Helper\Data
     */
    protected $helper;
    /**
     * @var Configurable
     */
    protected $configurable;
    private $syncHelper;

    public function __construct(
        \Ebizmarts\MailChimp\Helper\Data $helper,
        Configurable $configurable,
        SyncHelper $syncHelper
    ) {
        $this->helper               = $helper;
        $this->configurable         = $configurable;
        $this->syncHelper           = $syncHelper;
    }
    function execute(Observer $observer)
    {
        $product = $observer->getProduct();
        $mailchimpStore = $this->helper->getConfigValue(
            \Ebizmarts\MailChimp\Helper\Data::XML_MAILCHIMP_STORE,
            $product->getStoreId()
        );
        $this->_updateProduct($product->getId());
    }
    protected function _updateProduct($entityId)
    {
        $this->syncHelper->markEcommerceAsDeleted($entityId, \Ebizmarts\MailChimp\Helper\Data::IS_PRODUCT);
    }

}
