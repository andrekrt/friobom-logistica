<?php

session_start();
require("../../conexao.php");
include '../funcao.php';

$idModudulo = 12;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $filial = $_SESSION['filial'];
    $dataRodizio = date("Y-m-d");
    $pneu = filter_input(INPUT_POST, 'pneu');

    //consultas dados anteriores
    $consultaPneu=$db->prepare("SELECT * FROM pneus WHERE idpneus = :pneu");
    $consultaPneu->bindValue(':pneu', $pneu);
    $consultaPneu->execute();
    $dados = $consultaPneu->fetch();
    
    $veiculoAnterior = $dados['veiculo'];
    $kmInicialVeiculoAnterior = $dados['km_inicial'];
    $kmRodadoTotal = $dados['km_rodado'];
    $kmFinalVeiculoAnterior = filter_input(INPUT_POST, 'kmFinal');
    $kmRodadoAnterior = $kmFinalVeiculoAnterior-$kmInicialVeiculoAnterior;
    $novoVeiculo = filter_input(INPUT_POST, 'novoVeiculo');
    $kmInicialNovo = filter_input(INPUT_POST, 'kmInicial');
    $kmRodadoTotal = $kmRodadoTotal+$kmRodadoAnterior;
    $localizacao = filter_input(INPUT_POST, 'localizacao');
    $posicao = filter_input(INPUT_POST, 'posicao');
    $usuario = $_SESSION['idUsuario'];

    $db->beginTransaction();

    try{
        $sql = $db->prepare("INSERT INTO rodizio_pneu (data_rodizio, pneu, veiculo_anterior, km_inicial_veiculo_anterior, km_final_veiculo_anterior, km_rodado_veiculo_anterior, novo_veiculo, km_inicial_novo_veiculo, usuario, filial) VALUES (:dataRodizio, :pneu, :veiculoAnterior, :kmInicialAnterior, :kmFinalAnterior, :kmRodadoAnterior, :novoVeiculo, :kmInicialNovo, :usuario, :filial)");
        $sql->bindValue(':dataRodizio', $dataRodizio);
        $sql->bindValue(':pneu', $pneu);
        $sql->bindValue(':veiculoAnterior', $veiculoAnterior);
        $sql->bindValue(':kmInicialAnterior', $kmInicialVeiculoAnterior);
        $sql->bindValue(':kmFinalAnterior', $kmFinalVeiculoAnterior);
        $sql->bindValue(':kmRodadoAnterior', $kmRodadoAnterior);
        $sql->bindValue(':novoVeiculo', $novoVeiculo);
        $sql->bindValue(':kmInicialNovo', $kmInicialNovo);
        $sql->bindValue(':usuario', $usuario);
        $sql->bindValue(':filial', $filial);
        $sql->execute();

        $somaRodizio = $db->prepare("SELECT SUM(km_rodado_veiculo_anterior) as kmRodadoAnterior FROM rodizio_pneu WHERE pneu=:pneu");
        $somaRodizio->bindValue(':pneu', $pneu);
        $somaRodizio->execute();
        $somaRodizio=$somaRodizio->fetch();
        $valor = $somaRodizio['kmRodadoAnterior'];

        addExtrato($pneu, "Rodízio", $valor, $veiculoAnterior, $kmFinalVeiculoAnterior);

        $atualizaPneu = $db->prepare("UPDATE pneus SET veiculo = :novoVeiculo, km_inicial = :kmInicialNovo, km_rodado = :kmRodadoTotal, localizacao = :localizacao, posicao_inicio = :posicao WHERE idpneus = :idpneu");
        $atualizaPneu->bindValue(':novoVeiculo', $novoVeiculo);
        $atualizaPneu->bindValue(':kmInicialNovo', $kmInicialNovo);
        $atualizaPneu->bindValue(':kmRodadoTotal',$valor);
        $atualizaPneu->bindValue(':idpneu', $pneu);
        $atualizaPneu->bindValue(':localizacao', $localizacao );
        $atualizaPneu->bindValue(':posicao', $posicao );
        $atualizaPneu->execute();

        $db->commit();

        $_SESSION['msg'] = 'Rodízio Lançado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Registrar Ocorrência';
        $_SESSION['icon']='error';
    }

    header("Location: rodizio.php");
    exit();    

}else{

}

?>