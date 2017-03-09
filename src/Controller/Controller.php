<?php

namespace Mindk\Framework\Controller;

use Mindk\Framework\Renderer\Renderer;
use Mindk\Framework\Response\Response;

class Controller
{
    public function render(string $view_path, array $params=[], bool $with_layout=true): Response
    {
        //get rendered info
        $content = Renderer::render($view_path, $params);
        //return response

        if($with_layout) {
            //let layout placed into the same dir
//            $content = Renderer::render()
        }

        return new Response($content);

    }
}