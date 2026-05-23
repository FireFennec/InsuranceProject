<?php

namespace Model;

use DateTime;
use Exceptions\InsurenceException;

class InsurencePersonForm
{
    public ?string $name;
    public ?string $surname;
    public ?DateTime $birthdate;
    public ?string $phone;
    public ?string $email;
    public ?string $address;
    public ?string $city;
    public ?string $zipCode;

    public function __construct(
        ?string $name,
        ?string $surname,
        ?DateTime $birthdate,
        ?string $phone,
        ?string $email,
        ?string $address,
        ?string $city,
        ?string $zipCode
    )
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->birthdate = $birthdate;
        $this->phone = $phone;
        $this->email = $email;
        $this->address = $address;
        $this->city = $city;
        $this->zipCode = $zipCode;
    }

    public function allFilled(): void
    {
        if (empty($this->name)) {
            throw new InsurenceException('Musíte vyplnit jméno pro přidání pojištěného.');
        } elseif (empty($this->surname)) {
            throw new InsurenceException('Musíte vyplnit příjmení pro přidání pojištěného.');
        } elseif (empty($this->birthdate)) {
            throw new InsurenceException('Musíte vyplnit datum narození.');
        } elseif (empty($this->phone)) {
            throw new InsurenceException('Musíte vyplnit telefoní číslo pojištěného pro přidání.');
        } elseif (empty($this->email)) {
            throw new InsurenceException('Musíte vyplnit email pro přidání pojištěného.');
        } elseif (empty($this->address)) {
            throw new InsurenceException('Musíte vyplnit adresu pro přidání pojištěného.');
        } elseif (empty($this->city)) {
            throw new InsurenceException('Musíte vyplnit město pro přidání pojištěného.');
        } elseif (empty($this->zipCode)) {
            throw new InsurenceException('Musíte vyplnit PSČ pro přidání pojištěného.');
        }
    }

    public function birthdateIsCorrect(): void
    {
        $now = new DateTime();

        if ($this->birthdate > $now) {
            throw new InsurenceException(
                'Datum narození nemůže být v budoucnosti.'
            );
        }
    }

    public function addInsuredPerson(): void
    {
        Db::insert('insured_persons', [
            'name' => $this->name,
            'surname' => $this->surname,
            'birth' => $this->birthdate->format('Y-m-d'),
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'city' => $this->city,
            'zip_code' => $this->zipCode,
        ]);
    }

    public function editInsuredPerson(int $id): void
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
                $this->name,
                $this->surname,
                $this->birthdate->format('Y-m-d'),
                $this->phone,
                $this->email,
                $this->address,
                $this->city,
                $this->zipCode,
                $id
            ]
        );
    }
}