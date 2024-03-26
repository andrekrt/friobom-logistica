<?php

session_start();
require("../conexao.php");

$idModudulo = 14;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $codigo = filter_input(INPUT_POST, 'codigo');
    $nome = filter_input(INPUT_POST, 'nome');
    $cidade = filter_input(INPUT_POST, 'residencia');
    $veiculo = filter_input(INPUT_POST, 'veiculo');

    $db->beginTransaction();

    try{
        $sql = $db->prepare("UPDATE supervisores SET nome_supervisor=:supervisor, cidade_residencia = :cidade, veiculo=:veiculo WHERE idsupervisor=:codigo ");
        $sql->bindValue(':supervisor', $nome);
        $sql->bindValue(':codigo', $codigo);
        $sql->bindValue(':cidade', $cidade);
        $sql->bindValue(':veiculo', $veiculo);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Supervisor Atualizado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Atualizar Supervisor';
        $_SESSION['icon']='error';
    }
    

}else{
    $_SESSION['msg'] = 'Acesso não permitido!';
    $_SESSION['icon']='warning';
}
header("Location: supervisores.php");
exit();
?>