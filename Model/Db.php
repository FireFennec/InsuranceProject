<?php

namespace Model;

use PDO;

class Db
{
    private static $connection;
    private static array $settings = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    public static function connect(string $host, string $user, string $password, string $database): void
    {
        if (!isset(self::$connection)) {
            self::$connection = @new PDO(
                "mysql:host=$host;dbname=$database",
                $user,
                $password,
                self::$settings
            );
        }
    }

    public static function findOne(string $query, array $params = []): array|bool
    {
        $return = self::$connection->prepare($query);
        $return->execute($params);
        return $return->fetch();
    }

    public static function findAll(string $query, array $params = []): array
    {
        $return = self::$connection->prepare($query);
        $return->execute($params);
        return $return->fetchAll();
    }

    public static function query(string $query, array $params = []): bool
    {
        $return = self::$connection->prepare($query);
        return $return->execute($params);
    }

    public static function insert(string $table, array $parameters = []): bool
    {
        return self::query(
            "INSERT INTO `$table` (`" .
            implode('`, `', array_keys($parameters)) .
            "`) VALUES (" .
            str_repeat('?,', count($parameters) - 1) .
            "?)",
            array_values($parameters)
        );
    }
}