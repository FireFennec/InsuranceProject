<?php

namespace Controller;

use Model\InsuredPersonAdministration;
use Model\InsuranceAdministration;

class InsuranceDetailsController extends Controller
{
    public function process(array $parameters): void
    {
        $insuredPersonAdministration = new InsuredPersonAdministration();
        $insuranceAdministration = new InsuranceAdministration();

        $this->header = [
            'title' => 'Pojištění',
            'keywords' => 'pojištění, údaje',
            'description' => 'Detaily vybraného pojištění.'
        ];

        $idInsurance = empty($parameters[0]) ? null : (int)$parameters[0];

        $insurance = $insuranceAdministration->getInsuranceDetail($idInsurance);
        $insuredPerson = $insuredPersonAdministration->getInsuredPersonDetail($insurance['id_insured_person']);

        $this->data['insurance'] = $insurance;
        $this->data['insuredPerson'] = $insuredPerson;
        $this->data['messages'] = $this->getMessages();

        $this->view = 'insuranceDetails';
    }
}