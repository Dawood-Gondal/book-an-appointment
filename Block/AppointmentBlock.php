<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_BookAnAppointment
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\BookAnAppointment\Block;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\Template;
use M2Commerce\BookAnAppointment\Helper\Email;

class AppointmentBlock extends Template
{
    /**
     * @var Email
     */
    protected $emailHelper;

    /**
     * @param Context $context
     * @param Email $emailHelper
     */
    public function __construct(
        Context $context,
        Email $emailHelper
    ) {
        $this->emailHelper = $emailHelper;
        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl("appointment/book/");
    }
}
