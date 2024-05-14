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
        $condicao = "AND rodizio_pneu.filial=$filial";
    }

    $db->exec("set names utf8");
    $sql = $db->query("SELECT data_rodizio, num_fogo, veiculo_anterior, km_inicial_veiculo_anterior, km_final_veiculo_anterior, km_rodado_veiculo_anterior, novo_veiculo, km_inicial_novo_veiculo, nome_usuario FROM `rodizio_pneu` LEFT JOIN pneus ON rodizio_pneu.pneu = pneus.idpneus LEFT JOIN usuarios ON pneus.usuario = usuarios.idusuarios WHERE 1 $condicao");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=rodizio.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        mb_convert_encoding('Data do Rodízio','ISO-8859-1', 'UTF-8'),
       "Pneu",
        mb_convert_encoding('Veículo Anterior','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Km Inicial Veículo Anterior','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Km Final Veículo Anterior','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Km Rodado Veículo Anterior','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Veículo Atual','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Km Inicial Veículo Atual','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Lançado por','ISO-8859-1', 'UTF-8'),
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding($dado,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


