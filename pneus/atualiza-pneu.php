<?php

session_start();
require("../conexao.php");

$idModudulo = 12;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idpneu = filter_input(INPUT_POST, 'idpneu');
    $nFogo = filter_input(INPUT_POST, 'nFogo');
    $medida = strtoupper(filter_input(INPUT_POST, 'medida'));
    $calibMax = filter_input(INPUT_POST, 'calibMax');
    $marca = strtoupper(filter_input(INPUT_POST, 'marca'));
    $modelo = strtoupper(filter_input(INPUT_POST, 'modelo'));
    $serie = strtoupper(filter_input(INPUT_POST, 'nSerie'));
    $vida = filter_input(INPUT_POST, 'vida');
    $posicao = strtoupper(filter_input(INPUT_POST, 'posicao'));
    $situacao = filter_input(INPUT_POST, 'situacao');
    $localizacao = filter_input(INPUT_POST, 'localizacao');
    $veiculo = filter_input(INPUT_POST, 'veiculo');
    $kmVeiculo = filter_input(INPUT_POST, 'kmVeiculo');
    $suco01 = filter_input(INPUT_POST, 'suco01');
    $suco02 = filter_input(INPUT_POST, 'suco02');
    $suco03 = filter_input(INPUT_POST, 'suco03');
    $suco04 = filter_input(INPUT_POST, 'suco04');

    $db->beginTransaction();

    try{
        $atualiza = $db->prepare("UPDATE pneus SET num_fogo = :nFogo, medida = :medida, calibragem_maxima = :calibMax, marca = :marca, modelo =:modelo, num_serie = :nSerie, vida = :vida, posicao_inicio = :posicao, veiculo = :veiculo, km_inicial = :kmInicial, situacao = :situacao, localizacao = :localizacao, suco01 = :suco01, suco02 = :suco02, suco03 = :suco03, suco04 = :suco04  WHERE idpneus = :idpneu");
        $atualiza->bindValue(':nFogo', $nFogo);
        $atualiza->bindValue(':medida', $medida);
        $atualiza->bindValue(':calibMax', $calibMax);
        $atualiza->bindValue(':marca', $marca);
        $atualiza->bindValue(':modelo', $modelo);
        $atualiza->bindValue(':nSerie', $serie);
        $atualiza->bindValue(':vida', $vida);
        $atualiza->bindValue(':posicao', $posicao);
        $atualiza->bindValue(':veiculo', $veiculo);
        $atualiza->bindValue(':kmInicial', $kmVeiculo);
        $atualiza->bindValue(':situacao', $situacao);
        $atualiza->bindValue(':localizacao', $localizacao);
        $atualiza->bindValue(':suco01', $suco01);
        $atualiza->bindValue(':suco02', $suco02);
        $atualiza->bindValue(':suco03', $suco03);
        $atualiza->bindValue(':suco04', $suco04);
        $atualiza->bindValue(':idpneu', $idpneu);
        $atualiza->execute();

        $db->commit();

        $_SESSION['msg'] = 'Pneu Atualizado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Atualizar Pneu';
        $_SESSION['icon']='error';
    }

    header("Location: pneus.php");
    exit();

}else{

}

?>