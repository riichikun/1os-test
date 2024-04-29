<?php

/**
 * Оптимизировать запросы в базу данных. Работу функций оптимизировать не нужно, только оптимизация работы с базой данных. 
 * Акцент стоит ставить на скорость работы и количество запросов. В идеале вся работа должна быть выполнена за 2 sql запроса.
 */

/**
 * Some fancy function that multyplies number to a random float number and returns a rounded value;
 */
function fancyFunction1($value)
{
    return round($value * (mt_rand() / mt_getrandmax()));
}

/**
 * Some fancy function that gets a random part of a string and returns it;
 */
function fancyFunction2($value)
{
    return substr($value, rand(0, strlen($value) - 1), rand(1, strlen($value)));
}

/**
 * Config array to establish a connection with a database.
 */
$dbConfig = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'dbname' => 'exam'
];

/**
 * Establishing a database connection.
 */
$connection = mysqli_connect($dbConfig['host'], $dbConfig['user'], $dbConfig['password'], $dbConfig['dbname']);

/**
 * List of users to update
 */
$users = $connection->query("SELECT * FROM users")->fetch_all(MYSQLI_ASSOC);

$connection->query("START TRANSACTION");
$query = [];
foreach ($users as $user) {
    $newParam1 = fancyFunction1($user['param1']);
    $newParam2 = fancyFunction2($user['param2']);
    
    $query[] = '(' . $user['id'] . ', ' . $newParam1 . ', ' . $newParam2 . ')';
    
}

$query = implode(', ', $query);

$connection->query("CREATE TEMPORARY TABLE temp_users (id int(11), param1 int(11) NOT NULL DEFAULT 0, param2 text DEFAULT '``')");
$connection->query("INSERT INTO temp_users (id, param1, param2) VALUES " . $query);
$connection->query("UPDATE users JOIN temp_users ON users.id = temp_users.id set users.param1 = temp_users.param1, users.param2 = temp_users.param2");
$connection->query("COMMIT");




exit(0);