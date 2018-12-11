<?php

namespace Comilio;
use \Httpful\Request;

class SmsMessage extends ComilioRequest
{
    const SMS_TYPE_CLASSIC = 'Classic';
    const SMS_TYPE_SMART = 'Smart';
    const SMS_TYPE_SMARTPRO = 'SmartPro';

    private $type = self::SMS_TYPE_SMART,
            $sender = null,
            $recipients = null,
            $sms_id = null,
            $status = null;

    public function __construct($sms_id = null)
    {
        $this->sms_id = $sms_id;
    }

    /**
    * Sets SMS type
    * @param string $type message type (one of SmsMessage::SMS_TYPE_CLASSIC, SmsMessage::SMS_TYPE_SMART or SmsMessage::SMS_TYPE_SMARTPRO) defaults to SmsMessage::SMS_TYPE_SMART
    * @return SmsMessage
    * @throws Exception
    */
    public function setType($type)
    {
        if (in_array($type, array(self::SMS_TYPE_CLASSIC, self::SMS_TYPE_SMART, self::SMS_TYPE_SMARTPRO))) {
            $this->type = $type;
        } else {
            $this->type = self::SMS_TYPE_SMART;
            throw new Exception("Specified type is not valid");
        }

        return $this;
    }

    /**
    * Sets SMS Sender
    * @param string $sender Sender string can be alphanumeric (up to 11 chars) or numeric (e.g. +393401234567)
    * @return SmsMessage
    * @throws Exception
    */
    public function setSender($sender)
    {
        $_sender = filter_var($sender, FILTER_SANITIZE_STRING);

        if (self::isValidNumberFormat($_sender) === false) {
            $sender_length = strlen($_sender);
            if ($sender_length > 11) {
                $this->sender = null;
                throw new Exception("Specified sender '$sender' is not valid");
                return $this;
            }
        }

        $this->sender = $_sender;
        return $this;
    }

    /**
    * Sets SMS Recipients
    * @param string|array $recipients Recipients string or array
    * @return SmsMessage
    * @throws Exception
    */
    public function setRecipients($recipients)
    {
        $_recipients = is_array($recipients) ? $recipients : array($recipients);

        foreach($_recipients as $i => $recipient) {
            if (self::isValidNumberFormat($recipient) === false) {
                $this->recipients = null;
                throw new Exception("Recipient '$recipient' is not valid");
                return $this;
            }
        }

        $this->recipients = $_recipients;
        return $this;
    }

    /**
    * Send an SMS message
    * @param string $message message text to be sent
    * @return bool TRUE on success, FALSE otherwise
    * @throws Exception
    */
    public function send($message)
    {
        if (is_string($message) === false || strlen($message) === 0) {
            throw new Exception("SMS message text cannot be empty");
        }

        if ($this->recipients === null || count($this->recipients) === 0) {
            throw new Exception("At least one recipient is required");
        }

        if ($this->username === false || $this->password === false) {
            throw new Exception("Auth required");
        }

        $payload = array(
           'message_type' => $this->type,
           'phone_numbers' => $this->recipients,
           'text' => $message
       );

        if ($this->sender !== null) {
            $payload['sender_string'] = $this->sender;
        }

        $post_request = Request::post(self::buildUrl('/message'), json_encode($payload), 'application/json');
        $post_request->expects('application/json');
        $post_request->autoParse(true);
        $post_request->authenticateWithBasic($this->username, $this->password);
        $result = $post_request->send();

        switch ($result->code) {
            case 200:
                $this->sms_id = $result->body->message_id;
                return true;
            case 401:
                throw new Exception("Authentication failed");
                return false;
            default:
                throw new Exception("Unable to send SMS. Gateway response: $result->raw_body");
                return false;
        }

        return true;
    }

    /**
    * Retrieve sent SMS status
    * @return array of stdClasses (e.g. [{"phone_number":"+393401234567","status":"Sent"},{"phone_number":"+393498765432","status":"Delivering"}])
    * @throws Exception
    */
    public function getStatus($sms_id = null)
    {
        $_sms_id = $sms_id === null ? $this->sms_id : $sms_id;

        if ($_sms_id === null) {
            throw new Exception("SMS message ID not set");
        }

        if ($this->username === false || $this->password === false) {
            throw new Exception("Auth required");
        }

        $get_request = Request::get(self::buildUrl("/message/{$_sms_id}"), 'application/json');
        $get_request->expects('application/json');
        $get_request->autoParse(true);
        $get_request->authenticateWithBasic($this->username, $this->password);
        $result = $get_request->send();

        if ($result->code !== 200) {
            throw new Exception("Unable to get SMS status. Gateway response: $result->raw_body");
            return false;
        }

        return $result->body;
    }

    public function getId()
    {
        return $this->sms_id;
    }

    /**
    * Check format of a number
    * @param string $number Number to be checked
    * @return bool
    */
    public static function isValidNumberFormat($number) {
        return preg_match('/^\+?[0-9]{4,14}$/', $number) === 1;
    }


}
