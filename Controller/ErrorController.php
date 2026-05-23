<?php

namespace Controller;

class ErrorController extends Controller
{
    public function process(array $paramets): void
    {
        header("HTTP/1.0 404 Not Found");
        $this->header = [
            'title' => 'Chyba 404',
            'keywords' => '',
            'description' => 'Chyba o neexistující stránce.'
        ];
        $this->view = 'error';
    }
}