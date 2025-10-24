<?php

namespace Core;

class App
{
    protected $controller = 'DashboardController';
    protected $method = 'index';
    protected $params = [];

    public function run(): void
    {
        $url = $this->parseUrl();

        if (!empty($url[0])) {
            $potentialController = ucfirst($url[0]) . 'Controller';
            if (file_exists(__DIR__ . '/../app/controllers/' . $potentialController . '.php')) {
                $this->controller = $potentialController;
            }
            array_shift($url);
        }

        $controllerClass = '\\App\\Controllers\\' . $this->controller;
        $this->controller = new $controllerClass();

        if (!empty($url[0]) && method_exists($this->controller, $url[0])) {
            $this->method = $url[0];
            array_shift($url);
        }

        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl(): array
    {
        if (!isset($_GET['url'])) {
            return [];
        }

        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return explode('/', $url);
    }
}
