<?php

namespace Controller;

use DateTime;
use Exceptions\InsurenceException;
use Model\InsuredPersonAdministration;
use Model\InsuranceAdministration;
use Model\InsurenceForm;
use Model\InsurenceTypeEnum;
use Model\MessageEnum;
use Model\UserAdministration;

# Controller for adding or editing a single insurance.
class InsuranceFormController extends Controller
{
    public function process(array $parameters): void
    {
        $user = $this->getUser();
        if (!$user) {
            $this->redirect('insuredPersonList');
        }
        if (!$user['admin']) {
            $this->redirect('insuredPersonList');
        }

        if (!isset($_GET['idInsuredPerson']) || !is_numeric($_GET['idInsuredPerson'])) {
            $this->redirect('insuredPersonList');
        }

        $insuranceAdministration = new InsuranceAdministration();
        $insuredPersonAdministration = new InsuredPersonAdministration();

        $idInsuredPerson = (int)$_GET['idInsuredPerson'];
        $insuredPerson = $insuredPersonAdministration->getInsuredPersonDetail($idInsuredPerson);

        $idInsurance = null;
        $insurance = [];
        $editMode = isset($_GET['edit']);

        if ($editMode) {
            $idInsurance = (int)$_GET['edit'];
            $insurance = $insuranceAdministration->getInsurence($idInsurance);
        }

        if ($_POST) {

            try {
                $validFrom = !empty($_POST['validFrom'])
                    ? new DateTime($_POST['validFrom'])
                    : null;

                $validUntil = !empty($_POST['validUntil'])
                    ? new DateTime($_POST['validUntil'])
                    : null;
                $insuranceForm = new InsurenceForm(
                    $idInsuredPerson,
                    $_POST['kindOfInsurance'] ?? '',
                    (int)$_POST['sum'] ?? '',
                    $_POST['subjectOfInsurance'] ?? '',
                    $validFrom,
                    $validUntil
                );

                $insuranceForm->allFilled();

                if ($editMode) {
                    $insuranceAdministration->editInsurence($idInsurance, $insuranceForm);
                    $this->addMessage('Pojištění bylo úspěšně upraveno.', MessageEnum::SUCCESS);
                } else {
                    $insuranceForm->addInsurance();
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