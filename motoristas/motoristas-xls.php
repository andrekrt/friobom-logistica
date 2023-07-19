<?php

session_start();
require("../conexao.php");

$idModudulo = 4;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $db->exec("set names utf8");
    $sql = $db->query("SELECT cod_interno_motorista, nome_motorista, cnh, validade_cnh, toxicologico, validade_toxicologico, salario FROM motoristas WHERE ativo = 1 ORDER BY nome_motorista");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=motoristas.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        mb_convert_encoding('Código Motorista','ISO-8859-1', 'UTF-8'),
        "Motorista",
        mb_convert_encoding('CNH Veículo','ISO-8859-1', 'UTF-8'),
        "Validade CNH",
        mb_convert_encoding('Toxicológico','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Validade Toxicológico','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Salário','ISO-8859-1', 'UTF-8')
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding(str_replace(".",",",$dado) ,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


