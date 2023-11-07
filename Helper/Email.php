<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_BookAnAppointment
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\BookAnAppointment\Helper;

use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Custom Module Email helper
 */
class Email extends AbstractHelper
{
    const XML_PATH_EMAIL_TEMPLATE_FIELD = 'appointment/general/email_template';
    const XML_PATH_RECEIVER_EMAIL_FIELD = 'appointment/general/receiver_email';
    const XML_PATH_EMAIL_SUBJECT_FIELD = 'appointment/general/email_subject';

    /**
     * @var
     */
    protected $storeManager;

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var string
     */
    protected $templateId;

    /**
     * @var string
     */
    protected $recEmailIds;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param TransportBuilder $transportBuilder
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
    }

    /**
     * @param $emailTemplateVariables
     * @param $senderInfo
     * @return bool
     */
    public function sendAppointmentEmail($emailTemplateVariables, $senderInfo)
    {
        $success = false;
        try {
            $this->templateId = $this->getTemplateId(self::XML_PATH_EMAIL_TEMPLATE_FIELD);
            $this->recEmailIds = $this->getReceiverEmails(self::XML_PATH_RECEIVER_EMAIL_FIELD);
            $this->inlineTranslation->suspend();
            $this->generateTemplate($emailTemplateVariables, $senderInfo);
            $transport = $this->transportBuilder->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
            $success = true;
        } catch (\Exception $e) {
        }
        return $success;
    }

    /**
     * @param $path
     * @param $storeId
     * @return mixed
     */
    protected function getConfigValue($path, $storeId)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @return StoreInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStore()
    {
        return $this->storeManager->getStore();
    }

    /**
     * @param $xmlPath
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getTemplateId($xmlPath)
    {
        return $this->getConfigValue($xmlPath, $this->getStore()->getStoreId());
    }

    /**
     * @param $xmlPath
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getReceiverEmails($xmlPath)
    {
        return $this->getConfigValue($xmlPath, $this->getStore()->getStoreId());
    }

    /**
     * @param $emailTemplateVariables
     * @param $senderInfo
     * @return $this
     */
    public function generateTemplate($emailTemplateVariables, $senderInfo)
    {
        $receiverInfo = [];
        if (!empty($this->recEmailIds)) {
            $emailSep = explode(",", $this->recEmailIds);

            if (!empty($emailSep)) {
                $receiverInfo = $emailSep;
            }
        }

        $emailTemplateVariables["email_subject"] = $this->getEmailSubject();
        $template = $this->transportBuilder->setTemplateIdentifier($this->templateId)
            ->setTemplateOptions([
                'area' => Area::AREA_FRONTEND,
                'store' => $this->storeManager->getStore()->getId(),
            ])
            ->setTemplateVars($emailTemplateVariables)
            ->setFrom($senderInfo)
            ->addTo($receiverInfo);

        return $this;
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getEmailSubject()
    {
        return $this->getConfigValue(self::XML_PATH_EMAIL_SUBJECT_FIELD, $this->getStore()->getStoreId());
    }
}
