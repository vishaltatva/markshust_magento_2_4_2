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

namespace Sparsh\ReviewReminder\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\ScopeInterface;
use Sparsh\ReviewReminder\Model\ResourceModel\ReviewReminderLog\CollectionFactory;
use Sparsh\ReviewReminder\Model\ReviewReminderLog;

/**
 * Class Data
 *
 * @category Sparsh
 * @package   Sparsh_ReviewReminder
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */
class Data extends AbstractHelper
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var ReviewReminderLog
     */
    private $reviewReminderLog;
    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * Data constructor.
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param ReviewReminderLog $reviewReminderLog
     * @param DateTime $dateTime
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        ReviewReminderLog $reviewReminderLog,
        DateTime $dateTime
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->reviewReminderLog = $reviewReminderLog;
        $this->dateTime = $dateTime;
    }

    /**
     * Path to configuration
     */
    const CONFIG_MODULE_PATH = 'review_reminder/';

    /**
     * Get system configuration value
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get system configuration value of general configuration
     */
    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::CONFIG_MODULE_PATH .'general/'. $code, $storeId);
    }

    /**
     * Get system configuration value of email configuration
     */
    public function getEmailConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::CONFIG_MODULE_PATH .'email/'. $code, $storeId);
    }

    /**
     * Get system configuration value of order configuration
     */
    public function getOrderConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::CONFIG_MODULE_PATH .'order/'. $code, $storeId);
    }

    public function clearLog($from)
    {
        $collection = $this->collectionFactory->create();
        foreach ($collection as $item) {
            if ($from == 'cron' && $this->getEmailConfig('clear_log')) {
                $date = date("Y-m-d", strtotime($item->getScheduleAt() . '+'.$this->getEmailConfig('clear_log').' day'));
                if (!empty($this->getEmailConfig('max_no_of_email')) && $this->getEmailConfig('max_no_of_email')!=0) {
                    if ($this->getEmailConfig('max_no_of_email') <= $item->getNoOfTimeSent()) {
                        if ($date <= $this->dateTime->gmtDate('Y-m-d')) {
                            $this->reviewReminderLog->load($item->getId());
                            $this->reviewReminderLog->delete();
                        }
                    }
                } else {
                    if ($date <= $this->dateTime->gmtDate('Y-m-d')) {
                        $this->reviewReminderLog->load($item->getId());
                        $this->reviewReminderLog->delete();
                    }
                }
            }
        }
    }
}
