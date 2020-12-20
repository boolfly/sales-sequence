<?php declare(strict_types=1);
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Sales Sequence
 */
namespace Boolfly\SalesSequence\Model\Profile;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\SalesSequence\Model\Profile;
use Magento\SalesSequence\Model\ProfileFactory;
use Boolfly\SalesSequence\Model\ResourceModel\Profile\Collection;
use Boolfly\SalesSequence\Model\ResourceModel\Profile\CollectionFactory;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use Magento\SalesSequence\Model\MetaFactory;

class DataProvider extends ModifierPoolDataProvider
{
    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var ProfileFactory
     */
    private $profileFactory;

    /**
     * @var MetaFactory
     */
    private $metaFactory;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param DataPersistorInterface $dataPersistor
     * @param ProfileFactory $profileFactory
     * @param MetaFactory $metaFactory
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        DataPersistorInterface $dataPersistor,
        ProfileFactory $profileFactory,
        MetaFactory $metaFactory,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->profileFactory = $profileFactory;
        $this->metaFactory = $metaFactory;
        $this->dataPersistor = $dataPersistor;
        $this->collection = $collectionFactory->create();
        $this->collection->addFieldToSelect('*');
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var Profile $profile */
        foreach ($items as $profile) {
            $meta = $this->metaFactory->create()->load($profile->getMetaId());
            $profile->setData('store_id', $meta->getStoreId());
            $profile->setData('entity_type', ucfirst($meta->getEntityType()));
            $this->loadedData[$profile->getId()] = $profile->getData();
        }

        $data = $this->dataPersistor->get('bf_profile');
        if (!empty($data)) {
            $profile = $this->collection->getNewEmptyItem();
            $meta = $this->metaFactory->create()->load($profile->getMetaId());
            $profile->setData('store_id', $meta->getStoreId());
            $profile->setData('entity_type', ucfirst($meta->getEntityType()));
            $profile->setData($data);
            $this->loadedData[$profile->getId()] = $profile->getData();
            $this->dataPersistor->clear('bf_profile');
        }
        return $this->loadedData;
    }
}
