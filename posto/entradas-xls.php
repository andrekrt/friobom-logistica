<?php

session_start();
require("../conexao.php");
$filial = $_SESSION['filial'];
if($filial===99){
    $condicao = " ";
}else{
    $condicao = "AND combustivel_entrada.filial=$filial";
}
$db->exec("set names utf8");
$sql = $db->query("SELECT * FROM combustivel_entrada LEFT JOIN usuarios ON combustivel_entrada.usuario = usuarios.idusuarios LEFT JOIN fornecedores ON combustivel_entrada.fornecedor = fornecedores.id WHERE $condicao");
$dados = $sql->fetchAll();

$fp = fopen("entradas.csv", "w");
$escreve = fwrite($fp, "
                ID; Data Entrada; NF; Valor por Litro;Litros;".  mb_convert_encoding('Valor Combustível','ISO-8859-1', 'UTF-8') ."; Frete; Valor Total; Fornecedor; Qualidade; ". mb_convert_encoding('Usuário que Lançou','ISO-8859-1', 'UTF-8') );

foreach($dados as $dado){
    $valorComb = $dado['total_litros']*$dado['valor_litro'];
    $escreve=fwrite($fp,
        "\n$dado[idcombustivel_entrada];". date("d/m/Y", strtotime($dado['data_entrada'])). ";". "$dado[nf];". number_format($dado['valor_litro'],4,",","."). ";". number_format($dado['total_litros'],2,",",".") .";". number_format($valorComb,2,",",".").";" . number_format($dado['frete'],2,",","."). ";". number_format($dado['valor_total'],2,",",".") .";" . mb_convert_encoding($dado['nome_fantasia'],'ISO-8859-1', 'UTF-8').";". mb_convert_encoding($dado['qualidade'],'ISO-8859-1', 'UTF-8').";". mb_convert_encoding($dado['nome_usuario'],'ISO-8859-1', 'UTF-8')
    );
}

fclose($fp);

header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=entradas.csv");
header("Content-Type: application/zip");
header("Content-Transfer-Encoding: binary");

// Read the file
readfile('entradas.csv');
exit;

