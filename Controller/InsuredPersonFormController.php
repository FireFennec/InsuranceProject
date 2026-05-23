<?php

namespace Controller;

use DateTime;
use Exceptions\InsurenceException;
use Model\InsuredPersonAdministration;
use Model\InsuredPersonForm;
use Model\MessageEnum;

# Controller for adding or editing a single insured person.
class InsuredPersonFormController extends Controller
{
    public function process(array $parameters): void
    {
        $insuredPersonAdministration = new InsuredPersonAdministration();

        $idInsuredPerson = null;
        $insuredPersonDetail = [];
        $editMode = isset($_GET['edit']);

        if ($editMode) {
            $idInsuredPerson = $_GET['edit'] ? (int)$_GET['edit'] : null;
            $insuredPersonDetail = $insuredPersonAdministration->getInsuredPersonDetail($idInsuredPerson);

            if (!$insuredPersonDetail) {
                $this->redirect('insuredPerson');
            }
        }

        if ($_POST) {
            try {
                $insuranceForm = new InsuredPersonForm(
                    $_POST['name'] ?? '',
                    $_POST['surname'] ?? '',
                    new DateTime($_POST['birthdate'] ?? ''),
                    $_POST['phone'] ?? '',
                    $_POST['email'] ?? '',
                    $_POST['address'] ?? '',
                    $_POST['city'] ?? '',
                    $_POST['zipCode'] ?? '',
                );

                $insuranceForm->allFilled();
                $insuranceForm->birthdateIsCorrect();

                if ($editMode) {
                    $insuranceForm->editInsuredPerson($idInsuredPerson);
                    $this->addMessage('Pojištěnec byl úspěšně upraven.', MessageEnum::SUCCESS);
                } else {
                    $insuranceForm->addInsuredPerson();
                    $this->addMessage('Pojištěnec byl úspěšně přidán.', MessageEnum::SUCCESS);
                }

                $this->redirect('insuredPersonDetail/' . $idInsuredPerson);
            } catch (InsurenceException $e) {
                $this->addMessage($e->getMessage(), MessageEnum::DANGER);
            }
        }

        $this->header = [
            'title' => $editMode ? 'Editace pojištěnce' : 'Přidání pojištěnce',
            'keywords' => 'pojištěnec, přidání pojištěnce, úprava pojištěnce',
            'description' => 'Přidání nového pojištěnce či úprava pojištěnce v databázi.'
        ];

        $this->data['insuredPerson'] = $insuredPersonDetail;
        $this->data['editMode'] = $editMode;
        $this->data['messages'] = $this->getMessages();

        $this->view = 'insuredPersonForm';
    }
}