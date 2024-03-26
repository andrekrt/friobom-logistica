<?php 

session_start();
require("../conexao.php");

$idModudulo = 2;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $idTk = filter_input(INPUT_GET, 'idTk');
    $ativo = 0;

    $db->beginTransaction();

    try{
        $atualiza = $db->prepare("UPDATE thermoking SET ativo = :ativo WHERE idthermoking = :tk");
        $atualiza->bindValue(':tk', $idTk);
        $atualiza->bindValue(':ativo', $ativo);
        $atualiza->execute();

        $db->commit();

        $_SESSION['msg'] = 'Thermoking Desativo com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Desativar Thermoking';
        $_SESSION['icon']='error';
    }
    

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: thermoking.php");
exit();
?>