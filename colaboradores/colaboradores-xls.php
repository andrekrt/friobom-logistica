<?php

session_start();
require("../conexao.php");

$idModudulo = 5;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $db->exec("set names utf8");
    $sql = $db->query("SELECT cpf_colaborador, nome_colaborador, salario_colaborador, salario_extra, cargo_colaborador FROM colaboradores WHERE ativo = 1 ORDER BY nome_colaborador");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=colaboradores.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "CPF",
        "Nome",
        mb_convert_encoding('Salário','ISO-8859-1', 'UTF-8'),
        "Extra",
        mb_convert_encoding('Função','ISO-8859-1', 'UTF-8'),
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding(str_replace(".",",",$dado) ,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


