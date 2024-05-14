<?php

session_start();
require("../../conexao.php");
include ('../funcao.php');

$idModudulo = 12;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $filial = $_SESSION['filial'];
    $dataMedicao = date("Y-m-d H:i:s");
    $idpneu = $_POST['idpneu'];
    $fogo = $_POST['fogo'];
    $kmInicialPneu = $_POST['kmPneu'];
    $kmVeiculo = $_POST['kmVeiculo'];
    $carcaca = $_POST['carcaca'];
    $suco01 = $_POST['suco01'];
    $suco02 = $_POST['suco02'];
    $suco03 = $_POST['suco03'];
    $suco04 = $_POST['suco04'];
    $calibragem = $_POST['calibragem'];
    $usuario = $_SESSION['idUsuario']; 
    $vida = $_POST['vida'];
    $kmRodado = $_POST['kmRodado'];
    $veiculo = $_POST['veiculo'];

    $db->beginTransaction();

    try{
        //verificar se existe registro desse veiculo no dia atual
        $sqlCont=$db->prepare("SELECT * FROM sucos WHERE veiculo=:veiculo AND DATE(data_medicao)=:dataMedida");
        $sqlCont->bindValue(':veiculo', $veiculo);
        $sqlCont->bindValue(':dataMedida', date('Y-m-d', strtotime($dataMedicao)) );
        $sqlCont->execute();
        $qtdMedidas = $sqlCont->rowCount();

        if($qtdMedidas>0){
            $_SESSION['msg'] = 'Já existe registro desse veículo hoje!';
            $_SESSION['icon']='warning';
            header("Location: form-suco.php");
            exit();
        }

        // verificar se é a primeira medida de suco desse veículo no mês
        $registros = $db->prepare("SELECT * FROM sucos WHERE veiculo=:veiculo AND MONTH(data_medicao)=MONTH(CURRENT_DATE())");
        $registros->bindValue(':veiculo', $veiculo);
        $registros->execute();
        $contRegistros = $registros->rowCount();

        for($i=0; $i<count($idpneu);$i++){       
            //pegar o km do veículo da ultima medidade de suco
            $sucoMax = $db->prepare("SELECT MAX(idsucos) as idsuco, km_veiculo FROM sucos WHERE pneus_idpneus=:idpneu");
            $sucoMax->bindValue(':idpneu', $idpneu[$i]);
            $sucoMax->execute();
            $valores = $sucoMax->fetch();
            $kmVeiculoUltimaMedida= $valores['km_veiculo']; 

            if($contRegistros<1){
                $kmPneu = ($kmVeiculo-$kmInicialPneu[$i]);
            }else{
                $kmPneu = ($kmVeiculo-$kmVeiculoUltimaMedida);
            }
            
            $sql = $db->prepare("INSERT INTO sucos (data_medicao, km_veiculo, km_pneu, carcaca, vida, suco01, suco02, suco03, suco04, calibragem, pneus_idpneus, veiculo, usuario, filial) VALUES (:dataMedida, :kmVeiculo, :kmPneu, :carcaca, :vida, :suco01, :suco02, :suco03, :suco04, :calibragem, :pneu, :veiculo, :usuario, :filial)");
            $sql->bindValue(':dataMedida', $dataMedicao);
            $sql->bindValue(':kmVeiculo', $kmVeiculo);
            $sql->bindValue(':kmPneu', $kmPneu);
            $sql->bindValue(':carcaca', $carcaca[$i]);
            $sql->bindValue(':vida', $vida[$i]);
            $sql->bindValue(':suco01', $suco01[$i]);
            $sql->bindValue(':suco02', $suco02[$i]);
            $sql->bindValue(':suco03', $suco03[$i]);
            $sql->bindValue(':suco04', $suco04[$i]);
            $sql->bindValue(':calibragem', $calibragem[$i]);
            $sql->bindValue(':pneu', $idpneu[$i]);
            $sql->bindValue(':veiculo',$veiculo);
            $sql->bindValue(':usuario', $usuario);
            $sql->bindValue(':filial', $filial);
            $sql->execute();

            $kmRodadoTotal = $kmPneu+$kmRodado[$i];
            addExtrato($idpneu[$i], "Suco", $kmRodadoTotal, $veiculo, $kmVeiculo);
            $atualizaPneu = $db->prepare("UPDATE pneus SET km_rodado = :kmRodadoTotal WHERE idpneus = :idpneu");
            $atualizaPneu->bindValue(':kmRodadoTotal', $kmRodadoTotal);
            $atualizaPneu->bindValue(':idpneu', $idpneu[$i]);
            $atualizaPneu->execute();
        }

        $db->commit();

        $_SESSION['msg'] = 'Suco Registrado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Registrar Suco';
        $_SESSION['icon']='error';
    }
    

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
}
header("Location: sucos.php");
exit();
?>