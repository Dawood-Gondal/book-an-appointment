<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_BookAnAppointment
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\BookAnAppointment\Controller\Book;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use M2Commerce\BookAnAppointment\Helper\Email;

class Index implements ActionInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var Email
     */
    protected $emailHelper;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @param RequestInterface $request
     * @param Email $emailHelper
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        RequestInterface $request,
        Email $emailHelper,
        JsonFactory $resultJsonFactory
    ) {
        $this->request = $request;
        $this->emailHelper = $emailHelper;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $post = $this->request->getPostValue();

        if (!isset($post["name"]) || $post["name"] == "" || !isset($post["email"]) || $post["email"] == "" || !isset($post["telephone"])
            || $post["telephone"] == "" || !isset($post["comment"]) || $post["comment"] == ""
        ) {
            return $resultJson->setData(["success" => false]);
        }

        $status = $this->sendMail($this->request->getPostValue());
        $response = [
            'success' => $status
        ];
        $resultJson->setData($response);
        return $resultJson;
    }

    /**
     * @param $post
     * @return bool
     */
    protected function sendMail($post)
    {
        $senderInfo = [
            'name' => $post["name"],
            'email' => $post["email"],
        ];

        $emailTempVariables['name'] = $post["name"];
        $emailTempVariables['email'] = $post["email"];
        $emailTempVariables['telephone'] = $post["telephone"];
        $emailTempVariables['comment'] = $post["comment"];

        return $this->emailHelper->sendAppointmentEmail(
            $emailTempVariables,
            $senderInfo
        );
    }
}
