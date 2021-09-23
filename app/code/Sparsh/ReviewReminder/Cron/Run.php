<?php
/**
 * Sparsh ReviewReminder
 * php version 7.0.31
 *
 * @category Sparsh
 * @package  Sparsh_ReviewReminder
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */

namespace Sparsh\ReviewReminder\Cron;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Model\Order;
use Sparsh\ReviewReminder\Helper\Data;
use Sparsh\ReviewReminder\Model\ResourceModel\ReviewReminderLog\CollectionFactory as ReviewReminderLogCollectionFactory;
use Sparsh\ReviewReminder\Mail\SendMail;

/**
 * Class Run
 *
 * @category Sparsh
 * @package  Sparsh_ReviewReminder
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */
class Run extends \Magento\Framework\App\Action\Action
{

    /**
     * @var Data
     */
    private $data;
    /**
     * @var DateTime
     */
    private $dateTime;
    /**
     * @var Order
     */
    private $order;
    /**
     * @var ReviewReminderLogCollectionFactory
     */
    private $reviewReminderLogCollectionFactory;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var SendMail
     */
    private $sendMail;

    /**
     * Index constructor.
     * @param Context $context
     * @param Data $data
     * @param DateTime $dateTime
     * @param Order $order
     * @param ReviewReminderLogCollectionFactory $reviewReminderLogCollectionFactory
     * @param SerializerInterface $serializer
     * @param SendMail $sendMail
     */
    public function __construct(
        Context $context,
        Data $data,
        DateTime $dateTime,
        Order $order,
        ReviewReminderLogCollectionFactory $reviewReminderLogCollectionFactory,
        SerializerInterface $serializer,
        SendMail $sendMail
    ) {
        $this->data = $data;
        $this->dateTime = $dateTime;
        $this->order = $order;
        $this->reviewReminderLogCollectionFactory = $reviewReminderLogCollectionFactory;
        $this->serializer = $serializer;
        $this->sendMail = $sendMail;
        return parent::__construct($context);
    }

    /**
     * Index action execute
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if ($this->data->getGeneralConfig('enabled') == 1) {
            $this->data->clearLog('cron');

            $collection = $this->reviewReminderLogCollectionFactory->create();

            foreach ($collection as $item) {
                $flag = 1;

                $products = $this->serializer->unserialize($item->getProduct());
                $orderData = $this->order->load($item->getOrderId());

                if (!empty($this->data->getEmailConfig('email_send_after'))) {
                    $flag = $item->getScheduleAt() != $this->dateTime->gmtDate('Y-m-d') ? ($flag && 0) : ($flag && 1);
                }

                $productsData = array();
                foreach ($products as $product) {
                    if ($this->data->getOrderConfig('included_excluded') == "included" && !empty($this->data->getOrderConfig('include_skus'))) {
                        if(in_array($product['sku'], explode(",", $this->data->getOrderConfig('include_skus')))) {
                            $productsData[] = $product;
                        }
                    } elseif ($this->data->getOrderConfig('included_excluded') == "excluded" && !empty($this->data->getOrderConfig('exclude_skus'))) {
                        if(!in_array($product['sku'], explode(",", $this->data->getOrderConfig('exclude_skus')))) {
                            $productsData[] = $product;
                        }
                    } else {
                        $productsData[] = $product;
                    }
                }

                if (!empty($this->data->getEmailConfig('max_no_of_email'))) {
                    $flag = $this->data->getEmailConfig('max_no_of_email') <= $item->getNoOfTimeSent() ? ($flag && 0) : ($flag && 1);
                }

                if (!empty($this->data->getOrderConfig('order_status'))) {
                    $flag = in_array($orderData->getStatus(), explode(",", $this->data->getOrderConfig('order_status'))) ? ($flag && 1) : ($flag && 0);
                }

                if(empty($productsData)) {
                  $flag = $flag && 0;
                }

                $variables = [
                    'email' => $item->getEmail(),
                    'name' => $item->getName(),
                    'product' => $productsData,
                    'order' => $item->getOrder(),
                    'review_reminder_id' => $item->getId()
                ];

                if ($flag) {
                    $this->sendMail->mailSend($variables,"cron");
                }

            }
        }
    }
}
