<?php

namespace Model;

class InsuranceAdministration
{
    public function getListOfInsuredPersonInsurances(int $idIsuredPerson, int $limit, int $offset): array|bool
    {
        return Db::findAll(
            'SELECT * 
            FROM insurances 
            WHERE id_insured_person = ? 
            ORDER BY id 
            DESC 
            LIMIT ? 
            OFFSET ?',
            [$idIsuredPerson, $limit, $offset]
        );
    }

    public function getInsuranceDetail(int $id): array|bool
    {
        return  Db::findOne('SELECT * FROM insurances WHERE id = ?', [$id]);
    }

    public function editInsurence(int $idInsurence, InsurenceForm $insuranceForm): void
    {
        Db::query(
            'UPDATE insurances 
            SET id_insured_person = ?, 
             kind_of_insurance = ?, 
             sum = ?, 
             subject_of_insurance = ?, 
             valid_from = ?, 
             valid_until = ?
            WHERE id = ?',
            [
                $insuranceForm->idInsuredPerson,
                $insuranceForm->kindOfInsurance,
                $insuranceForm->sum,
                $insuranceForm->subjectOfInsurance,
                $insuranceForm->validFrom->format('Y-m-d'),
                $insuranceForm->validUntil->format('Y-m-d'),
                $idInsurence
            ]
        );
    }

    public function getInsurence(int $id): array|bool
    {
        return Db::findOne(
            'SELECT * FROM insurences WHERE id = ?',
            [$id]
        );
    }

    public function deleteInsurence(int $id): void
    {
        Db::query('DELETE FROM insurences WHERE id = ?', [$id]);
    }

    public function getInsuranceCountByInsuredPerson(int $idInsuredPerson): int
    {
        return Db::findOne(
            'SELECT COUNT(*) AS count 
         FROM insurances 
         WHERE id_insured_person = ?',
            [$idInsuredPerson]
        )['count'];
    }
}