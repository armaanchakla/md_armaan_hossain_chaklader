<?php

class App {

    /**
     * @var string
     */
    protected $controller = 'forbidden';

    /**
     * @var string
     */
    protected $method = 'index';

    /**
     * @var array
     */
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // checking if the controller exists
        if (file_exists('./app/controllers/' . $url[0] . '.php')) {
            $this->controller = $url[0];
            unset($url[0]);
        }

        require_once './app/controllers/' . $this->controller . '.php';

        $this->controller = new $this->controller;

        // checking method
        if (isset($url[1])) {
            switch ($url[1]) {
            case "1":
                $url[1] = 'solution_one';
                break;
            case "2":
                $url[1] = 'solution_two';
                break;
            case "3":
                $url[1] = 'solution_three';
                break;
            default:
                $url[1] = 'index';
            }

            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : $this->params;

        // check parameters
        if (!empty($this->params)) {
            $this->controller = 'forbidden';
            require_once './app/controllers/' . $this->controller . '.php';
            $this->controller = new $this->controller;
            $this->method     = 'index';
            call_user_func_array([$this->controller, $this->method], []);
        }

        call_user_func_array([$this->controller, $this->method], []);
    }

    /**
     * @return mixed
     */
    public function parseUrl() {
        if (isset($_GET['url'])) {
            $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));

            return $url;
        }
    }
}
