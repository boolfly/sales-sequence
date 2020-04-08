<?php declare(strict_types=1);
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Sales Sequence
 */
namespace Boolfly\SalesSequence\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\SalesSequence\Model\MetaFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class EntityType extends Column
{
    /**
     * @var MetaFactory
     */
    private $metaFactory;

    /**
     * EntityType constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param MetaFactory $metaFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        MetaFactory $metaFactory,
        array $components = [],
        array $data = []
    ) {
        $this->metaFactory = $metaFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$this->getData('name')] = $this->prepareItem($item);
            }
        }

        return $dataSource;
    }

    /**
     * Get data
     *
     * @param array $item
     * @return string
     */
    protected function prepareItem(array $item)
    {
        $metaId = $item['meta_id'];
        /** @var \Magento\SalesSequence\Model\Meta $meta */
        $meta = $this->metaFactory->create()->load($metaId);
        if (!$meta->getId()) {
            return '';
        }
        return  ucfirst($meta->getEntityType());
    }
}
