<?php
/**
 * Sparsh  ReviewReminder Module
 * php version 7.0.31
 *
 * @category Sparsh
 * @package   Sparsh_ReviewReminder
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */

namespace Sparsh\ReviewReminder\Controller\Adminhtml\Log;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Serialize\SerializerInterface;
use Sparsh\ReviewReminder\Helper\Data;
use Magento\Sales\Model\Order;
use Sparsh\ReviewReminder\Model\ReviewReminderLog;
use Sparsh\ReviewReminder\Mail\SendMail;

/**
 * Class SendNow
 *
 * @category Sparsh
 * @package   Sparsh_ReviewReminder
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */
class SendNow extends \Magento\Backend\App\Action
{
    /**
     * @var ReviewReminderLog
     */
    protected $model;

    /**
     * @var Data
     */
    private $data;
    /**
     * @var Order
     */
    private $order;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var SendMail
     */
    private $sendMail;

    /**
     * Edit constructor.
     * @param Context $context
     * @param ReviewReminderLog $model
     * @param SerializerInterface $serializer
     * @param Data $data
     * @param SendMail $sendMail
     */
    public function __construct(
        Context $context,
        ReviewReminderLog $model,
        SerializerInterface $serializer,
        Data $data,
        Order $order,
        SendMail $sendMail
    ) {
        $this->model = $model;
        $this->data = $data;
        $this->order = $order;
        $this->serializer = $serializer;
        $this->sendMail = $sendMail;
        parent::__construct($context);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if ($this->data->getGeneralConfig('enabled') == 0) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('admin/dashboard/index', ['_current' => true]);
        }
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $this->model->load($id);
            if (!$this->model->getId()) {
                $this->messageManager->addError(__('This log is no longer exists.'));
            }

            $flag = 1;
            $errorMsg = '';

            if(!empty($this->model->getProduct())) {
                $products = $this->serializer->unserialize($this->model->getProduct());
            }
            $orderData = $this->order->load($this->model->getOrderId());

            $productsData = array();
            if(!empty($products)) {
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
            }

            $this->model->setProduct($productsData);
            if(empty($productsData)) {
              $flag = $flag && 0;
              $errorMsg .= 'No product found to be sent as review reminder.<br />';
            }

            if (!empty($this->data->getEmailConfig('max_no_of_email'))) {
                $flag = $this->data->getEmailConfig('max_no_of_email') <= $this->model->getNoOfTimeSent() ? ($flag && 0) : ($flag && 1);

                if($this->data->getEmailConfig('max_no_of_email') <= $this->model->getNoOfTimeSent()){
                    $errorMsg .= 'Maximum number of email per order limit is reached.<br />';
                }
            }

            if (!empty($this->data->getOrderConfig('order_status'))) {
                $flag = in_array($orderData->getStatus(), explode(",", $this->data->getOrderConfig('order_status'))) ? ($flag && 1) : ($flag && 0);

                if(!in_array($orderData->getStatus(), explode(",", $this->data->getOrderConfig('order_status')))){
                    $errorMsg .= 'Only selected order status are allowed.<br />';
                }
            }

            if ($flag) {
                $this->sendMail->mailSend($this->model->getData());
            } else {
                $this->messageManager->addError(__($errorMsg.' Please check review reminder <a href="'.$this->getUrl('adminhtml/system_config/edit/section/review_reminder').'">configuration</a>.'));
            }
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }
    }
}
