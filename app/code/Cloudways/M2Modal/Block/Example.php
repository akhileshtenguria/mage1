<?php
namespace Cloudways\M2Modal\Block;

use \Magento\Store\Model\StoreRepository;
class Example extends \Magento\Framework\View\Element\Template
{
	 /**
     * @var Rate
     */
    protected $_storeManager;    
     
    /**
     * @param StoreRepository $storeRepository
     */
     public function __construct(
         StoreRepository $storeRepository,
        \Magento\Backend\Block\Template\Context $context,        
        \Magento\Store\Model\StoreManagerInterface $storeManager,        
        array $data = []
    ) {
         
    	$this->_storeManager = $storeManager;   
         $this->_storeRepository = $storeRepository;     
        parent::__construct($context, $data);
    }


    public function getContent()
    {
        $groups = $this->_storeManager->getWebsite()->getGroups();

        $storeList = [];
        $storeViewName = [];
        foreach ($groups as $key => $group) {
          
            $storeList[$group->getCode()] = $group->getName(); // get store name
            $stores = $group->getStores();
            /* foreach ($stores as $store) {
                $storeViewName[] = $store->getName(); // get store view name
            }*/
        }
    
        return array_reverse($storeList);
    }

     public function getStoreCode()
    {
        return $this->_storeManager->getStore()->getCode();
    }
}