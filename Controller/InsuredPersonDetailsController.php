<?php

namespace Controller;

use Model\InsuredPersonsAdministration;
use Model\InsuranceAdministration;
use Model\MessageEnum;

# Controller for displaying details of a single insured person.
class InsuredPersonDetailsController extends Controller
{
    public function process(array $parameters): void
    {
        $insuredPersonsAdministration = new InsuredPersonsAdministration();
        $insuranceAdministration = new InsuranceAdministration();

        $this->header = [
            'title' => 'Pojištěnec',
            'keywords' => 'pojištěnci, evidence, údaje',
            'description' => 'Detaily vybraného pojištěnce.'
        ];

        $page = empty($parameters[1]) ? 1 : (int)$parameters[1];

        if ($page < 1) {
            $page = 1;
        }

        $limit = 3;
        $offset = ($page - 1) * $limit;

        if (empty($parameters[0])) {
            $this->redirect('insuredPersons');
        }

        $id = empty($parameters[0]) ? null : (int)$parameters[0];

        if (isset($_GET['delete'])) {
            $insuranceAdministration->deleteInsurence((int)$_GET['delete']);
            $this->addMessage('Pojištění bylo úspěšně smazáno.', MessageEnum::SUCCESS);
            $this->redirect('insuredPersonDetails/' . $id);
        }

        $insuranceList = $insuranceAdministration->getListOfInsuredPersonInsurences($id, $limit, $offset);

        $this->data['insuredPerson'] = $insuredPersonsAdministration->getInsuredPersonDetail($id);
        $this->data['messages'] = $this->getMessages();
        $this->data['insuranceList'] = $insuranceList;

        $this->view = 'insuredPersonDetails';
    }
}