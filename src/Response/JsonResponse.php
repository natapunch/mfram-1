<?php

namespace Mindk\Framework\Response;

/**
 * Class JsonResponse
 * @package Mindk\Framework\Response
 */
class JsonResponse extends Response
{
    /**
     * JsonResponse constructor.
     * @param $content
     * @param int $code
     */
    public function __construct($content, $code = 200)
    {
        parent::__construct($content, $code);
        $this->addHeader('Content-Type','application/json');
    }

    /**
     * Send content to the client
     */
    public function sendBody(){
        echo json_encode($this->payload);
    }
}