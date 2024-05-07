<?php

session_start();
require("../conexao.php");

$idModudulo = 19;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    
    $usuario = $_SESSION['idUsuario'];
    $idvale = filter_input(INPUT_GET, 'idvale');
    $pago = 1;

    // echo $idvale . "<br>". $pago;

    $db->beginTransaction();

    try{
        $atualiza = $db->prepare("UPDATE vales SET pago=:pago WHERE idvale=:id");
        $atualiza->bindValue(':id', $idvale);
        $atualiza->bindValue(':pago', $pago);
        $atualiza->execute();        

        $db->commit();

        $_SESSION['msg'] = 'Vale Pago com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();

        $_SESSION['msg'] = 'Erro ao Pagar Vale';
        $_SESSION['icon']='error';

    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}

header("Location: vales.php");
exit();

?>