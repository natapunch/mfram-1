<?php

namespace Mindk\Framework\Renderer;

class Renderer
{
    /**
     * @param string $path_to_view
     * @param array $params
     * @return string
     */
    public static function render(string $path_to_view, array $params = []): string
    {
        ob_start();
        extract($params);
        include $path_to_view;

        return ob_get_clean();
    }
}