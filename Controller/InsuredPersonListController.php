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

        $this->header = [
            'title' => 'Pojištěnci',
            'keywords' => 'pojištěnci, evidence',
            'description' => 'Seznam pojištěnců a informace o jejich evidenci'
        ];

        $page = empty($parameters[0]) ? 1 : (int)$parameters[0];

        if ($page < 1) {
            $page = 1;
        }

        $limit = 3;
        $offset = ($page - 1) * $limit;

        $totalCount = $insuredPersonAdministration->getInsuredPersonCount();
        $totalPages = (int)ceil($totalCount / $limit);

        $insuredPerson = $insuredPersonAdministration->getListOfInsuredPersons($limit, $offset);

        $this->data['insuredPersonList'] = $insuredPerson;
        $this->data['messages'] = $this->getMessages();
        $this->data['page'] = $page;
        $this->data['totalPages'] = $totalPages;

        $this->view = 'insuredPersonList';
    }
}