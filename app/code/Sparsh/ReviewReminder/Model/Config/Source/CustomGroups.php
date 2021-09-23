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

namespace Sparsh\ReviewReminder\Model\Config\Source;

use \Magento\Customer\Model\ResourceModel\Group\Collection;

/**
 * Class CustomGroups
 *
 * PHP version 7
 *
 * @category Sparsh
 * @package  Sparsh_ReviewReminder
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */
class CustomGroups implements \Magento\Framework\Option\ArrayInterface
{
    protected $_customerGroup;

    protected $_options;

    public function __construct( Collection $customerGroup ) {
        $this->_customerGroup = $customerGroup; 
    }

    /**
     * Customer groups without all groups value.
     *
     * @return array
     */
    public function toOptionArray() {
        if (!$this->_options) {
            $this->_options = $this->_customerGroup->toOptionArray();
        }
        return $this->_options;
    }
   
}
