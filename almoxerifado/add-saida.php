<?php

session_start();
require("../conexao.php");
include('funcoes.php');

$idModudulo = 11;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idUsuario = $_SESSION['idUsuario'];

    $dataSaida = date("Y-m-d", strtotime(filter_input(INPUT_POST, 'dataAbertura'))) ;
    $qtd = str_replace(",",".",filter_input(INPUT_POST, 'qtd')) ;
    $placa = filter_input(INPUT_POST, 'placa');
    $obs = filter_input(INPUT_POST, 'obs');  
    $os = filter_input(INPUT_POST, 'idOrdemServico');

    $peca = $_POST['peca'];
    $servico = $_POST['servico'];
    $qtd = $_POST['qtd'];
    $requisicao = $_POST['requisicao'];

    $db->beginTransaction();

    try{
        for($i=0; $i<count($qtd);$i++){
            if(contaEstoque($peca[$i])<$qtd[$i]){
                $_SESSION['msg'] = 'Estoque Insuficiente!';
                $_SESSION['icon']='warning';
                header("Location: form-saidapeca-os.php?idOs=$os");
                exit();
            }
        }
    
        for($i=0;$i<count($peca);$i++){
            $inserir = $db->prepare("INSERT INTO saida_estoque (data_saida, qtd, peca_idpeca, placa, obs, servico, os, requisicao_saida, id_usuario) VALUES (:dataSaida, :qtd, :peca, :placa, :obs, :servico, :os, :requisicao, :idUsuario)");
            $inserir->bindValue(':dataSaida', $dataSaida);
            $inserir->bindValue(':qtd', $qtd[$i]);
            $inserir->bindValue(':peca', $peca[$i]);
            $inserir->bindValue(':placa', $placa);
            $inserir->bindValue(':obs', $obs);
            $inserir->bindValue(':servico', $servico[$i]);
            $inserir->bindValue(':os', $os);
            $inserir->bindValue(':requisicao', $requisicao[$i]);
            $inserir->bindValue(':idUsuario', $idUsuario);
            $inserir->execute();

            atualizaEStoque($peca[$i]);

            $db->commit();

            $_SESSION['msg'] = 'Saída Lançada com Sucesso';
            $_SESSION['icon']='success';

        }
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Lançar Saída';
        $_SESSION['icon']='error';
    }
    header("Location: ordem-servico.php");
    exit();
    
}

?>