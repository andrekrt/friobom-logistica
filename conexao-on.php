<?php

$dsn = "mysql:dbname=friobo99_despesas;host=108.179.253.230";
$dbuser = "friobo99_admRoot";
$dbpass = "admfriobom2020";
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
);

    try {
        $db = new PDO($dsn, $dbuser, $dbpass,$options);
    } catch (PDOException $e) {
        echo "Falhou: " . $e->getMessage();
    }

//conexao com banco oracel 



?>