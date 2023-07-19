<?php

session_start();
require("../conexao.php");

$idModudulo = 5;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

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