<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false ){

    $dataAtual = date("Y/m/d");
    $peca = ucfirst(filter_input(INPUT_POST, 'peca')) ;
    $placa = strtoupper(filter_input(INPUT_POST, 'veiculo'));
    $descricao = ucfirst(filter_input(INPUT_POST,'descricao'));
    $imagem = $_FILES['imagem']['name']?$_FILES['imagem']['name']:null;
    $situacao = "Em análise";
    $idSolic = $_SESSION['idUsuario'];
    $localReparo = ucfirst(filter_input(INPUT_POST, 'local')) ;
    $categoria = filter_input(INPUT_POST, 'categoria');

    echo "$dataAtual<br>$peca<br>$placa<br>$descricao<br>$imagem<br> $situacao<br>$idSolic<br>$localReparo<br>$categoria<br>";

    
    $inserindo = "";

    $sql = $db->prepare("INSERT INTO solicitacoes(dataAtual, servico, descricao, imagem, placarVeiculo, idSolic, statusSolic, localReparo, categoria_idcategoria) VALUES (:datAtual,:peca, :descricao, :imagem, :placa, :idSolic, :situacao, :localReparo,:categoria)");
    $sql->bindValue(':datAtual', $dataAtual);
    $sql->bindValue(':peca', $peca);
    $sql->bindValue(':descricao', $descricao);
    $sql->bindValue(':imagem', $imagem);
    $sql->bindValue(':placa', $placa);
    $sql->bindValue(':idSolic', $idSolic);
    $sql->bindValue(':situacao', $situacao);
    $sql->bindValue(':localReparo', $localReparo);
    $sql->bindValue(':categoria', $categoria);
    $pasta = 'uploads/';
    $mover = move_uploaded_file($_FILES['imagem']['tmp_name'],$pasta.$imagem);
    if($sql->execute()){
        header("Location:solicitacoes.php");
    }else{
        print_r($sql->errorInfo());
    }

}

?>