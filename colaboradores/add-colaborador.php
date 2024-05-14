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
    $filial = $_SESSION['filial'];
    $nomeColaborador = filter_input(INPUT_POST, 'nome');
    $cpf = filter_input(INPUT_POST, 'cpf');
    $salario = str_replace(",",".",filter_input(INPUT_POST, 'salario'));
    $extra = str_replace(",",".",filter_input(INPUT_POST, 'extra'));
    $funcao = filter_input(INPUT_POST, 'funcao');

    $db->beginTransaction();

    try{
        $consulta = $db->prepare("SELECT * FROM colaboradores WHERE cpf_colaborador = :cpf ");
        $consulta->bindValue(':cpf', $cpf);
        $consulta->execute();

        if($consulta->rowCount()>0){
            $_SESSION['msg'] = 'Esse Colaborador já está cadastrado!';
            $_SESSION['icon']='warning';
            header("Location: colaboradores.php");
            exit();
        }

        $sql = $db->prepare("INSERT INTO colaboradores (nome_colaborador, cpf_colaborador, salario_colaborador, salario_extra, cargo_colaborador, filial) VALUES (:nome, :cpf, :salario, :salarioExtra, :cargo, :filial) ");
        $sql->bindValue(':nome', $nomeColaborador);
        $sql->bindValue(':cpf', $cpf);
        $sql->bindValue(':salario', $salario);
        $sql->bindValue(':salarioExtra', $extra);
        $sql->bindValue(':cargo', $funcao);
        $sql->bindValue(':filial', $filial);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Colaborador Cadastrado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Cadastrar Colaborador';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: colaboradores.php");
exit();
?>