<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false &&  $_SESSION['tipoUsuario'] != 3){

    $idLocal = filter_input(INPUT_POST, 'id');
    $nomeLocal = filter_input(INPUT_POST, 'localReparo');
    $responsavel = filter_input(INPUT_POST, 'responsavel');
    $telefone = filter_input(INPUT_POST, 'telefone');

    $atualiza = $db->prepare("UPDATE local_reparo SET nome_local = :localReparo, responsavel = :responsavel, telefone = :telefone WHERE id_local = :idLocal" );
    $atualiza->bindValue('localReparo', $nomeLocal);
    $atualiza->bindValue(':responsavel', $responsavel);
    $atualiza->bindValue(':telefone', $telefone);
    $atualiza->bindValue(':idLocal', $idLocal);
    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='local-reparo.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }
   
}else{

    echo "<script> alert('Acesso não permitido!')</script>";
    echo "<script> window.location.href='local-reparo.php' </script>";

}

?>