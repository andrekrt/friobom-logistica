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

    $nome = filter_input(INPUT_POST, 'nome');
    $cpf = filter_input(INPUT_POST, 'cpf');
    $salario = str_replace(",",".",filter_input(INPUT_POST, 'salario'));
    $rota = filter_input(INPUT_POST, 'rota');

    $db->beginTransaction();

    try{
        $consulta = $db->prepare("SELECT * FROM auxiliares_rota WHERE cpf_auxiliar = :cpf ");
        $consulta->bindValue(':cpf', $cpf);
        $consulta->execute();

        if($consulta->rowCount()>0){
            $_SESSION['msg'] = 'Esse Auxiliar já está cadastrado!';
            $_SESSION['icon']='warning';
            header("Location: auxiliares.php");
            exit();
        }
        $sql = $db->prepare("INSERT INTO auxiliares_rota (nome_auxiliar, cpf_auxiliar, salario_auxiliar, rota) VALUES (:nome, :cpf, :salario, :rota) ");
        $sql->bindValue(':nome', $nome);
        $sql->bindValue(':cpf', $cpf);
        $sql->bindValue(':salario', $salario);
        $sql->bindValue(':rota', $rota);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Auxiliar Cadastrado com Sucesso';
        $_SESSION['icon']='success';
        
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Cadastrado Auxiliar';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}

header("Location: auxiliares.php");
exit();
?>