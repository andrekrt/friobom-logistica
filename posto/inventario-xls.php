<?php

session_start();
require("../conexao.php");

$db->exec("set names utf8");
$sql = $db->query("SELECT * FROM combustivel_inventario LEFT JOIN usuarios ON combustivel_inventario.usuario = usuarios.idusuarios");
$dados = $sql->fetchAll();

$fp = fopen("inventario.csv", "w");
$escreve = fwrite($fp, "ID;Data Inventario; Volume Anterior(Litros); Volume Inventaridado (Litros); Volume Divergente(Litros);". utf8_decode('Usuário que Lançou') );

foreach($dados as $dado){
    $escreve=fwrite($fp,
        "\n$dado[idinventario];". date("d/m/Y", strtotime($dado['data_inventario'])). ";" .number_format($dado['volume_anterior'],2,",","."). ";" .number_format($dado['qtd_encontrada'],2,",","."). ";" .number_format($dado['volume_divergente'],2,",","."). ";". utf8_decode($dado['nome_usuario'])
    );
}

fclose($fp);

header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=inventario.csv");
header("Content-Type: application/zip");
header("Content-Transfer-Encoding: binary");

// Read the file
readfile('inventario.csv');
exit;

