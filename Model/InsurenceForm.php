<?php

namespace Model;

use DateTime;
use Exceptions\InsurenceException;

class InsurenceForm
{
    public ?int $idInsuredPerson;
    public ?string $kindOfInsurance;
    public ?int $sum;
    public ?string $subjectOfInsurance;
    public ?DateTime $validFrom;
    public ?DateTime $validUntil;

    public function __construct(
        ?int $idInsuredPerson,
        ?string $kindOfInsurance,
        ?int $sum,
        ?string $subjectOfInsurance,
        ?DateTime $validFrom,
        ?DateTime $validUntil
    )
    {
        $this->idInsuredPerson = $idInsuredPerson;
        $this->kindOfInsurance = $kindOfInsurance;
        $this->sum = $sum;
        $this->subjectOfInsurance = $subjectOfInsurance;
        $this->validFrom = $validFrom;
        $this->validUntil = $validUntil;
    }

    public function allFilled(): void
    {
        if (empty($this->kindOfInsurance)) {
            throw new InsurenceException('Musíte vyplnit typ pojištění.');
        } elseif (empty($this->sum)) {
            throw new InsurenceException('Musíte vyplnit částku pojištění.');
        } elseif (empty($this->subjectOfInsurance)) {
            throw new InsurenceException('Musíte vyplnit předmět pojištění');
        } elseif (empty($this->validFrom)) {
            throw new InsurenceException('Musíte vyplnit od kdy pojištění bude platit.');
        } elseif (empty($this->validUntil)) {
            throw new InsurenceException('Musíte vyplnit do kdy pojištění bude platit.');
        }
    }

    public function addInsurence(): void
    {
        Db::insert('insurences', [
            'id_insured_person' => $this->idInsuredPerson,
            'kind_of_insurance' => $this->kindOfInsurance,
            'sum' => $this->sum,
            'subject_of_insurance' => $this->subjectOfInsurance,
            'valid_from' => $this->validFrom->format('Y-m-d'),
            'valid_until' => $this->validUntil->format('Y-m-d'),
        ]);
    }
}