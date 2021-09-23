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

namespace Sparsh\ReviewReminder\Observer;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Model\Order;
use Magento\Store\Model\StoreManagerInterface;
use Sparsh\ReviewReminder\Helper\Data;
use Sparsh\ReviewReminder\Model\ReviewReminderLogFactory;
use Magento\Catalog\Helper\ImageFactory;
use Magento\Store\Model\App\Emulation;


/**
 * Class ReviewReminderLog
 *
 * @category Sparsh
 * @package  Sparsh_ReviewReminder
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */
class ReviewReminderLog implements ObserverInterface
{
    /**
     * @var Data
     */
    private $data;
    /**
     * @var ReviewReminderLogFactory
     */
    private $reviewReminderLogFactory;
    /**
     * @var Order
     */
    private $order;
    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * ReviewReminderLog constructor.
     * @param Data $data
     * @param Order $order
     * @param DateTime $dateTime
     * @param ProductCollectionFactory $productCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param SerializerInterface $serializer
     * @param ReviewReminderLogFactory $reviewReminderLogFactory
     */
    public function __construct(
        Data $data,
        Order $order,
        DateTime $dateTime,
        ProductCollectionFactory $productCollectionFactory,
        StoreManagerInterface $storeManager,
        SerializerInterface $serializer,
        ReviewReminderLogFactory $reviewReminderLogFactory,
        ImageFactory $imageHelper,
        Emulation $appEmulation
    ) {
        $this->data = $data;
        $this->reviewReminderLogFactory = $reviewReminderLogFactory;
        $this->order = $order;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->storeManager = $storeManager;
        $this->serializer = $serializer;
        $this->dateTime = $dateTime;
        $this->imageHelper = $imageHelper;
        $this->_appEmulation = $appEmulation;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->data->getGeneralConfig('enabled') == 1) {
            if($observer->getEvent() == "checkout_onepage_controller_success_action") {
                $order = $observer->getEvent()->getOrder();
                $order = $this->order->load($order->getId());
                $this->generateLog($order);
            } else {
                $order = $observer->getEvent()->getOrderIds();
                foreach ($order as $data) {
                    $order = $this->order->load($data);
                    $this->generateLog($order);
                }
            }

        }
    }

    public function generateLog($order) {
        foreach ($order->getAllItems() as $item) {
            if (empty($item->getParentItemId())) {
                $productCollection = $this->productCollectionFactory->create()
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('entity_id', $item->getProductId());
                foreach ($productCollection as $product) {
                    if(empty($product->getImage())) {
                        $storeId = $this->storeManager->getStore()->getId();
                        $this->_appEmulation->startEnvironmentEmulation($storeId, \Magento\Framework\App\Area::AREA_FRONTEND, true);
                        $productImageUrl = $this->imageHelper->create()->getDefaultPlaceholderUrl('image');
                        $this->_appEmulation->stopEnvironmentEmulation();
                    } else {
                        $productImageUrl = $this->storeManager
                                ->getStore()
                                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $product->getImage();
                    }
                    $productData[] = [
                        'image' => $productImageUrl,
                        'name' => $product->getName(),
                        'sku' => $product->getSku(),
                        'productUrl' => $product->getProductUrl()
                    ];
                }
            }
        }
        $scheduleDate = !empty($this->data->getEmailConfig('email_send_after')) ? date("Y-m-d", strtotime($order->getCreatedAt() . '+' . $this->data->getEmailConfig("email_send_after") . ' day')) : $this->dateTime->gmtDate('Y-m-d', $order->getCreatedAt());

        if (!empty($this->data->getGeneralConfig('customer_groups')) || $this->data->getGeneralConfig('customer_groups') == "0") {
            if(in_array($order->getCustomerGroupId(),  explode(",",$this->data->getGeneralConfig('customer_groups')))) {
                $reviewReminderLogFactory = $this->reviewReminderLogFactory->create();
                $reviewReminderLogFactory->setOrder($order->getIncrementId())
                    ->setReceiver($order->getCustomerEmail())
                    ->setStatus('Pending')
                    ->setProduct(!empty($productData) ? $this->serializer->serialize($productData) : "")
                    ->setScheduleAt($scheduleDate)
                    ->setOrderId($order->getId( ))
                    ->setCustomerGroupId($order->getCustomerGroupId())
                    ->setName($order->getCustomerName())
                    ->save();
            }
        }
    }
}
