<?php

namespace Core;

class View
{
    public static function render(string $view, array $data = [], string $layout = 'layouts/main'): void
    {
        $viewPath = '../app/views/' . $view . '.php';
        $layoutPath = '../app/views/' . $layout . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(404);
            echo 'View not found.';
            return;
        }

        extract($data);
        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        if (file_exists($layoutPath)) {
            include $layoutPath;
            return;
        }

        echo $content;
    }
}
