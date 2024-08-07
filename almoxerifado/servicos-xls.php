<?php

session_start();
require("../conexao.php");

$idModudulo = 11;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $filial = $_SESSION['filial'];
    if($filial===99){
        $condicao = " ";
    }else{
        $condicao = " WHERE saida_estoque.filial=$filial";
    }

    $db->exec("set names utf8");
    $sql = $db->query("SELECT saida_estoque.filial,idservicos, descricao, categoria, nome_usuario FROM servicos_almoxarifado LEFT JOIN usuarios ON servicos_almoxarifado.usuario = usuarios.idusuarios $condicao");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=servicos.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "Filial",
        "ID",
        mb_convert_encoding('Descrição','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Categoria','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Lançado','ISO-8859-1', 'UTF-8'),
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        
        fputcsv($arquivo, mb_convert_encoding($dado, 'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


