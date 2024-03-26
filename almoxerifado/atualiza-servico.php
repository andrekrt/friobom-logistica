<?php

session_start();
require("../conexao.php");

$idModudulo = 11;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idservico = filter_input(INPUT_POST, 'id');
    $descricao = filter_input(INPUT_POST, 'descricao');
    $categoria = filter_input(INPUT_POST, 'categoria');

    $db->beginTransaction();

    try{
        $atualiza = $db->prepare("UPDATE servicos_almoxarifado SET descricao = :descricao, categoria = :categoria WHERE idservicos = :id");
        $atualiza->bindValue(':descricao', $descricao);
        $atualiza->bindValue(':categoria', $categoria);
        $atualiza->bindValue(':id', $idservico);
        $atualiza->execute();

        $db->commit();

        $_SESSION['msg'] = 'Serviço Atualizado com Sucesso!';
        $_SESSION['icon']='success';  

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Atualizar Serviço';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: servicos.php");
exit();
?>