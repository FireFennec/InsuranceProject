<?php

namespace Controller;

class DirectionController extends Controller
{
    protected Controller $controller;

    public function process(array $paramets): void
    {
        $parsedUrl = $this->parseUrl($paramets[0]);

        if (empty($parsedUrl[0])) {
            $this->redirect('clanek');
        }
        $classController = 'Controller\\' . $this->toCamelCase(array_shift($parsedUrl)) . 'Controller';

        $fileController = str_replace('\\', '/', $classController) . '.php';

        if (file_exists($fileController)) {
            $this->controller = new $classController();
        } else {
            $this->redirect('error');
        }

        $this->controller->process($parsedUrl);

        $this->data['title'] = $this->controller->header['title'];
        $this->data['description'] = $this->controller->header['description'];
        $this->data['keywords'] = $this->controller->header['keywords'];

        $this->view = 'layout';
    }

    private function parseUrl(string $url): array
    {
        $parsedUrl = parse_url($url);
        $parsedUrl['path'] = ltrim($parsedUrl['path'], '/');
        $parsedUrl['path'] = trim($parsedUrl['path']);

        return explode('/', $parsedUrl['path']);
    }

    private function toCamelCase(string $text): string
    {
        $sentence = str_replace("-", " ", $text);
        $sentence = ucwords($sentence);
        $sentence = str_replace(' ', '', $sentence);

        return $sentence;
    }
}