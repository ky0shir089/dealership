<?php
$host = "localhost";
$db = "dealership";
$user = "root";
$pass = "";
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try 
{
    $pdo = new PDO($dsn, $user, $pass, $options);

	$year = date('y');
	define('year',$year);

	$month = date('m');
	define('month',$month);
}
catch(PDOException $e)
{
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?> 