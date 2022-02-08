<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

    $idPeca = filter_input(INPUT_POST, 'idPeca');
    $descricao = filter_input(INPUT_POST, 'descricao');
    $medida = filter_input(INPUT_POST, 'medida');
    $grupo = filter_input(INPUT_POST, 'grupo');
    $estoqueMinimo = filter_input(INPUT_POST, 'estoqueMinimo');

    $atualiza = $db->prepare("UPDATE peca_estoque SET descricao_peca = :descricao, un_medida = :medida, grupo_peca = :grupo, estoque_minimo = :estoqueMinimo WHERE idpeca = :idPeca");
    $atualiza->bindValue(':descricao', $descricao);
    $atualiza->bindValue(':medida', $medida);
    $atualiza->bindValue(':grupo', $grupo);
    $atualiza->bindValue(':estoqueMinimo', $estoqueMinimo);
    $atualiza->bindValue(':idPeca', $idPeca);

    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='pecas.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }

}else{

}

?>