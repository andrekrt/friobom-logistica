<?php

session_start();
require("../conexao.php");

$filial = $_SESSION['filial'];
if($filial===99){
    $condicao = " ";
}else{
    $condicao = "AND combustivel_inventario.filial=$filial";
}

$db->exec("set names utf8");
$sql = $db->query("SELECT * FROM combustivel_inventario LEFT JOIN usuarios ON combustivel_inventario.usuario = usuarios.idusuarios WHERE filial=$filial");
$dados = $sql->fetchAll();

$fp = fopen("inventario.csv", "w");
$escreve = fwrite($fp, "Filial; ID;Data Inventario; Volume Anterior(Litros); Volume Inventaridado (Litros); Volume Divergente(Litros);". utf8_decode('Usuário que Lançou') );

foreach($dados as $dado){
    $escreve=fwrite($fp,
        "\n$dadp[filial]; $dado[idinventario];". date("d/m/Y", strtotime($dado['data_inventario'])). ";" .number_format($dado['volume_anterior'],2,",","."). ";" .number_format($dado['qtd_encontrada'],2,",","."). ";" .number_format($dado['volume_divergente'],2,",","."). ";". utf8_decode($dado['nome_usuario'])
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

