<?php
/**
 * Sparsh ReviewReminder Module
 * php version 7.0.31
 *
 * @category Sparsh
 * @package  Sparsh_ReviewReminder
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */

namespace Sparsh\ReviewReminder\Controller\Adminhtml\Log;

use Sparsh\ReviewReminder\Model\ReviewReminderLog;
use Magento\Backend\App\Action;

/**
 * Class Delete
 *
 * @category Sparsh
 * @package  Sparsh_ReviewReminder
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */
class Delete extends Action
{
    /**
     * StoreLocation Model
     *
     * @param array
     */
    protected $model;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param ReviewReminderLog $model
     */
    public function __construct(
        Action\Context $context,
        ReviewReminderLog $model
    ) {
        $this->model = $model;
        parent::__construct($context);
    }

    /**
     * Delete action
     *
     * @return $this
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $this->model->load($id);
                $this->model->delete();
                $this->messageManager->addSuccess(__('Log has been deleted successfully.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/index', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find log to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
