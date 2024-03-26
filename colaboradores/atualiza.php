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

    $db->beginTransaction();

    try{
        $atualiza = $db->prepare("UPDATE colaboradores SET nome_colaborador = :nome, cpf_colaborador = :cpf, salario_colaborador = :salario, salario_extra = :extra, cargo_colaborador = :cargo, ativo = :ativo WHERE idcolaboradores = :idcolaboradores");
        $atualiza->bindValue(':nome', $nome);
        $atualiza->bindValue(':cpf', $cpf);
        $atualiza->bindValue(':salario', $salario);
        $atualiza->bindValue(':extra', $extra);
        $atualiza->bindValue(':cargo', $funcao);
        $atualiza->bindValue(':ativo', $ativo);
        $atualiza->bindValue(':idcolaboradores', $id);
        $atualiza->execute();

        $db->commit();

        $_SESSION['msg'] = 'Colaborador Atualizado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();

        $_SESSION['msg'] = 'Erro ao Atualizar Colaborador';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}

header("Location: colaboradores.php");
exit();

?>