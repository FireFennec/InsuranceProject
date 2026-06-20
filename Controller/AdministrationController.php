<?php

namespace Controller;

use Model\UserAdministration;

class AdministrationController extends Controller
{
    public function process(array $parameters): void
    {
        $user = $this->getUser();
        if (!$user) {
            $this->redirect('login');
        }

        if (!empty($parameters[0]) && $parameters[0] == 'logout') {
            $userAdministration = new UserAdministration();
            $userAdministration->logout();
            $this->redirect('login');
        }

        $this->header = [
            'title' => 'Administrace',
            'keywords' => 'administrace, účet',
            'description' => 'Administrace uživatele.'
        ];
        $this->data['messages'] = $this->getMessages();
        $this->data['name'] = $user['name'];
        $this->data['admin'] = $user['admin'];
        $this->view = 'administration';
    }
}