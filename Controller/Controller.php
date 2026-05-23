<?php

namespace Controller;

use Model\MessageEnum;

abstract class Controller
{
    protected array $data = [];
    protected string $view = "";
    protected array $header = [
        'title' => '',
        'keyWords' => '',
        'description' => '',
    ];

    abstract function process(array $parameters): void;

    public function writeView(): void
    {
        if ($this->view) {
            extract($this->data);
            require("view/" . $this->view . ".phtml");
        }
    }

    public function redirect(string $url): void
    {
        header("Location: /$url");
        header("Connection: close");
        exit();
    }

    public function addMessage(string $message, MessageEnum $type): void
    {
        $_SESSION['messages'][] = [
            'message' => $message,
            'type' => $type->value,
        ];
    }

    public function getMessages(): array
    {
        if (isset($_SESSION['messages'])) {
            $messages = $_SESSION['messages'];
            unset($_SESSION['messages']);
            return $messages;
        } else {
            return [];
        }
    }
}