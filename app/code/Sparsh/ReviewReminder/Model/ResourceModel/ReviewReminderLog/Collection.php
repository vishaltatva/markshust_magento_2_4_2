<?php
/**
 * Sparsh ReviewReminder
 * php version 7.0.31
 *
 * @category Sparsh
 * @package   Sparsh_ReviewReminder
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */

namespace Sparsh\ReviewReminder\Model\ResourceModel\ReviewReminderLog;

/**
 * Class Collection
 *
 * @category Sparsh
 * @package  Sparsh_ReviewReminder
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'review_reminder_id';
    protected $_eventPrefix = 'review_reminder_entity_collection';
    protected $_eventObject = 'review_reminder_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Sparsh\ReviewReminder\Model\ReviewReminderLog::class,
            \Sparsh\ReviewReminder\Model\ResourceModel\ReviewReminderLog::class
        );
    }
}
