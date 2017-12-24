<?php

namespace MyShop\AdminBundle\Utils;


use MyShop\AdminBundle\DTO\UploadedImageResult;
//use Egulias\EmailValidator\EmailValidator;
//use Egulias\EmailValidator\Validation\RFCValidation;

class SendMailService
{
    private $mailer;
    private $templating;
    private $senderName;
    private $senderEmail;
    private $recipientName;
    private $recipientEmail;
    private $subject;
    private $message;
    private $fileAttachment;
    private $userNotificationsList;
    private $productId;
    private $absoluteUrl;
    private $flashMessage;

    /**
     * SendMailService constructor.
     * @param \Symfony\Bundle\TwigBundle\TwigEngine $templateting
     * @param \Swift_Mailer $mailer
     * @param $senderName
     * @param $senderEmail
     * @param $userNotificationsList
     */
    public function __construct($templateting,
                                \Swift_Mailer $mailer, $senderName, $senderEmail, $userNotificationsList)
    {
        $this->templating = $templateting;
        $this->mailer = $mailer;
        $this->senderName = $this->getSenderName() !== null ? $this->getSenderName() : $senderName;
        $this->senderEmail = $this->getSenderEmail() !== null ? $this->getSenderEmail() : $senderEmail;
        $this->recipientName = $this->getRecipientName();
        $this->recipientEmail = $this->getRecipientEmail();
        $this->subject = $this->getSubject() !== null ? $this->getSubject() : "";
        $this->message = $this->getMessage();
        $this->productId = $this->getProductId();
        $this->absoluteUrl = $this->getAbsoluteUrl();
        $this->fileAttachment = $this->getFileAttachment();
        $this->userNotificationsList = $userNotificationsList;
    }

    /**
     * @param string $type
     * @param string $history
     */
    public function sendMail($history = null, $type = "text/html") {
        $htmlResult = $this->templating->render("MyShopAdminBundle:email:email.html.twig", [
            "userAction" => $history,
            "userName" => $this->recipientName,
            "message" => $this->message
        ]);
//        $validator = new EmailValidator();
//        $validator->isValid("example@example.com", new RFCValidation()); //true
        $message = new \Swift_Message();
        $message->setTo($this->recipientEmail, $this->recipientName);
        $message->setFrom($this->senderEmail, $this->senderName);
        $message->setSubject($this->subject);
        $message->setBody($htmlResult, $type);
//        $message->attach(
//            Swift_Attachment::fromPath($this->fileAttachment)
//        );
        $this->mailer->send($message);
    }

    public function sendNotifyEmail($history = null, $type = "text/html") {
        dump($this->userNotificationsList);
        die();
        $htmlResult = $this->templating->render("MyShopAdminBundle:email:notify.html.twig", [
            "userAction" => $history,
            "userName" => $this->userNotificationsList
        ]);
        var_dump($this->userNotificationsList);
        $message = new \Swift_Message();
        $message->setTo($this->userNotificationsList);
        $message->setFrom($this->senderEmail, $this->senderName);
        $message->setBody($htmlResult, $type);
        $message->setSubject($this->subject);
//        $message->attach(\Swift_Attachment::fromPath($this->fileAttachment));
        $this->mailer->send($message);
    }

    /**
     * @return string
     */
    public function getSenderEmail()
    {
        return $this->senderEmail;
    }

    /**
     * @param string $senderEmail
     */
    public function setSenderEmail($senderEmail)
    {
        $this->senderEmail = $senderEmail;
    }

    /**
     * @return string
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * @param string $senderName
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;
    }

    /**
     * @return string
     */
    public function getRecipientName()
    {
        return $this->recipientName;
    }

    /**
     * @param string $recipientName
     */
    public function setRecipientName($recipientName)
    {
        $this->recipientName = $recipientName;
    }

    /**
     * @return string
     */
    public function getRecipientEmail()
    {
        return $this->recipientEmail;
    }

    /**
     * @param string $recipientEmail
     */
    public function setRecipientEmail($recipientEmail)
    {
        $this->recipientEmail = $recipientEmail;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getFileAttachment()
    {
        return $this->fileAttachment;
    }

    /**
     * @param string $fileAttachment
     */
    public function setFileAttachment($fileAttachment)
    {
        $this->fileAttachment = $fileAttachment;
    }

    /**
     * @return integer
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param integer $productId
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getAbsoluteUrl()
    {
        return $this->absoluteUrl;
    }

    /**
     * @param string $absoluteUrl
     */
    public function setAbsoluteUrl($absoluteUrl)
    {
        $this->absoluteUrl = $absoluteUrl;
    }

    /**
     * @return string
     */
    public function getFlashMessage()
    {
        return $this->flashMessage;
    }

    /**
     * @param string $flashMessage
     */
    public function setFlashMessage($flashMessage)
    {
        $this->flashMessage = $flashMessage;
    }
}