<?php

namespace Controller;

use Model\InsuredPersonAdministration;
use Model\InsuranceAdministration;
use Model\MessageEnum;

# Controller for displaying details of a single insured person.
class InsuredPersonDetailController extends Controller
{
    public function process(array $parameters): void
    {
        $insuredPersonAdministration = new InsuredPersonAdministration();
        $insuranceAdministration = new InsuranceAdministration();

        if (isset($parameters[0]) && $parameters[0]) {
            $idInsuredPerson = (int)$parameters[0];
        } else {
            $idInsuredPerson = null;
        }
        if (empty($idInsuredPerson)) {
            $this->redirect('insuredPersonList');
        }

        if (isset($_GET['delete'])) {
            $insuranceAdministration->deleteInsurance((int)$_GET['delete']);
            $this->addMessage('Pojištění bylo úspěšně smazáno.', MessageEnum::SUCCESS);
            $this->redirect('insuredPersonDetail/' . $idInsuredPerson);
        }

        if (isset($parameters[1]) && $parameters[1]) {
            $page = (int)$parameters[1];
        } else {
            $page = 1;
        }
        if ($page < 1) {
            $page = 1;
        }
        $limit = 3;
        $offset = ($page - 1) * $limit;

        $totalCount = $insuranceAdministration->getInsuranceCountByInsuredPerson($idInsuredPerson);
        $totalPages = (int)ceil($totalCount / $limit);

        if (empty($parameters[0])) {
            $this->redirect('insuredPerson');
        }

        $insuranceList = $insuranceAdministration->getListOfInsuredPersonInsurances($idInsuredPerson, $limit, $offset);

        $this->header = [
            'title' => 'Dataily pojištěnce',
            'keywords' => 'pojištěnci, evidence, údaje, detaily',
            'description' => 'Detaily a možnosti úprav či mazání vybraného pojištěnce.'
        ];

        $this->data['insuredPerson'] = $insuredPersonAdministration->getInsuredPersonDetail($idInsuredPerson);
        $this->data['insuranceList'] = $insuranceList;
        $this->data['page'] = $page;
        $this->data['totalPages'] = $totalPages;
        $this->data['messages'] = $this->getMessages();

        $this->view = 'insuredPersonDetail';
    }
}