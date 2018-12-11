<?php

namespace Comilio;
use \Httpful\Request;

class Credit extends ComilioRequest
{

    public function get()
    {
        $get_request = Request::get(self::buildUrl('/credits'));
        $get_request->autoParse(true);
        $get_request->authenticateWithBasic($this->username, $this->password);

        $result = $get_request->send();

        switch ($result->code) {
            case 200:
                return $result->body;
            case 401:
                throw new Exception("Authentication failed");
                return false;
            default:
                throw new Exception("Unable to get SMS credits. Gateway response: $result->raw_body");
                return false;
        }
    }
    

}
