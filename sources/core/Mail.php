<?php 

class Mail 
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
    
    public function send($toEmail)
    {

    }
}