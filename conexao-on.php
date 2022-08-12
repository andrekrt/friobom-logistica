<?php

$dsn = "mysql:dbname=friobo99_despesas;host=108.179.253.230";
$dbuser = "friobo99_admRoot";
$dbpass = "admfriobom2020";

    try {
        $db = new PDO($dsn, $dbuser, $dbpass);
    } catch (PDOException $e) {
        echo "Falhou: " . $e->getMessage();
    }

//conexao com banco oracel 



?>