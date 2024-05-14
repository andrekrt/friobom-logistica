<?php

use Mpdf\Tag\Input;

session_start();
require("../conexao.php");

$idModudulo = 16;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $filial = $_SESSION['filial'];
    $saida = filter_input(INPUT_POST, 'saida');
    $carregamento = filter_input(INPUT_POST, 'carregamento');
    $veiculo = filter_input(INPUT_POST, 'veiculo');
    $rota = filter_input(INPUT_POST, 'rota');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $entregas = filter_input(INPUT_POST, 'entregas');
    $situacao = "Pendente";
    $usuario = $_SESSION['idUsuario'];

    $db->beginTransaction();

    try{
        //verificar se já existe o carregamento
        $sqlConsulta = $db->prepare("SELECT carregamento FROM fusion WHERE carregamento=:carregamento");
        $sqlConsulta->bindValue(':carregamento', $carregamento);
        $sqlConsulta->execute();
        if($sqlConsulta->rowCount()>0){
            $_SESSION['msg'] = 'Esse carregamento já está registrado!';
            $_SESSION['icon']='warning';
            header("Location: form-fusion.php");
            exit();
        }
        $sql = $db->prepare("INSERT INTO fusion (saida, carregamento, veiculo, rota, motorista, num_entregas, situacao, usuario, filial) VALUES (:saida, :carregamento, :veiculo, :rota, :motorista, :entregas, :situacao, :usuario, :filial)");
        $sql->bindValue(':saida', $saida);
        $sql->bindValue(':carregamento', $carregamento);
        $sql->bindValue(':veiculo', $veiculo);
        $sql->bindValue(':rota', $rota);
        $sql->bindValue(':motorista', $motorista);
        $sql->bindValue(':entregas', $entregas);
        $sql->bindValue(':situacao', $situacao);
        $sql->bindValue(':usuario', $usuario);
        $sql->bindValue(':filial', $filial);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Viagem Registrada com Sucesso';
        $_SESSION['icon']='success';
        
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Registrar Viagem';
        $_SESSION['icon']='error';
    }    

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
}
header("Location: fusion.php");
exit();
?>