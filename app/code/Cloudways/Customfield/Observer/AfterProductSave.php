<?php

namespace Cloudways\Customfield\Observer;

use Magento\Framework\Event\ObserverInterface;

class AfterProductSave implements ObserverInterface
{

    /**
     * Custom factory
     *
     * @var \Vendor\ModuleName\Model\CustomProductsFactory
     */
    protected $_CustomProductsFactory;

    /**
     * Http Request
     *
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;


    /**
     * @param \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup
     * @param \Magento\Framework\App\Request\Http $request
     * @param array $data
     */
    public function __construct(
         \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory  $customProductsFactory,
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    ) {
        $this->_CustomProductsFactory = $CustomProductsFactory;
        $this->request = $request;
    }


    /**
     *
     *  @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getProduct();

        if ((!empty($product))) {
            $productId = $product->getId();
            $requestId = $this->request->getParam('id');
            $productName =  $product->getName();
            $customFieldSetData = serialize($product->getCustomFieldSet());
            if ($productId) {
                $this->saveCustomProductData($requestId,$productId,$customFieldSetData);
            }
        }
    }


    public function saveCustomProductData($requestId,$productId,$customFieldSetData)
    {

        $model = $this->_CustomProductsFactory->create();

        if (empty($requestId)) {
            $data = ['custom_data'=>$customFieldSetData,'product_id'=>$productId];
        } else {
            //load model
            $model->load($productId, 'product_id');
            $data = ['custom_data'=>$customFieldSetData,'product_id'=>$productId];
        }

        $model->addData($data);

        $model->save();
    }
}