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
    $frete = filter_input(INPUT_POST, 'frete');
    $nf = filter_input(INPUT_POST, 'nf');

    // verificar cidade base do veiculo para registrar no bd da viagem
    $sqlCidade = $db->prepare("SELECT cidade_base FROM veiculos WHERE placa_veiculo =:veiculo");
    $sqlCidade->bindValue(':veiculo', $placa);
    $sqlCidade->execute();
    if($sqlCidade->rowCount()>0){
        $cidadeBase = $sqlCidade->fetch();
        $cidadeBase = $cidadeBase['cidade_base'];
    }else{
        $cidadeBase='Bacabal';
    }

    $peca = $_POST['peca'];
    $qtd = str_replace(",",".",$_POST['qtd']) ;
    $vlUnit = str_replace(",",".", $_POST['vlUnit']);
    $desconto = str_replace(",", ".", $_POST['desconto']);
    $idSolicitacao = $_POST['id'];
    $fornecedor=filter_input(INPUT_POST, 'fornecedor');
    
    $obs = filter_input(INPUT_POST, 'obs')?filter_input(INPUT_POST, 'obs'):null;
    $situacao = filter_input(INPUT_POST, 'situacao');
    if($situacao=='Aprovado'){
        $dataAprovacao = date('Y/m/d');
    }else{
        $dataAprovacao=null;
    }
    
    for($i=0; $i<count($peca); $i++){

        // echo "Toke: $token <br> Placa: $placa <br> Problema: $problema<br> Peça: $peca[$i]<br> Qtd: $qtd[$i]<br> Valor: $vlUnit[$i]<br> ID: $idSolicitacao[$i] <br> OBS: $obs <br> Situação: $situacao<br><br>";

        $valorTotal =  ($vlUnit[$i]-$desconto[$i])*$qtd[$i];

        $sql = $db->prepare("UPDATE solicitacoes_new SET placa = :placa, motorista = :motorista, rota = :rota, problema = :problema, peca_servico = :peca, fornecedor=:fornecedor, qtd = :qtd, vl_unit = :vlUnit, desconto = :desconto, vl_total = :vlTotal, frete = :frete, num_nf=:nf, situacao = :situacao, data_aprovacao = :dataAprovacao, obs = :obs, cidade_base=:cidadeBase WHERE id = :id");
        $sql->bindValue(':placa', $placa);
        $sql->bindValue(':motorista', $motorista);
        $sql->bindValue(':rota', $rota);
        $sql->bindValue(':problema', $problema);
        $sql->bindValue(':peca', $peca[$i]);
        $sql->bindValue(':fornecedor', $fornecedor);
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
        $sql->bindValue(':cidadeBase', $cidadeBase);
        // $sql->execute();

       
        

        if($placa==='Estoque' && $situacao==="Aprovado"){
            // echo " Peça: $peca[$i]<br> Qtd: $qtd[$i]<br> Valor: $vlUnit[$i]<br> ID: $idSolicitacao[$i] <br> OBS: $obs <br> Situação: $situacao<br><br>";
            addEstoque($peca[$i], $fornecedor, $qtd[$i], $vlUnit[$i], $desconto[$i], $valorTotal, $nf, $obs, $frete, $idUsuario);
        }

        // echo 'ID:'.$idSolicitacao[$i].' Situação:' .$situacao . "<br>";

    }

    // echo "<script> alert('Solicitação Atualizada!')</script>";
    // echo "<script> window.location.href='solicitacoes.php' </script>"; 
}

?>