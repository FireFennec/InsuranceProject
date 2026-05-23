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
            $this->redirect('insuredPerson');
        }

        $id = empty($parameters[0]) ? null : (int)$parameters[0];

        if (isset($_GET['delete'])) {
            $insuranceAdministration->deleteInsurence((int)$_GET['delete']);
            $this->addMessage('Pojištění bylo úspěšně smazáno.', MessageEnum::SUCCESS);
            $this->redirect('insuredPersonDetail/' . $id);
        }

        $insuranceList = $insuranceAdministration->getListOfInsuredPersonInsurences($id, $limit, $offset);

        $this->data['insuredPerson'] = $insuredPersonAdministration->getInsuredPersonDetail($id);
        $this->data['messages'] = $this->getMessages();
        $this->data['insuranceList'] = $insuranceList;

        $this->view = 'insuredPersonDetail';
    }
}