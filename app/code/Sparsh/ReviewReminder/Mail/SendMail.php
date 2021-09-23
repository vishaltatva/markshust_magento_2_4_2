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

namespace Sparsh\ReviewReminder\Mail;

use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Sparsh\ReviewReminder\Helper\Data;
use Magento\Framework\Message\ManagerInterface;
use Sparsh\ReviewReminder\Model\ReviewReminderLogFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * class SendMail
 *
 * @category Sparsh
 * @package   Sparsh_ReviewReminder
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */
class SendMail
{
    /**
     * @var Data
     */
    private $data;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var StateInterface
     */
    private $inlineTranslation;
    /**
     * @var TransportBuilder
     */
    private $transportBuilder;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var ManagerInterface
     */
    private $messageManager;
    /**
     * @var ReviewReminderLogFactory
     */
    private $reviewReminderLogFactory;
    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * SendMail constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param StateInterface $inlineTranslation
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param ManagerInterface $messageManager
     * @param ReviewReminderLogFactory $reviewReminderLogFactory
     * @param Data $data
     * @param DateTime $dateTime
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        ManagerInterface $messageManager,
        ReviewReminderLogFactory $reviewReminderLogFactory,
        Data $data,
        DateTime $dateTime
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->data = $data;
        $this->messageManager = $messageManager;
        $this->reviewReminderLogFactory = $reviewReminderLogFactory;
        $this->dateTime = $dateTime;
    }

    /**
     * @param $variables
     */
    public function mailSend($variables,$source = null)
    {
        $reviewReminderLogFactory = $this->reviewReminderLogFactory->create();
        $this->inlineTranslation->suspend();
        try {
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($this->data->getEmailConfig('email_template'))
                ->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => $this->storeManager->getStore()->getId()
                    ]
                )
                ->setTemplateVars($variables)
                ->setFrom($this->data->getEmailConfig('email_sender'))
                ->addTo($variables['email'])
                ->getTransport();
            $transport->sendMessage();
            $reviewReminderLogFactory->load($variables['review_reminder_id']);

            $maxEmail = $this->data->getEmailConfig('max_no_of_email');
            $totalSent = $reviewReminderLogFactory->getNoOfTimeSent() + 1;

            $scheduleDate = ($source == "cron" && !empty($this->data->getEmailConfig('email_send_after')) && $maxEmail >= $totalSent) ? date("Y-m-d", strtotime($reviewReminderLogFactory->getScheduleAt() . '+'.$this->data->getEmailConfig("email_send_after").' day')) : $this->dateTime->gmtDate('Y-m-d', $reviewReminderLogFactory->getScheduleAt());

            $reviewReminderLogFactory
                ->setNoOfTimeSent($totalSent)
                ->setScheduleAt($scheduleDate)
                ->setStatus('Sent')
                ->save();
			$this->messageManager->addSuccessMessage("Reminder is sent successfully.");
        } catch (\Exception $e) {
            $reviewReminderLogFactory->load($variables['review_reminder_id']);
            $reviewReminderLogFactory
                ->setStatus('Error')
                ->setErrormsg($e->getMessage())
                ->save();
            $this->messageManager->addError("Reminder could not be sent. <br/>".$e->getMessage());
        } finally {
            $this->inlineTranslation->resume();
        }
    }
}
