<?php

namespace Controller;

use DateTime;
use Exceptions\InsurenceException;
use Model\InsuredPersonAdministration;
use Model\InsurencePersonForm;
use Model\MessageEnum;

# Controller for adding or editing a single insured person.
class InsuredPersonFormController extends Controller
{
    public function process(array $parameters): void
    {
        $insuredPersonAdministration = new InsuredPersonAdministration();

        $id = null;
        $insuredPerson = [];
        $editMode = isset($_GET['edit']);

        if ($editMode) {
            $id = $_GET['edit'] ? (int)$_GET['edit'] : null;
            $insuredPerson = $insuredPersonAdministration->getInsuredPersonDetail($id);

            if (!$insuredPerson) {
                $this->redirect('insuredPerson');
            }
        }

        if ($_POST) {
            try {
                $insuranceForm = new InsurencePersonForm(
                    $_POST['name'],
                    $_POST['surname'],
                    new DateTime($_POST['birthdate']),
                    $_POST['phone'],
                    $_POST['email'],
                    $_POST['address'],
                    $_POST['city'],
                    $_POST['zipCode']
                );

                $insuranceForm->allFilled();
                $insuranceForm->birthdateIsCorrect();

                if ($editMode) {
                    $insuranceForm->editInsuredPerson($id);
                    $this->addMessage('Pojištěnec byl úspěšně upraven.', MessageEnum::SUCCESS);
                } else {
                    $insuranceForm->addInsuredPerson();
                    $this->addMessage('Pojištěnec byl úspěšně přidán.', MessageEnum::SUCCESS);
                }

                $this->redirect('insuredPerson');
            } catch (InsurenceException $e) {
                $this->addMessage($e->getMessage(), MessageEnum::DANGER);
            }
        }
        $this->header = [
            'title' => $editMode
                ? 'Editovat pojištěnce'
                : 'Přidat pojištěnce',
            'keywords' => 'pojištěnci, nový pojištěnec',
            'description' => 'Přidání nového či úprava pojištěnce do databáze'
        ];

        $this->data['messages'] = $this->getMessages();
        $this->data['insuredPerson'] = $insuredPerson;
        $this->data['editMode'] = $editMode;

        $this->view = 'insuredPersonForm';
    }
}