<?php

namespace Mindk\Framework\Controller;

use Mindk\Framework\Renderer\Renderer;
use Mindk\Framework\Response\Response;

class Controller
{
    public function render(string $view_path, array $params = [], bool $with_layout = true): Response
    {
        $content = Renderer::render($view_path, $params);

        //decided that layout.html.php placed in the same dir as view
        if ($with_layout) {
            $content = Renderer::render(
                pathinfo($view_path)['dirname'] . DIRECTORY_SEPARATOR . 'layout.html.php',
                ['content' => $content]);
        }

        return new Response($content);
    }
}