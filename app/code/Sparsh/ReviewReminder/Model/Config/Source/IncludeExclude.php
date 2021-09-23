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

/**
 * Class IncludeExclude
 *
 * PHP version 7
 *
 * @category Sparsh
 * @package  Sparsh_ReviewReminder
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */
class IncludeExclude implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Path to configuration, set select options for distance unit
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '', 'label' => __('Please select....')],
            ['value' => 'included', 'label' => __('Included')],
            ['value' => 'excluded', 'label' => __('Excluded')]
        ];
    }
}
