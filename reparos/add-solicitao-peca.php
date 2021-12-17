<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false){

    //$token = sprintf('%07X', mt_rand(0, 0xFFFFFFF));
    $consultaToken = $db->query("SELECT MAX(token) as token FROM solicitacoes_new");
    $token = $consultaToken->fetch();
    if(empty($token['token'])){
        $newToken = 0+1;
    }else{
        $newToken = $token['token']+1;
    }

    $dataAtual = date("Y/m/d");
    $placa = filter_input(INPUT_POST, 'veiculo');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $rota = filter_input(INPUT_POST,'rota');
    $problema = filter_input(INPUT_POST, 'descricao');
    $localReparo = filter_input(INPUT_POST, 'localReparo');
    $frete = str_replace(",",".", filter_input(INPUT_POST, 'frete'));

    echo $frete;
    
    $situacao = "Em análise";
    $usuario = $_SESSION['idUsuario'];

    $peca = $_POST['peca'];
    $qtd = str_replace(",",".",$_POST['qtd']) ;
    $valorUnit = str_replace(",", ".",$_POST['vlUnit'] ) ;
    $desconto = str_replace(",", ".", $_POST['desconto']);
    $imagem = $_FILES['imagem']['name']?$_FILES['imagem']['name']:null;
    
    for($i=0; $i<count($peca); $i++){

        $valorTotal = ($valorUnit[$i]-$desconto[$i])*$qtd[$i];

        $sql = $db->prepare("INSERT INTO solicitacoes_new (token, data_atual, placa, motorista, rota, problema, local_reparo, imagem, peca_servico, qtd, vl_unit, desconto,  vl_total, frete, situacao, usuario) VALUES (:token, :dataAtual, :placa, :motorista, :rota, :problema, :localReparo, :imagem, :peca, :qtd, :vlUnit, :desconto, :vlTotal, :frete, :situacao, :usuario)");
        $sql->bindValue(':token', $newToken);
        $sql->bindValue(':dataAtual', $dataAtual);
        $sql->bindValue(':placa', $placa);
        $sql->bindValue(':motorista', $motorista);
        $sql->bindValue(':rota', $rota);
        $sql->bindValue(':problema', $problema);
        $sql->bindValue(':localReparo', $localReparo);
        $sql->bindValue(':imagem', $imagem[$i]);
        $sql->bindValue(':peca', $peca[$i]);
        $sql->bindValue(':qtd', $qtd[$i]);
        $sql->bindValue(':vlUnit', $valorUnit[$i]);
        $sql->bindValue(':desconto', $desconto[$i]);
        $sql->bindValue(':vlTotal', $valorTotal);
        $sql->bindValue(':frete', $frete);
        $sql->bindValue(':situacao', $situacao);
        $sql->bindValue(':usuario', $usuario);
        
        if($sql->execute()){

            $pasta = 'uploads/';
            $mover = move_uploaded_file($_FILES['imagem']['tmp_name'][$i],$pasta.$imagem[$i]);

            echo "<script> alert('Solicitação Lançada!!')</script>";
            echo "<script> window.location.href='solicitacoes.php' </script>";
        }else{
            print_r($sql->errorInfo());
        }

    }

}else{

}

?>