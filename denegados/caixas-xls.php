<?php

session_start();
require("../conexao.php");

$idModudulo = 8;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $filial=$_SESSION['filial'];
    if($filial===99){
        $condicao = " ";
    }else{
        $condicao = " AND (auxiliares_rota.filial=$filial)";
    }

    $db->exec("set names utf8");
    $sql = $db->query("SELECT idcaixas, carregamento, qtd_caixas, situacao, nome_usuario FROM caixas LEFT JOIN usuarios ON caixas.usuario = usuarios.idusuarios WHERE 1 $condicao");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=caixas.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        mb_convert_encoding('Nº','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Carga','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Qtd de Caixas','ISO-8859-1', 'UTF-8'),
        "Status",
        mb_convert_encoding('Usuário','ISO-8859-1', 'UTF-8')
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding($dado,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


