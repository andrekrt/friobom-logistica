<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario']==99 || $_SESSION['tipoUsuario'] ==4){

    $nomeColaborador = filter_input(INPUT_POST, 'nome');
    $cpf = filter_input(INPUT_POST, 'cpf');
    $salario = str_replace(",",".",filter_input(INPUT_POST, 'salario'));
    $extra = str_replace(",",".",filter_input(INPUT_POST, 'extra'));
    $funcao = filter_input(INPUT_POST, 'funcao');

    $consulta = $db->prepare("SELECT * FROM colaboradores WHERE cpf_colaborador = :cpf ");
    $consulta->bindValue(':cpf', $cpf);
    $consulta->execute();

    if($consulta->rowCount()>0){
        echo "<script>alert('Esse Colaborador já está cadastrado!');</script>";
        echo "<script>window.location.href='colaboradores.php'</script>";
    }else{
        $sql = $db->prepare("INSERT INTO colaboradores (nome_colaborador, cpf_colaborador, salario_colaborador, salario_extra, cargo_colaborador) VALUES (:nome, :cpf, :salario, :salarioExtra, :cargo) ");
        $sql->bindValue(':nome', $nomeColaborador);
        $sql->bindValue(':cpf', $cpf);
        $sql->bindValue(':salario', $salario);
        $sql->bindValue(':salarioExtra', $extra);
        $sql->bindValue(':cargo', $funcao);

        if($sql->execute()){
            echo "<script>alert('Colaborador Cadastrado!');</script>";
            echo "<script>window.location.href='colaboradores.php'</script>";
        }else{
            print_r($sql->errorInfo());
        }
    }
    //echo "$codMotorista<br> $nomeMotorista<br> $cnh <br> $validadeCnh";

}else{
    echo "<script>alert('Acesso negado!');</script>";
        echo "<script>window.location.href='colaboradores.php'</script>";
}

?>