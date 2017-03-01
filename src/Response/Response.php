<?php

namespace Mindk\Framework\Response;

/**
 * Class Response
 * @package Mindk\Framework\Response
 */
class Response
{
    public $code = 200;

    const STATUS_MSGS = [
        '200' => 'Ok',
        '404' => 'Not found',
        '500' => 'Server error'
    ];

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var string
     */
    protected $body = '';

    /**
     * Response constructor.
     */
    public function __construct($content, $code = 200)
    {
        $this->setContent($content);
        $this->code = $code;
        $this->addHeader('Content-Type','text/html');
    }

    /**
     * @param $key
     * @param $value
     */
    public function addHeader($key, $value){
       $this->headers[$key] = $value;
    }

    /**
     * @param $content
     */
    public function setContent($content){
        $this->body = $content;
    }

    /**
     * Send response
     */
    public function send(){
        $this->sendHeaders();
        $this->sendContent();
    }

    public function sendHeaders(){
        header($_SERVER['SERVER_PROTOCOL'] . " " . $this->code . " " . self::STATUS_MSGS[$this->code]);
        if(!empty($this->headers)){
           foreach($this->headers as $key => $value){
               header($key.": ". $value);
           }
       }
    }

    public function sendContent(){
        echo "Hi!";
    }
}