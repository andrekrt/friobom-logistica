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
    $motorista = filter_input(INPUT_POST, 'motorista');
    $rota = filter_input(INPUT_POST,'rota');
    $carregamento = filter_input(INPUT_POST, 'carregamento');
    $idVale = filter_input(INPUT_POST, 'idvale');
    $valor = str_replace(".",",",filter_input(INPUT_POST, 'valor')) ;
    $situacao = filter_input(INPUT_POST,'status');

    $db->beginTransaction();

    try{

        $atualiza = $db->prepare("UPDATE vales SET motorista=:motorista, rota=:rota, valor=:valor, carregamento=:carregamento, situacao=:situacao WHERE idvale=:id");
        $atualiza->bindValue(':motorista', $motorista);
        $atualiza->bindValue(':rota', $rota);
        $atualiza->bindValue(':valor', $valor);
        $atualiza->bindValue(':carregamento', $carregamento);  
        $atualiza->bindValue(':situacao',$situacao);
        $atualiza->bindValue(':id', $idVale);
        $atualiza->execute();

        $db->commit();

        $_SESSION['msg'] = 'Vale Atualizado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();

        $_SESSION['msg'] = 'Erro ao Atualizar Vale';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}

header("Location: vales.php");
exit();

?>