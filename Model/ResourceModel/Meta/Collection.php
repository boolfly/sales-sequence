<?php declare(strict_types=1);
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Sales Sequence
 */
namespace Boolfly\SalesSequence\Model\ResourceModel\Meta;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'meta_id';

    protected function _construct()
    {
        $this->_init(
            'Magento\SalesSequence\Model\Meta',
            'Magento\SalesSequence\Model\ResourceModel\Meta'
        );
    }
}
