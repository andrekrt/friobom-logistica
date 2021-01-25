<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false ){

    $dataAtual = date("Y/m/d");
    $peca = ucfirst(filter_input(INPUT_POST, 'peca')) ;
    $placa = strtoupper(filter_input(INPUT_POST, 'veiculo'));
    $descricao = ucfirst(filter_input(INPUT_POST,'descricao'));
    $imagem = $_FILES['imagem']['name'];
    $situacao = "Em análise";
    $idSolic = $_SESSION['id_reparo'];
    $localReparo = ucfirst(filter_input(INPUT_POST, 'local')) ;
    $inserindo = "INSERT INTO solicitacoes(dataAtual, servico, descricao, imagem, placarVeiculo, idSolic, statusSolic, localReparo) VALUES ('$dataAtual','$peca', '$descricao', '$imagem', '$placa', '$idSolic', '$situacao', '$localReparo')";

    $sql = $db->query($inserindo);
    $pasta = 'uploads/';
    $mover = move_uploaded_file($_FILES['imagem']['tmp_name'],$pasta.$imagem);
    if($sql){
        header("Location:solicitacoes.php");
    }else{
        echo "Não foi cadastrado";
    }

}

?>