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

namespace Sparsh\ReviewReminder\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Sparsh\ReviewReminder\Model\ReviewReminderLogFactory;
/**
 * Class Errormsginfo
 *
 * @category Sparsh
 * @package   Sparsh_ReviewReminder
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */
class Errormsginfo extends Column
{

    /**
     * @var ReviewReminderLogFactory
     */
    private $reviewReminderLogFactory;
    /**
     * Constructor
     *
     * @param ContextInterface   $context            Context
     * @param UiComponentFactory $uiComponentFactory UiComponentFactory
     * @param UrlInterface       $urlBuilder         UrlBuilder
     * @param array              $components         Components
     * @param array              $data               Data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ReviewReminderLogFactory $reviewReminderLogFactory,
        array $components = [],
        array $data = []
    ) {
        $this->reviewReminderLogFactory = $reviewReminderLogFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource DataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $reviewReminderLogFactory = $this->reviewReminderLogFactory->create();
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['review_reminder_id'])) {
                    $reviewReminderLogFactory->load($item['review_reminder_id']);
                    $errormsg = $reviewReminderLogFactory->getErrormsg();
                    $status = $reviewReminderLogFactory->getStatus();
                    if($status == 'Error') {
                        $item[$this->getData('name')] = html_entity_decode("<p title=\"$errormsg\" style='color:#ff0000;text-decoration:underline;cursor:default;'>" . $status . "</p>");
                    } else {
                        $item[$this->getData('name')] = $status;
                    }
                }
            }
        }
        return $dataSource;
    }
}
