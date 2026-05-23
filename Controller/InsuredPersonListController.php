<?php

namespace Controller;

use Model\InsuredPersonAdministration;
use Model\MessageEnum;

# Controller for displaying a list of insured persons.
class InsuredPersonListController extends Controller
{
    public function process(array $parameters): void
    {
        $insuredPersonAdministration = new InsuredPersonAdministration();

        if (isset($_POST['delete'])) {
            $insuredPersonAdministration->deleteInsuredPerson((int)$_POST['delete']);
            $this->addMessage('Pojištěnec byl úspěšně smazán.', MessageEnum::SUCCESS);
            $this->redirect('insuredPerson');
        }

        if (isset($parameters[0]) && $parameters[0]) {
            $page = (int)$parameters[0];
        } else {
            $page = 1;
        }
        if ($page < 1) {
            $page = 1;
        }
        $limit = 3;
        $offset = ($page - 1) * $limit;
        $totalCount = $insuredPersonAdministration->getInsuredPersonCount();
        $totalPages = (int)ceil($totalCount / $limit);

        $insuredPersonList = $insuredPersonAdministration->getListOfInsuredPersons($limit, $offset);

        $this->header = [
            'title' => 'Seznam pojištěnců',
            'keywords' => 'pojištěnci, evidence, informace',
            'description' => 'Seznam pojištěnců a nějaké informace k nim.'
        ];

        $this->data['insuredPersonList'] = $insuredPersonList;
        $this->data['page'] = $page;
        $this->data['totalPages'] = $totalPages;
        $this->data['messages'] = $this->getMessages();

        $this->view = 'insuredPersonList';
    }
}