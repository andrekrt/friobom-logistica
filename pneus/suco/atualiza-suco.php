<?php

session_start();
require("../../conexao.php");

$idModudulo = 12;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idSuco = filter_input(INPUT_POST, 'idsuco');
    $idpneu = filter_input(INPUT_POST, 'pneu');
    
    $consultaPneu = $db->prepare("SELECT * FROM pneus WHERE idpneus = :idpneu");
    $consultaPneu->bindValue(':idpneu', $idpneu);
    $consultaPneu->execute();
    $pneu = $consultaPneu->fetch();

    $kmVeiculo = filter_input(INPUT_POST, 'kmVeiculo');
    $carcaca = filter_input(INPUT_POST, 'carcaca');
    $suco01 = filter_input(INPUT_POST, 'suco01');
    $suco02 = filter_input(INPUT_POST, 'suco02');
    $suco03 = filter_input(INPUT_POST,'suco03');
    $suco04 = filter_input(INPUT_POST, 'suco04');
    $calibragem = filter_input(INPUT_POST, 'calibragemAtual');    
    $kmPneu = $kmVeiculo-$pneu['km_inicial'];
    $veiculo = filter_input(INPUT_POST, 'veiculo');

    $db->beginTransaction();
    
    try{
        $sql = $db->prepare("UPDATE sucos SET km_veiculo = :kmVeiculo, km_pneu = :kmPneu, carcaca = :carcaca, suco01 = :suco01, suco02 = :suco02, suco03 = :suco03, suco04 = :suco04, calibragem = :calibragem, pneus_idpneus = :pneu, veiculo=:veiculo WHERE idsucos = :idSuco");
        $sql->bindValue(':kmVeiculo', $kmVeiculo);
        $sql->bindValue(':kmPneu', $kmPneu);
        $sql->bindValue(':carcaca', $carcaca);
        $sql->bindValue(':suco01', $suco01);
        $sql->bindValue(':suco02', $suco02);
        $sql->bindValue(':suco03', $suco03);
        $sql->bindValue(':suco04', $suco04);
        $sql->bindValue(':calibragem', $calibragem);
        $sql->bindValue(':pneu', $idpneu);
        $sql->bindValue(':idSuco', $idSuco);
        $sql->bindValue(':veiculo', $veiculo);
        $sql->execute();

        //km rodado no rodizio
        $rodizios = $db->prepare("SELECT SUM(km_rodado_veiculo_anterior) as kmRodado FROM rodizio_pneu WHERE pneu = :pneu");
        $rodizios->bindValue(':pneu', $idpneu);
        $rodizios->execute();
        $rodizios=$rodizios->fetch();
        $totalKmRodado = $rodizios['kmRodado'];

        //pegar todos os veiculos do rodizio
        $veiculosRodizios = $db->prepare("SELECT novo_veiculo FROM rodizio_pneu WHERE pneu = :pneu ORDER BY idrodizio DESC LIMIT 1");
        $veiculosRodizios->bindValue(':pneu', $idpneu);
        $veiculosRodizios->execute();
        $veiculoRodizio = $veiculosRodizios->fetch();
        $novoVeiculo = $veiculoRodizio['novo_veiculo'];

        // km rodado dos sucos que ainda não fizeram rodizio
        $sucos = $db->prepare("SELECT SUM(km_pneu) as kmPneu FROM sucos WHERE veiculo = :veiculo");
        $sucos->bindValue(':veiculo', $novoVeiculo);
        $sucos->execute();
        $sucos = $sucos->fetch();
        $kmRodadoSuco = $sucos['kmPneu'];

        $kmGeral = $kmRodadoSuco+$totalKmRodado;
        
        $atualizaPneu = $db->prepare("UPDATE pneus SET km_rodado = :kmRodadoTotal WHERE idpneus = :idpneu");
        $atualizaPneu->bindValue(':kmRodadoTotal', $kmGeral);
        $atualizaPneu->bindValue(':idpneu', $idpneu);
        $atualizaPneu->execute();

        $db->commit();

        $_SESSION['msg'] = 'Suco Atualizado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Atualizar Suco';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
}
header("Location: sucos.php");
exit();
?>