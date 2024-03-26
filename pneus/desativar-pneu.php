<?php

session_start();
require("../conexao.php");

$idModudulo = 12;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idPneu = filter_input(INPUT_POST, 'idpneu');
    $consultaPneu = $db->prepare("SELECT * FROM pneus WHERE idpneus = :idpneu");
    $consultaPneu->bindValue(':idpneu', $idPneu);
    $consultaPneu->execute();
    $dados = $consultaPneu->fetch();
    $kmInicial = $dados['km_inicial'];
    $kmFinal = filter_input(INPUT_POST, 'kmFinal');
    $kmRodado = $dados['km_rodado']+($kmFinal-$kmInicial);
    $motivo = filter_input(INPUT_POST, 'motivo');
    $uso = 0;

    $db->beginTransaction();

    try{
        $sql = $db->prepare("UPDATE pneus SET km_final = :kmFinal, km_rodado = :kmRodado, uso = :uso, motivo_descarte = :motivo, veiculo=:veiculo, localizacao=:localizacao WHERE idpneus = :idPneu");
        $sql->bindValue(':kmFinal', $kmFinal);
        $sql->bindValue(':kmRodado', $kmRodado);
        $sql->bindValue(':uso', $uso);
        $sql->bindValue(':motivo', $motivo);
        $sql->bindValue(':idPneu', $idPneu);
        $sql->bindValue(':veiculo', '00000');
        $sql->bindValue(':localizacao', 'Descartado');
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Pneu Descartado com Sucesso';
        $_SESSION['icon']='success';
        
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Descartar Pneu';
        $_SESSION['icon']='error';
    }

    
    header("Location: pneus.php");
    exit();   

}else{

}

?>