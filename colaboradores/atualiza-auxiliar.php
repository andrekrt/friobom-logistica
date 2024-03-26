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
    $rota = filter_input(INPUT_POST, 'rota'); 

    $db->beginTransaction();

    try{
        $atualiza = $db->prepare(" UPDATE auxiliares_rota SET nome_auxiliar = :nome, cpf_auxiliar = :cpf, salario_auxiliar = :salario, rota = :rota WHERE idauxiliares = :idauxiliares");
        $atualiza->bindValue(':nome', $nome);
        $atualiza->bindValue(':cpf', $cpf);
        $atualiza->bindValue(':salario', $salario);
        $atualiza->bindValue(':rota', $rota);
        $atualiza->bindValue(':idauxiliares', $id);
        $atualiza->execute();

        $db->commit();

        $_SESSION['msg'] = 'Auxiliar Atualizado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();

        $_SESSION['msg'] = 'Erro ao Atualizar Auxiliar';
        $_SESSION['icon']='error';
    }

    

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: auxiliares.php");
exit();
?>