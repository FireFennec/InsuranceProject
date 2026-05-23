<?php

namespace Controller;

use DateTime;
use Exceptions\InsurenceException;
use Model\InsuredPersonAdministration;
use Model\InsuranceAdministration;
use Model\InsurenceForm;
use Model\InsurenceTypeEnum;
use Model\MessageEnum;

# Controller for adding or editing a single insurance.
class InsuranceFormController extends Controller
{
    public function process(array $parameters): void
    {
        $insurenceAdministration = new InsuranceAdministration();
        $insuredPersonAdministration = new InsuredPersonAdministration();

        if (!isset($_GET['idInsuredPerson']) || !is_numeric($_GET['idInsuredPerson'])) {
            $this->redirect('insuredPersonList');
        }

        $idInsuredPerson = (int)$_GET['idInsuredPerson'];
        $insuredPerson = $insuredPersonAdministration->getInsuredPersonDetail($idInsuredPerson);

        $idInsurence = null;
        $insurance = [];
        $editMode = isset($_GET['edit']);

        if ($editMode) {
            $idInsurence = (int)$_GET['edit'];
            $insurance = $insurenceAdministration->getInsurence($idInsurence);
        }

        if ($_POST) {
            try {
                $insuranceForm = new InsurenceForm(
                    $idInsuredPerson,
                    $_POST['kindOfInsurance'],
                    (int)$_POST['sum'],
                    $_POST['subjectOfInsurance'],
                    new DateTime($_POST['validFrom']),
                    new DateTime($_POST['validUntil'])
                );

                $insuranceForm->allFilled();

                if ($editMode) {
                    $insurenceAdministration->editInsurence($idInsurence, $insuranceForm);
                    $this->addMessage('Pojištění bylo úspěšně upraveno.', MessageEnum::SUCCESS);
                } else {
                    $insuranceForm->addInsurence();
                    $this->addMessage('Pojištění bylo úspěšně přidáno.', MessageEnum::SUCCESS);
                }

                $this->redirect('insuredPersonDetail/' . $idInsuredPerson);

            } catch (InsurenceException $e) {
                $this->addMessage($e->getMessage(), MessageEnum::DANGER);
            }
        }

        $this->header = [
            'title' => $editMode ? 'Editovat pojištění' : 'Přidat pojištění',
            'keywords' => 'pojištění, nové pojištění',
            'description' => 'Přidání nového či úprava pojištění do databáze'
        ];

        $this->data['messages'] = $this->getMessages();
        $this->data['insurance'] = $insurance;
        $this->data['insuredPerson'] = $insuredPerson;
        $this->data['editMode'] = $editMode;
        $this->data['insurenceTypes'] = InsurenceTypeEnum::cases();

        $this->view = 'insuranceForm';
    }
}