<?php

session_start();
require("../../conexao.php");

$idModudulo = 12;
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
        $condicao = "AND manutencao_pneu.filial=$filial";
    }

    $db->exec("set names utf8");
    $sql = $db->query("SELECT manutencao_pneu.filial,data_manutencao, tipo_manutencao, num_fogo, medida, marca, modelo, vida, km_veiculo, km_pneu, valor, num_nf, fornecedor, manutencao_pneu.suco01, manutencao_pneu.suco02, manutencao_pneu.suco03, manutencao_pneu.suco04, nome_usuario FROM manutencao_pneu LEFT JOIN pneus ON manutencao_pneu.pneus_idpneus = pneus.idpneus LEFT JOIN usuarios ON manutencao_pneu.usuario = usuarios.idusuarios WHERE 1 $condicao");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=manutencoes.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "Filial",
        "Data de Reparo",
        "Tipo de Reparo",
        mb_convert_encoding('Nº Fogo','ISO-8859-1', 'UTF-8'),
        "Medida",
        "Marca",
        "Modelo",
        "Vida",
        mb_convert_encoding('Km Veículo','ISO-8859-1', 'UTF-8'),
        "Km Pneu",
        "Valor",
        mb_convert_encoding('Nº NF','ISO-8859-1', 'UTF-8'),
        "Fornecedor",
        "Suco 01",
        "Suco 02",
        "Suco 03",
        "Suco 04",
        "Registrado"
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding(str_replace(".",",",$dado) ,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


