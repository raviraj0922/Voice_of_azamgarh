<?php

class PHP_Email_Form {
    public $to;
    public $from_name;
    public $from_email;
    public $subject;
    public $message;
    public $headers;
    public $smtp = false;
    public $smtp_host = 'localhost';
    public $smtp_port = 25;
    public $smtp_username = 'username';
    public $smtp_password = 'password';

    public function add_message($message, $field = '', $maxlength = 0) {
        if ($maxlength > 0 && strlen($message) > $maxlength) {
            $message = substr($message, 0, $maxlength);
        }
        $this->message .= ($field ? "$field: " : '') . $message . "\n";
    }

    public function send() {
        if ($this->smtp) {
            $this->headers = "MIME-Version: 1.0" . "\r\n";
            $this->headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $this->headers .= 'From: ' . $this->from_name . ' <' . $this->from_email . '>' . "\r\n";

            return mail($this->to, $this->subject, $this->message, $this->headers);
        } else {
            return mail($this->to, $this->subject, $this->message, 'From: ' . $this->from_name . ' <' . $this->from_email . '>');
        }
    }
}

?>
