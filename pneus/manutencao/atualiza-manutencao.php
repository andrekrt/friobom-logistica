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

    $idManutencao = filter_input(INPUT_POST, 'idmanutencao');
    $pneu = filter_input(INPUT_POST, 'pneu');
    $consultaPneu = $db->prepare("SELECT * FROM pneus WHERE idpneus = :pneu");
    $consultaPneu->bindValue(':pneu', $pneu);
    $consultaPneu->execute();
    $dados = $consultaPneu->fetch();

    $kmVeiculo = $dados['km_inicial'];
    $dataManutencao = date("Y-m-d");
    $tipoReparo = filter_input(INPUT_POST, 'tipoManutencao');
    $kmManutencao = filter_input(INPUT_POST, 'kmVeiculo');
    $kmPneu = $kmManutencao-$dados['km_inicial'];
    $valor = str_replace(".", "", filter_input(INPUT_POST, 'valor')) ;
    $valor = str_replace(",", ".", $valor);
    $nf = filter_input(INPUT_POST, 'nf');
    $fornecedor = filter_input(INPUT_POST, 'fornecedor');
    $suco01 = filter_input(INPUT_POST, 'suco01');
    $suco02 = filter_input(INPUT_POST, 'suco02');
    $suco03 = filter_input(INPUT_POST,'suco03');
    $suco04 = filter_input(INPUT_POST, 'suco04');

    //echo "$idManutencao<br>$pneu<br>$dataManutencao<br>$tipoReparo<br>$kmManutencao<br>$valor<br>$nf<br>$fornecedor<br>$suco01<br>$suco02<br>$suco03<br>$suco04<br>$kmPneu";

    $sql = $db->prepare("UPDATE manutencao_pneu SET tipo_manutencao = :tipoManutencao, pneus_idpneus = :pneu, km_veiculo = :kmVeiculo, km_pneu = :kmPneu, valor = :valor, num_nf = :nf, fornecedor = :fornecedor, suco01 = :suco01, suco02 = :suco02, suco03 = :suco03, suco04 = :suco04 WHERE idmanutencao_pneu = :idmanutencao");
    $sql->bindValue(':tipoManutencao', $tipoReparo);
    $sql->bindValue(':pneu', $pneu);
    $sql->bindValue(':kmVeiculo', $kmVeiculo);
    $sql->bindValue(':kmPneu', $kmPneu);
    $sql->bindValue(':valor', $valor);
    $sql->bindValue(':nf', $nf);
    $sql->bindValue(':fornecedor', $fornecedor);
    $sql->bindValue(':suco01', $suco01);
    $sql->bindValue(':suco02', $suco02);
    $sql->bindValue(':suco03', $suco03);
    $sql->bindValue(':suco04', $suco04);
    $sql->bindValue(':idmanutencao', $idManutencao);
    
    if($sql->execute()){
        $atualizaPneu = $db->prepare("UPDATE pneus SET suco01 = :suco01, suco02 = :suco02, suco03 = :suco03, suco04 = :suco04 WHERE idpneus = :idpneu");
        $atualizaPneu->bindValue(':suco01', $suco01);
        $atualizaPneu->bindValue(':suco02', $suco02);
        $atualizaPneu->bindValue(':suco03', $suco03);
        $atualizaPneu->bindValue(':suco04', $suco04);
        $atualizaPneu->bindValue(':idpneu', $pneu);

        if($atualizaPneu->execute()){
            echo "<script> alert('Manutenção Atualizada!!!')</script>";
            echo "<script> window.location.href='manutencoes.php' </script>";
        }else{
            print_r($atualizaPneu->errorInfo());
        }
        
    }else{
        print_r($sql->errorInfo());
    }
    
}else{

}

?>