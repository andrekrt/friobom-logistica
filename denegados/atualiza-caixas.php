<?php

session_start();
require("../conexao.php");

$idModudulo = 8;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idUsuario = $_SESSION['idUsuario'];
    $id = filter_input(INPUT_POST,'id');
    $carga = filter_input(INPUT_POST,'carga');
    $qtd = filter_input(INPUT_POST,'qtd');
    $pallets= filter_input(INPUT_POST, 'pallets');
  
    $db->beginTransaction();

    try{
        $atualiza = $db->prepare("UPDATE caixas SET carregamento = :carregamento, qtd_caixas = :qtd, qtd_pallets=:palltes WHERE idcaixas = :id");
        $atualiza->bindValue(':carregamento', $carga);
        $atualiza->bindValue(':qtd', $qtd);
        $atualiza->bindValue(':palltes', $pallets);
        $atualiza->bindValue(':id', $id);
        $atualiza->execute();

        $db->commit();

        $_SESSION['msg'] = 'Saída de Caixa Atualizada com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Registrar Saída de Caixa';
        $_SESSION['icon']='error';
    }    

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: caixas.php");
exit();
?>