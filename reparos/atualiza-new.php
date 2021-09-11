<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false){

    $token = filter_input(INPUT_POST, 'token');
    $placa = filter_input(INPUT_POST, 'veiculo'); 
    $motorista = filter_input(INPUT_POST, 'motorista');
    $rota =filter_input(INPUT_POST, 'rota');
    $problema = filter_input(INPUT_POST, 'descricao');
    $localReparo = filter_input(INPUT_POST, 'localReparo');

    $peca = $_POST['peca'];
    $qtd = str_replace(",",".",$_POST['qtd']) ;
    $vlUnit = str_replace(",",".", $_POST['vlUnit']);
    $idSolicitacao = $_POST['id'];
    
    $obs = filter_input(INPUT_POST, 'obs')?filter_input(INPUT_POST, 'obs'):null;
    $situacao = filter_input(INPUT_POST, 'situacao')?filter_input(INPUT_POST, 'situacao'):"Em análise";
    if($situacao=='Aprovado'){
        $dataAprovacao = date('Y/m/d');
    }else{
        $dataAprovacao=null;
    }


    for($i=0; $i<count($peca); $i++){

        //echo "Toke: $token <br> Placa: $placa <br> Problema: $problema<br> Local: $localReparo<br> Peça: $peca[$i]<br> Qtd: $qtd[$i]<br> Valor: $vlUnit[$i]<br> ID: $idSolicitacao[$i] <br> OBS: $obs <br> Situação: $situacao<br><br>";

        $valorTotal = $vlUnit[$i]*$qtd[$i];

        $sql = $db->prepare("UPDATE solicitacoes_new SET placa = :placa, motorista = :motorista, rota = :rota, problema = :problema, local_reparo = :localReparo, peca_servico = :peca, qtd = :qtd, vl_unit = :vlUnit, vl_total = :vlTotal, situacao = :situacao, data_aprovacao = :dataAprovacao, obs = :obs WHERE id = :id");
        $sql->bindValue(':placa', $placa);
        $sql->bindValue(':motorista', $motorista);
        $sql->bindValue(':rota', $rota);
        $sql->bindValue(':problema', $problema);
        $sql->bindValue(':localReparo', $localReparo);
        $sql->bindValue(':peca', $peca[$i]);
        $sql->bindValue(':qtd', $qtd[$i]);
        $sql->bindValue(':vlUnit', $vlUnit[$i]);
        $sql->bindValue(':vlTotal', $valorTotal);
        $sql->bindValue(':situacao', $situacao);
        $sql->bindValue(':dataAprovacao', $dataAprovacao);
        $sql->bindValue(':obs', $obs);
        $sql->bindValue(':id', $idSolicitacao[$i]);

        if($sql->execute()){
            echo "<script> alert('Peça Adicionada!')</script>";
            echo "<script> window.location.href='solicitacoes.php' </script>"; 
        }else{
            print_r($sql->errorInfo());
        }

    }

}

?>