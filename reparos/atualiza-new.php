<?php

session_start();
require("../conexao.php");
include('funcao.php');

$idModudulo = 9;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $token = filter_input(INPUT_POST, 'token');
    $placa = filter_input(INPUT_POST, 'veiculo'); 
    $motorista = filter_input(INPUT_POST, 'motorista');
    $rota =filter_input(INPUT_POST, 'rota');
    $problema = filter_input(INPUT_POST, 'descricao');
    $localReparo = filter_input(INPUT_POST, 'localReparo');
    $frete = filter_input(INPUT_POST, 'frete');
    $nf = filter_input(INPUT_POST, 'nf');

    $peca = $_POST['peca'];
    $qtd = str_replace(",",".",$_POST['qtd']) ;
    $vlUnit = str_replace(",",".", $_POST['vlUnit']);
    $desconto = str_replace(",", ".", $_POST['desconto']);
    $idSolicitacao = $_POST['id'];
    $fornecedor=$_POST['fornecedor'];
    
    $obs = filter_input(INPUT_POST, 'obs')?filter_input(INPUT_POST, 'obs'):null;
    $situacao = filter_input(INPUT_POST, 'situacao');
    if($situacao=='Aprovado'){
        $dataAprovacao = date('Y/m/d');
    }else{
        $dataAprovacao=null;
    }
    
    for($i=0; $i<count($peca); $i++){

        // echo "Toke: $token <br> Placa: $placa <br> Problema: $problema<br> Local: $localReparo<br> Peça: $peca[$i]<br> Qtd: $qtd[$i]<br> Valor: $vlUnit[$i]<br> ID: $idSolicitacao[$i] <br> OBS: $obs <br> Situação: $situacao<br><br>";

        $valorTotal =  ($vlUnit[$i]-$desconto[$i])*$qtd[$i];

        $sql = $db->prepare("UPDATE solicitacoes_new SET placa = :placa, motorista = :motorista, rota = :rota, problema = :problema, local_reparo = :localReparo, peca_servico = :peca, fornecedor=:fornecedor, qtd = :qtd, vl_unit = :vlUnit, desconto = :desconto, vl_total = :vlTotal, frete = :frete, num_nf=:nf, situacao = :situacao, data_aprovacao = :dataAprovacao, obs = :obs WHERE id = :id");
        $sql->bindValue(':placa', $placa);
        $sql->bindValue(':motorista', $motorista);
        $sql->bindValue(':rota', $rota);
        $sql->bindValue(':problema', $problema);
        $sql->bindValue(':localReparo', $localReparo);
        $sql->bindValue(':peca', $peca[$i]);
        $sql->bindValue(':fornecedor', $fornecedor[$i]);
        $sql->bindValue(':qtd', $qtd[$i]);
        $sql->bindValue(':vlUnit', $vlUnit[$i]);
        $sql->bindValue(':desconto', $desconto[$i]);
        $sql->bindValue(':vlTotal', $valorTotal);
        $sql->bindValue(':frete', $frete);
        $sql->bindValue(':nf', $nf);
        $sql->bindValue(':situacao', $situacao);
        $sql->bindValue(':dataAprovacao', $dataAprovacao);
        $sql->bindValue(':obs', $obs);
        $sql->bindValue(':id', $idSolicitacao[$i]);
        $sql->execute();

        // echo 'ID:'.$idSolicitacao[$i].' Situação:' .$situacao . "<br>";

    }

    echo "<script> alert('Solicitação Atualizada!')</script>";
    echo "<script> window.location.href='solicitacoes.php' </script>"; 
}

?>