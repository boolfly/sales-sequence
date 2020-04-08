<?php declare(strict_types=1);
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Sales Sequence
 */
namespace Boolfly\SalesSequence\Controller\Adminhtml\Profile;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\SalesSequence\Model\ProfileFactory;

class Edit extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Boolfly_SalesSequence::profile';
    /**
     * @var ProfileFactory
     */
    private $profileFactory;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry = null;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     * @param ProfileFactory $profileFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $registry,
        ProfileFactory $profileFactory
    ) {
        $this->profileFactory = $profileFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $entityId = $this->getRequest()->getParam('profile_id');
        $profile = $this->profileFactory->create();
        if ($entityId) {
            $profile->load($entityId);
            if (!$profile->getId()) {
                $this->messageManager->addErrorMessage(__('This sequence no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        //Set entered data if was error when do save
        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $profile->setData($data);
        }
        $this->coreRegistry->register('bf_profile', $profile);
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Boolfly_SalesSequence::profile')
            ->addBreadcrumb(__('Boolfly SalesSequence'), __('Profile'))
            ->addBreadcrumb(__('Boolfly Profile'), __('Profile'));
        $resultPage->getConfig()->getTitle()->prepend(__('Profile'));
        $resultPage->getConfig()->getTitle()
            ->prepend(__('Profile'));

        return $resultPage;
    }
}
