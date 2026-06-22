<?php

namespace Model;

use Exceptions\UserException;
use PDOException;

class UserAdministration
{
    public function getImprint(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    public function register(string $name, string $password, string $passwordRepeat, string $year): void
    {
        if ($year != date('Y')) {
            throw new UserException('Chybně vyplněný antispam.');
        } elseif ($password != $passwordRepeat) {
            throw new UserException('Hesla se neshodují');
        } elseif (strlen($name) < 4) {
            throw new UserException('Jméno musí být alespoň 4 znaky dlouhé');
        } elseif (strlen($password) < 8) {
            throw new UserException('Hesla musí být alespoň 8 znaků dlouhé');
        } elseif ((!preg_match('/[a-z]/', $password) || (!preg_match('/[A-Z]/', $password)))) {
            throw new UserException('Heslo musí obsahovat alespoň jedno malé písmeno a jedno velké písmeno.');
        } else if (strlen($name) > 30) {
            throw new UserException('Jméno je příliš dlouhé.');
        } else if (strlen($password) > 50) {
            throw new UserException('Heslo je už příliš dlouhé.');
        }
        $user = [
            'name' => $name,
            'password_hash' => $this->getImprint($password),
            'admin' => 0,
        ];
        try {
            Db::insert('users', $user);
        } catch (PDOException $error) {
            throw new UserException('Uživatel s tímto jménem je již zaregistrovaný.');
        }
    }

    public function login(string $name, string $password): void
    {
        $user = Db::findOne('
            SELECT id, name, admin, password_hash
            FROM users
            WHERE name = ?
            ', [$name]);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            throw new UserException('Neplatné jméno nebo heslo.');
        }
        $_SESSION['user'] = $user;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
    }

    public function getUser(): ?array
    {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        return null;
    }
}