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

namespace Sparsh\ReviewReminder\Model;

use Magento\Framework\Model\AbstractModel;
use Sparsh\ReviewReminder\Api\Data\ReviewReminderInterface;

/**
 * class ReviewReminderLog
 *
 * @category Sparsh
 * @package   Sparsh_ReviewReminder
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */
class ReviewReminderLog extends AbstractModel implements ReviewReminderInterface
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init(\Sparsh\ReviewReminder\Model\ResourceModel\ReviewReminderLog::class);
    }

    /**
     * Get Order
     *
     * @return string
     */
    public function getOrder()
    {
        // TODO: Implement getOrder() method.
        return $this->getData(self::ORDER);
    }

    /**
     * Get Receiver
     *
     * @return string|null
     */
    public function getReceiver()
    {
        // TODO: Implement getReceiver() method.
        return $this->getData(self::RECEIVER);
    }

    /**
     * Get Status
     *
     * @return string|null
     */
    public function getStatus()
    {
        // TODO: Implement getStatus() method.
        return $this->getData(self::STATUS);
    }

    /**
     * Get Error Message
     *
     * @return string|null
     */
    public function getErrormsg()
    {
        // TODO: Implement getErrormsg() method.
        return $this->getData(self::ERRORMSG);
    }

    /**
     * Get Creation Time
     *
     * @return string|null
     */
    public function getCreationTime()
    {
        // TODO: Implement getCreationTime() method.
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * Get Schedule At
     *
     * @return string|null
     */
    public function getScheduleAt()
    {
        // TODO: Implement getScheduleAt() method.
        return $this->getData(self::SCHEDULE_AT);
    }

    /**
     * Get No Of Time Sent
     *
     * @return string|null
     */
    public function getNoOfTimeSent()
    {
        // TODO: Implement getNoOfTimeSent() method.
        return $this->getData(self::NO_OF_TIME_SENT);
    }

    /**
     * Set Order
     *
     * @param string $order
     * @return \Sparsh\ReviewReminder\Api\Data\ReviewReminderInterface
     */
    public function setOrder($order)
    {
        // TODO: Implement setOrder() method.
        return $this->setData(self::ORDER, $order);
    }

    /**
     * Set Receiver
     *
     * @param $receiver
     * @return \Sparsh\ReviewReminder\Api\Data\ReviewReminderInterface
     */
    public function setReceiver($receiver)
    {
        // TODO: Implement setReceiver() method.
        return $this->setData(self::RECEIVER, $receiver);
    }

    /**
     * Set Status
     *
     * @param $status
     * @return \Sparsh\ReviewReminder\Api\Data\ReviewReminderInterface
     */
    public function setStatus($status)
    {
        // TODO: Implement setStatus() method.
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Set Error Message
     *
     * @param $errormsg
     * @return \Sparsh\ReviewReminder\Api\Data\ReviewReminderInterface
     */
    public function setErrormsg($errormsg)
    {
        // TODO: Implement setErrormsg() method.
        return $this->setData(self::ERRORMSG, $errormsg);
    }

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return \Sparsh\ReviewReminder\Api\Data\ReviewReminderInterface
     */
    public function setCreationTime($creationTime)
    {
        // TODO: Implement setCreationTime() method.
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * Set No Of Time Sent
     *
     * @param $noOfTimeSent
     * @return \Sparsh\ReviewReminder\Api\Data\ReviewReminderInterface
     */
    public function setNoOfTimeSent($noOfTimeSent)
    {
        // TODO: Implement setNoOfTimeSent() method.
        return $this->setData(self::NO_OF_TIME_SENT, $noOfTimeSent);
    }

    /**
     * Set Schedule At
     *
     * @param $scheduleAt
     * @return \Sparsh\ReviewReminder\Api\Data\ReviewReminderInterface
     */
    public function setScheduleAt($scheduleAt)
    {
        // TODO: Implement setScheduleAt() method.
        return $this->setData(self::SCHEDULE_AT, $scheduleAt);
    }

    /**
     * Get Product
     *
     * @return string|null
     */
    public function getProduct()
    {
        // TODO: Implement getProduct() method.
        return $this->getData(self::PRODUCT);
    }

    /**
     * Set Product
     *
     * @param $product
     * @return \Sparsh\ReviewReminder\Api\Data\ReviewReminderInterface
     */
    public function setProduct($product)
    {
        // TODO: Implement setProduct() method.
        return $this->setData(self::PRODUCT, $product);
    }

    /**
     * Get Customer Group Id
     *
     * @return string|null
     */
    public function getCustomerGroupId()
    {
        // TODO: Implement getCustomerGroupId() method.
        return $this->getData(self::SKU);
    }

    /**
     * Get OrderId
     *
     * @return string|null
     */
    public function getOrderId()
    {
        // TODO: Implement getOrderId() method.
        return $this->getData(self::ORDER_ID);
    }

    /**
     * Set Customer Group Id
     *
     * @param $customerGroupId
     * @return \Sparsh\ReviewReminder\Api\Data\ReviewReminderInterface
     */
    public function setCustomerGroupId($customerGroupId)
    {
        // TODO: Implement setCustomerGroupId() method.
        return $this->setData(self::CUSTOMER_GROUP_ID, $customerGroupId);
    }

    /**
     * Set Order Id
     *
     * @param $orderId
     * @return \Sparsh\ReviewReminder\Api\Data\ReviewReminderInterface
     */
    public function setOrderId($orderId)
    {
        // TODO: Implement setOrderId() method.
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * Get Name
     *
     * @return string|null
     */
    public function getName()
    {
        // TODO: Implement getName() method.
        return $this->getData(self::NAME);
    }

    /**
     * Set Name
     *
     * @param $name
     * @return \Sparsh\ReviewReminder\Api\Data\ReviewReminderInterface
     */
    public function setName($name)
    {
        // TODO: Implement setName() method.
        return $this->setData(self::NAME, $name);
    }
}
