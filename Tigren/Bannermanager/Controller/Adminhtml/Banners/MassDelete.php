<?php
/**
 *
 * Copyright © 2016 Tigren. All rights reserved.
 *
 */
namespace Tigren\Bannermanager\Controller\Adminhtml\Banners;

use Tigren\Bannermanager\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends \Tigren\Bannermanager\Controller\Adminhtml\Banners\Index
{
    /**
     * Massactions filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        PageFactory $resultPageFactory,
        CollectionFactory $collectionFactory
    )
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $resultPageFactory);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $bannerDeleted = 0;
        foreach ($collection->getItems() as $banner) {
            $banner->delete();
            $bannerDeleted++;
        }
        $this->messageManager->addSuccess(
            __('A total of %1 record(s) have been deleted.', $bannerDeleted)
        );

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('bannersmanager/*/');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tigren_Bannermanager::banner');
    }
}
