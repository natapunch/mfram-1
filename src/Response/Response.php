<?php

namespace Mindk\Framework\Response;

/**
 * Class Response
 * @package Mindk\Framework\Response
 */
class Response
{
    /**
     * @var int Response code
     */
    public $code = 200;

    /**
     * HTTP Status messages
     */
    const STATUS_MSGS = [
        '200' => 'Ok',
        '301' => 'Moved permanently',
        '302' => 'Moved temporary',
        '401' => 'Auth required',
        '403' => 'Access denied',
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
    protected $payload = '';

    /**
     * Response constructor.
     */
    public function __construct($content, $code = 200)
    {
        $this->setPayload($content);
        $this->code = $code;
        $this->addHeader('Content-Type','text/html');
    }

    /**
     * Add header
     *
     * @param $key
     * @param $value
     */
    public function addHeader($key, $value){
       $this->headers[$key] = $value;
    }

    /**
     * @param $content
     */
    public function setPayload($content){
        $this->payload = $content;
    }

    /**
     * Send response
     */
    public function send(){

        $this->sendHeaders();
        $this->sendBody();
        exit();
    }

    /**
     * Send headers
     */
    public function sendHeaders(){

        header($_SERVER['SERVER_PROTOCOL'] . " " . $this->code . " " . self::STATUS_MSGS[$this->code]);
        if(!empty($this->headers)){
           foreach($this->headers as $key => $value){
               header($key.": ". $value);
           }
       }
    }

    /**
     * Send response playload
     */
    public function sendBody(){
        echo $this->payload;
    }
}