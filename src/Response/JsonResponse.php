<?php
/**
 * Created by PhpStorm.
 * User: dimmask
 * Date: 01.03.17
 * Time: 20:36
 */

namespace Mindk\Framework\Response;


class JsonResponse extends Response
{
    public function __construct($content, $code = 200)
    {
        parent::__construct($content, $code);

        $this->addHeader('Content-Type','application/json');
    }


    public function sendContent(){
        echo json_encode($this->body);
    }
}