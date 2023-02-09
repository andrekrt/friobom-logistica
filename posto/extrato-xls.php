<?php

session_start();
require("../conexao.php");

$db->exec("set names utf8");
$sql = $db->query("SELECT * FROM combustivel_extrato LEFT JOIN usuarios ON combustivel_extrato.usuario = usuarios.idusuarios");
$dados = $sql->fetchAll();

$fp = fopen("extrato.csv", "w");
$escreve = fwrite($fp, "ID; ".utf8_decode('Data Operação')." ; Volume(lt);Carregamento; Placa/Fornecedor; ". utf8_decode('Usuário que Lançou') );

foreach($dados as $dado){
    $escreve=fwrite($fp,
        "\n$dado[idextrato];". date("d/m/Y", strtotime($dado['data_operacao'])). ";".utf8_decode($dado['tipo_operacao']). ";". number_format($dado['volume'],2,",",".") .";". $dado['carregamento'] .";" .utf8_decode($dado['placa']).";".utf8_decode($dado['nome_usuario'])
    );
}

fclose($fp);

header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=abastecimento.csv");
header("Content-Type: application/zip");
header("Content-Transfer-Encoding: binary");

// Read the file
readfile('extrato.csv');
exit;

