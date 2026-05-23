<?php

namespace Model;

class InsuredPersonAdministration
{
    public function getListOfInsuredPersons(int $limit, int $offset): array|bool
    {
        return Db::findAll('SELECT * FROM insured_persons ORDER BY id DESC LIMIT ? OFFSET ?', [$limit, $offset]);
    }

    public function getInsuredPersonDetail(int $id): array|bool
    {
        return  Db::findOne('SELECT * FROM insured_persons WHERE id = ?', [$id]);
    }

    public function getInsuredPersonCount(): int
    {
        $result = Db::findOne('SELECT COUNT(*) AS count FROM insured_persons');

        return (int)$result['count'];
    }

    public function deleteInsuredPerson($id):void
    {
        Db::query('DELETE FROM insured_persons WHERE id = ?', [$id]);
    }

    public function editInsuredPerson(int $id, InsuredPersonForm $insuredPersonForm): void
    {
        Db::query(
            'UPDATE insured_persons
         SET name = ?,
             surname = ?,
             birth = ?,
             phone = ?,
             email = ?,
             address = ?,
             city = ?,
             zip_code = ?
         WHERE id = ?',
            [
                $insuredPersonForm->name,
                $insuredPersonForm->surname,
                $insuredPersonForm->birthdate->format('Y-m-d'),
                $insuredPersonForm->phone,
                $insuredPersonForm->email,
                $insuredPersonForm->address,
                $insuredPersonForm->city,
                $insuredPersonForm->zipCode,
                $id
            ]
        );
    }
}