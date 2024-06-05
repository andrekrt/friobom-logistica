<?php

session_start();
require("../conexao.php");

$filial = $_SESSION['filial'];
if($filial===99){
    $condicao = " ";
}else{
    $condicao = "AND combustivel_extrato.filial=$filial";
}

$db->exec("set names utf8");
$sql = $db->query("SELECT * FROM combustivel_extrato LEFT JOIN usuarios ON combustivel_extrato.usuario = usuarios.idusuarios WHERE $condicao");
$dados = $sql->fetchAll();

$fp = fopen("extrato.csv", "w");
$escreve = fwrite($fp, "Filial; ID; ".mb_convert_encoding('Data Operação','ISO-8859-1', 'UTF-8')." ;" .mb_convert_encoding("Tipo Operação",'ISO-8859-1', 'UTF-8')."; Volume(lt);Carregamento; Placa/Fornecedor; ". mb_convert_encoding('Usuário que Lançou','ISO-8859-1', 'UTF-8') );

foreach($dados as $dado){
    $escreve=fwrite($fp,
        "\n $dado[filial]; $dado[idextrato];". date("d/m/Y", strtotime($dado['data_operacao'])). ";".mb_convert_encoding($dado['tipo_operacao'],'ISO-8859-1', 'UTF-8'). ";". number_format($dado['volume'],2,",",".") .";". $dado['carregamento'] .";" .mb_convert_encoding($dado['placa'],'ISO-8859-1', 'UTF-8').";".mb_convert_encoding($dado['nome_usuario'],'ISO-8859-1', 'UTF-8')
    );
}

fclose($fp);

header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=extrato.csv");
header("Content-Type: application/zip");
header("Content-Transfer-Encoding: binary");

// Read the file
readfile('extrato.csv');
exit;

