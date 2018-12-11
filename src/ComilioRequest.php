<?php

namespace Comilio;
use \Httpful\Request;

class ComilioRequest
{
    protected $username = false,
            $password = false;

    /**
    * Set authentication data
    * @return SmsMessage
    * @throws Exception
    */
    public function authenticate($api_username, $api_password)
    {
        $this->username = filter_var($api_username, FILTER_SANITIZE_STRING);
        $this->password = filter_var($api_password, FILTER_SANITIZE_STRING);

        if ($this->username === false) {
            throw new Exception("API Username cannot be empty");
        }

        if ($this->password === false) {
            throw new Exception("API Password cannot be empty");
        }

        return $this;

    }

    protected static function buildUrl($resource)
    {
       return 'https://api.comilio.it/rest/v1'.$resource;
    }

}
