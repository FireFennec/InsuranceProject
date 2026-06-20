<?php

namespace Controller;

use Exceptions\UserException;
use Model\MessageEnum;
use Model\UserAdministration;

class LoginController extends Controller
{
    public function process(array $parameters): void
    {
        $userAdministration = new UserAdministration();
        if ($userAdministration->getUser()) {
            $this->redirect('insuredPersonList');
        }

        if ($_POST) {
            try {
                $userAdministration->login($_POST['name'], $_POST['password']);
                $this->addMessage('Byl jste úspěšně přihlášen.', MessageEnum::SUCCESS);
                $this->redirect('insuredPersonList');
            } catch (UserException $error) {
                $this->addMessage($error->getMessage(), MessageEnum::DANGER);
            }
        }

        $this->header = [
            'title' => 'Přihlášení',
            'keywords' => 'přihlášení, účet',
            'description' => 'Přihlášení uživatele do systému.'
        ];
        $this->data['messages'] = $this->getMessages();
        $this->view = 'login';
    }
}