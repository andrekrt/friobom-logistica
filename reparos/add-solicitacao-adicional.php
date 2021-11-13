<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false){

    $newToken = filter_input(INPUT_POST, 'token');
    $dataAtual = filter_input(INPUT_POST, 'data');
    $placa = filter_input(INPUT_POST, 'veiculo');
    $problema = filter_input(INPUT_POST, 'problema');
    $localReparo = filter_input(INPUT_POST, 'localReparo');
    $situacao = "Em análise";
    $usuario = $_SESSION['idUsuario'];

    $peca = $_POST['peca'];
    $qtd = str_replace(",",".",$_POST['qtd']) ;
    $valorUnit = str_replace(",", ".",$_POST['vlUnit'] ) ;
    $imagem = $_FILES['imagem']['name']?$_FILES['imagem']['name']:null;

    for($i=0; $i<count($peca); $i++){

        $valorTotal = $valorUnit[$i]*$qtd[$i];

        $sql = $db->prepare("INSERT INTO solicitacoes_new (token, data_atual, placa, problema, local_reparo, imagem, peca_servico, qtd, vl_unit, vl_total, situacao, usuario) VALUES (:token, :dataAtual, :placa, :problema, :localReparo, :imagem, :peca, :qtd, :vlUnit, :vlTotal, :situacao, :usuario)");
        $sql->bindValue(':token', $newToken);
        $sql->bindValue(':dataAtual', $dataAtual);
        $sql->bindValue(':placa', $placa);
        $sql->bindValue(':problema', $problema);
        $sql->bindValue(':localReparo', $localReparo);
        $sql->bindValue(':imagem', $imagem[$i]);
        $sql->bindValue(':peca', $peca[$i]);
        $sql->bindValue(':qtd', $qtd[$i]);
        $sql->bindValue(':vlUnit', $valorUnit[$i]);
        $sql->bindValue(':vlTotal', $valorTotal);
        $sql->bindValue(':situacao', $situacao);
        $sql->bindValue(':usuario', $usuario);
        
        if($sql->execute()){
            $atualiaza = $db->prepare("UPDATE solicitacoes_new SET situacao = :situacao WHERE token=:token");
            $atualiaza->bindValue(':token', $newToken);
            $atualiaza->bindValue(':situacao', $situacao);
            if($atualiaza->execute()){
                $pasta = 'uploads/';
                $mover = move_uploaded_file($_FILES['imagem']['tmp_name'][$i],$pasta.$imagem[$i]);

                echo "<script> alert('Peça Adicionada!')</script>";
                echo "<script> window.location.href='solicitacoes.php' </script>"; 
            }else{
                print_r($sql->errorInfo());
            }
            
        }else{
            print_r($sql->errorInfo());
        }

    }

}else{

}

?>