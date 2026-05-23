<?php

namespace Controller;

use Model\InsuredPersonsAdministration;
use Model\InsuranceAdministration;

class InsuranceDetailsController extends Controller
{
    public function process(array $paramets): void
    {
        $insuredPersonsAdministration = new InsuredPersonsAdministration();
        $insuranceAdministration = new InsuranceAdministration();

        $this->header = [
            'title' => 'Pojištění',
            'keywords' => 'pojištění, údaje',
            'description' => 'Detaily vybraného pojištění.'
        ];

        $idInsurance = empty($paramets[0]) ? null : (int)$paramets[0];

        $insurance = $insuranceAdministration->getInsuranceDetail($idInsurance);
        $insuredPerson = $insuredPersonsAdministration->getInsuredPersonDetail($insurance['id_insured_person']);

        $this->data['insurance'] = $insurance;
        $this->data['insuredPerson'] = $insuredPerson;
        $this->data['messages'] = $this->getMessages();

        $this->view = 'insuranceDetails';
    }
}