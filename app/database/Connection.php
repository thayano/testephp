<?php
namespace app\database;

use PDO;

class Connection
{
    private static $connect = null;

    public static function getConnection()
    {
        if (!self::$connect) {
            // importação das informações no arquivo .env não funcionou
            self::$connect = new PDO("mysql:host=localhost;dbname=lista_compras", 'root', 'root', [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);
        }

        return self::$connect;
    }
}