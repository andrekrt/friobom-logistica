<?php

session_start();
require("../conexao.php");

$filial = $_SESSION['filial'];
if($filial===99){
    $condicao = " ";
}else{
    $condicao = "AND vales.filial=$filial";
}


$db->exec("set names utf8");
$sql = $db->query("SELECT * FROM vales LEFT JOIN motoristas ON motoristas.cod_interno_motorista=vales.motorista LEFT JOIN rotas ON rotas.cod_rota=vales.rota LEFT JOIN usuarios on usuarios.idusuarios=vales.usuario WHERE 1 $condicao");
$dados = $sql->fetchAll();

$fp = fopen("vales.csv", "w");
$escreve = fwrite($fp, "Filial;". mb_convert_encoding('Nº','ISO-8859-1', 'UTF-8'). ";Data;Motorista; Rota; Valor (R$); Tipo Vale; Carregamento; Status;". mb_convert_encoding("Usuário",'ISO-8859-1', 'UTF-8') );

foreach($dados as $dado){
    $escreve=fwrite($fp,
        "\n $dado[filial]; $dado[idvale];". date("d/m/Y", strtotime($dado['data_lancamento'])). ";" .mb_convert_encoding($dado['nome_motorista'],'ISO-8859-1', 'UTF-8'). ";" .mb_convert_encoding($dado['nome_rota'],'ISO-8859-1', 'UTF-8'). ";" .number_format($dado['valor'],2,",","."). ";$dado[tipo_vale]; $dado[carregamento];". mb_convert_encoding($dado['situacao'],'ISO-8859-1', 'UTF-8'). ";". mb_convert_encoding($dado['nome_usuario'],'ISO-8859-1', 'UTF-8'),
    );
}

fclose($fp);

header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=vales.csv");
header("Content-Type: application/zip");
header("Content-Transfer-Encoding: binary");

// Read the file
readfile('vales.csv');
exit;

