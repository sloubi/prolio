<?php

namespace Prolio\Model;

use PDO;

class Spdo
{
    // Singleton
    private static $spdo = null;

    // Native PDO object 
    private $pdo   = null;

    private static $dsn = null;
    private static $user = null;
    private static $pass = null;

    // Constructeur privé pour le singleton
    final private function __construct()
    {
        if (is_null(self::$dsn) || is_null(self::$user) || is_null(self::$pass))
            throw new Exception("PDO needs connexion parameters.");

        $this->pdo = new PDO(
            self::$dsn,
            self::$user,
            self::$pass,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );

        // Mise en place du mode "Exception" pour les erreurs PDO
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Récupere le singleton
    final public static function get()
    {
        // Une instance est-elle disponible ?
        if (!isset(self::$spdo))
            self::$spdo = new Spdo();

        return self::$spdo;
    }

    // Fixe les paramètres de connexion
    public static function setMysqlParams($host, $dbName, $user, $pass)
    {
        self::$dsn  = 'mysql:host=' . $host . ';dbname=' . $dbName;
        self::$user = $user;
        self::$pass = $pass;
    }

    // Interdit le clonage du singleton
    final public function __clone()
    {
        throw new Exception("Clone ".__CLASS__." forbidden!");
    }

    final public function __call($methodName, $methodArguments)
    {
        // La méthode appelée fait-elle partie de la classe PDO
        if (!method_exists($this->pdo, $methodName))
            throw new Exception("PDO::$methodName doesn't exist.");

        // Appel de la méthode avec l'objet PDO
        $result = call_user_func_array(array($this->pdo, $methodName), $methodArguments);

        // Cas 'prepare' ou 'query' => mise en place du fetchMode par objet
        if ($methodName == 'prepare' || $methodName == 'query')
        {
            $result->setFetchMode(PDO::FETCH_OBJ);
        }

        return $result;
    }
}
