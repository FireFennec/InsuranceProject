<?php

namespace Model;

class InsuredPersonsAdministration
{
    public function getListOfInsuredPersons(int $limit, int $offset): array|bool
    {
        return Db::findAll('SELECT * FROM insured_persons ORDER BY id DESC LIMIT ? OFFSET ?', [$limit, $offset]);
    }

    public function getInsuredPersonDetail(int $id): array|bool
    {
        return  Db::findOne('SELECT * FROM insured_persons WHERE id = ?', [$id]);
    }

    public function getInsuredPersonsCount(): int
    {
        $result = Db::findOne('SELECT COUNT(*) AS count FROM insured_persons');

        return (int)$result['count'];
    }

    public function deleteInsuredPerson($id):void
    {
        Db::query('DELETE FROM insured_persons WHERE id = ?', [$id]);
    }
}