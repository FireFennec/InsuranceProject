<?php

namespace Controller;

use Exceptions\UserException;
use Model\MessageEnum;
use Model\UserAdministration;

class RegistrationController extends Controller
{
    public function process(array $parameters): void
    {
        if ($_POST) {
            try {
                $userAdministration = new UserAdministration();
                $userAdministration->register($_POST['name'], $_POST['password'], $_POST['password_repeat'], $_POST['year']);
                $userAdministration->login($_POST['name'], $_POST['password']);
                $this->addMessage('Byl jste úspěšně zaregistrován.', MessageEnum::SUCCESS);
                $this->redirect('insuredPersonList');
            } catch (UserException $error) {
                $this->addMessage($error->getMessage(), MessageEnum::DANGER);
            }
        }

        $this->header = [
            'title' => 'Registrace',
            'keywords' => 'registrace, účet',
            'description' => 'Registrace uživatele do systému.'
        ];
        $this->data['messages'] = $this->getMessages();

        $this->view = 'registration';
    }
}