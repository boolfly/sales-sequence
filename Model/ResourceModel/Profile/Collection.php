<?php declare(strict_types=1);
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Sales Sequence
 */
namespace Boolfly\SalesSequence\Model\ResourceModel\Profile;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'profile_id';

    protected function _construct()
    {
        $this->_init(
            'Magento\SalesSequence\Model\Profile',
            'Magento\SalesSequence\Model\ResourceModel\Profile'
        );
    }
}
