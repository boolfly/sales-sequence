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
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\SalesSequence\Model\ProfileFactory;

class Save extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Boolfly_SalesSequence::profile';
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var ProfileFactory
     */
    private $profileFactory;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param ProfileFactory $profileFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        ProfileFactory $profileFactory,
        DataPersistorInterface $dataPersistor
    ) {
        $this->profileFactory = $profileFactory;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (!isset($data['profile_id'])) {
                $this->messageManager->addErrorMessage(__('There is not profile id.'));
                return $resultRedirect->setPath('*/*/');
            }
            $profile = $this->profileFactory->create()->load($data['profile_id']);
            if (!$profile->getId()) {
                $this->messageManager->addErrorMessage(__('The profile no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            try {
                $profile->setData('prefix', $data['prefix']);
                $profile->setData('suffix', $data['suffix']);
                $profile->save();
                $this->messageManager->addSuccessMessage(__('Saved profile data.'));
                $this->dataPersistor->clear('bf_profile');
                return $this->processReturn($profile, $data, $resultRedirect);
            } catch (\Exception $ex) {
                $this->messageManager->addExceptionMessage($ex, __('Something went wrong while saving profile.'));
            }
            $this->dataPersistor->set('bf_profile', $data);
            return $resultRedirect->setPath('*/*/edit', ['profile_id' => $data['profile_id']]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $model
     * @param $data
     * @param $resultRedirect
     * @return mixed
     */
    public function processReturn($model, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';
        if ($redirect ==='continue') {
            $resultRedirect->setPath('*/*/edit', ['profile_id' => $model->getId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        }
        return $resultRedirect;
    }
}
