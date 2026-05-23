<?php

namespace Controller;

use Model\InsuredPersonsAdministration;
use Model\MessageEnum;

# Controller for displaying a list of insured persons.
class InsuredPersonsController extends Controller
{
    public function process(array $parameters): void
    {
        $insuredPersonsAdministration = new InsuredPersonsAdministration();

        if (isset($_POST['delete'])) {
            $insuredPersonsAdministration->deleteInsuredPerson((int)$_POST['delete']);
            $this->addMessage('Pojištěnec byl úspěšně smazán.', MessageEnum::SUCCESS);
            $this->redirect('insuredPersons');
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

        $totalCount = $insuredPersonsAdministration->getInsuredPersonsCount();
        $totalPages = (int)ceil($totalCount / $limit);

        $insuredPersons = $insuredPersonsAdministration->getListOfInsuredPersons($limit, $offset);

        $this->data['insuredPersons'] = $insuredPersons;
        $this->data['messages'] = $this->getMessages();
        $this->data['page'] = $page;
        $this->data['totalPages'] = $totalPages;

        $this->view = 'insuredPersons';
    }
}