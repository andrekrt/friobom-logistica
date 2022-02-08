<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 99 || $_SESSION['tipoUsuario'] == 4)){

    $id = filter_input(INPUT_POST,'id');
    $nome = filter_input(INPUT_POST, 'nome');
    $cpf = filter_input(INPUT_POST, 'cpf');
    $salario = str_replace(",",".",filter_input(INPUT_POST, 'salario')) ;
    $extra = str_replace(",",".",filter_input(INPUT_POST, 'extra')) ;
    $funcao = filter_input(INPUT_POST, 'funcao');
    $ativo = filter_input(INPUT_POST, 'ativo');
    if($ativo=='on'){
        $ativo = 0;
    }else{
        $ativo = 1;
    }    

    $atualiza = $db->prepare(" UPDATE colaboradores SET nome_colaborador = :nome, cpf_colaborador = :cpf, salario_colaborador = :salario, salario_extra = :extra, cargo_colaborador = :cargo, ativo = :ativo WHERE idcolaboradores = :idcolaboradores");
    $atualiza->bindValue(':nome', $nome);
    $atualiza->bindValue(':cpf', $cpf);
    $atualiza->bindValue(':salario', $salario);
    $atualiza->bindValue(':extra', $extra);
    $atualiza->bindValue(':cargo', $funcao);
    $atualiza->bindValue(':ativo', $ativo);
    $atualiza->bindValue(':idcolaboradores', $id);

    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='colaboradores.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }

}else{
    echo "Erro";
}

?>