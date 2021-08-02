<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 3 || $_SESSION['tipoUsuario'] == 99)){

    $id = filter_input(INPUT_POST, 'id');
    $descricao = filter_input(INPUT_POST, 'descricao');
    $categoria = filter_input(INPUT_POST, 'categoria');
    $medida = filter_input(INPUT_POST, 'medida');

    $atualiza = $db->prepare("UPDATE peca_reparo SET descricao = :descricao, categoria = :categoria, un_medida = :medida WHERE id_peca_reparo = :id" );
    $atualiza->bindValue('id', $id);
    $atualiza->bindValue(':descricao', $descricao);
    $atualiza->bindValue(':categoria', $categoria);
    $atualiza->bindValue(':medida', $medida);
    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='pecas.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }
   
}else{

    echo "<script> alert('Acesso não permitido!')</script>";
    echo "<script> window.location.href='pecas.php' </script>";

}

?>