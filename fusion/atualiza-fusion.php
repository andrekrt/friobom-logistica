<?php

session_start();
require("../conexao.php");
include './funcoes.php';

$idModudulo = 16;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $id = filter_input(INPUT_POST, 'id');
    $saida = filter_input(INPUT_POST, 'saida');
    $termino = filter_input(INPUT_POST, 'termino');
    $chegada = filter_input(INPUT_POST, 'chegada');
    $carregamento = filter_input(INPUT_POST, 'carregamento');
    $veiculo = filter_input(INPUT_POST, 'veiculo');
    $rota = filter_input(INPUT_POST, 'rota');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $numEntregas = filter_input(INPUT_POST, 'numEntregas');
    $entregasFeitas = filter_input(INPUT_POST, 'entregasFeita');
    $erros = filter_input(INPUT_POST, 'erros');
    $numDev = filter_input(INPUT_POST, 'numDev');
    $devolucao = filter_input(INPUT_POST, 'devolucao');
    $diasRota = filter_input(INPUT_POST, 'diasRota');
    $velMax = filter_input(INPUT_POST, 'velMax');
    $situacao = filter_input(INPUT_POST, 'situacao');

    $db->beginTransaction();

    try{
        $entregasLiq = $entregasFeitas-$numDev;
        $usoFusion = $numEntregas==$entregasFeitas?1:0;
        $checklist=checklist($carregamento);
        if(!is_numeric($checklist)){

            $_SESSION['msg'] = $checklist;
            $_SESSION['icon']='warning';
            header("Location: fusion.php");
            exit();
        }

        $consumo = mediaConsumo($carregamento);
        if(!is_numeric($consumo)){

            $_SESSION['msg'] = $consumo;
            $_SESSION['icon']='warning';
            header("Location: fusion.php");
            exit();
        }

        $premiopossivel = $entregasLiq*1;

        if($usoFusion==0){
            $premioReal = 0;
        }else{
            $premioReal= ($usoFusion*0.5+$checklist*0.1+$consumo*0.1+$diasRota*0.1+$devolucao*0.1+$velMax*0.1)* $entregasLiq;
        }
    
        $percPremio = ($premioReal/$premiopossivel);

        $sql = $db->prepare("UPDATE fusion SET saida = :saida, termino_rota = :termino_rota, chegada_empresa = :chegada_empresa, carregamento = :carregamento, veiculo=:veiculo, motorista =:motorista, rota=:rota, num_entregas=:numEntregas, entregas_feitas=:entregas, erros_fusion=:erros, num_dev=:numDev, entregas_liq=:entregasLiq, uso_fusion=:uso, checklist=:checklist, media_km=:media, devolucao=:devolucao, dias_rota=:diasRota, vel_max=:velMax, premio_possivel=:premioPossivel, premio_real=:premio_ganho, premio_alcancado=:percPremio, situacao = :situacao WHERE idfusion = :id");
        $sql->bindValue(':saida', $saida);
        $sql->bindValue(':termino_rota', $termino);
        $sql->bindValue(':chegada_empresa', $chegada);
        $sql->bindValue(':carregamento', $carregamento);
        $sql->bindValue(':veiculo', $veiculo);
        $sql->bindValue(':motorista', $motorista);
        $sql->bindValue(':rota', $rota);
        $sql->bindValue(':numEntregas', $numEntregas);
        $sql->bindValue(':entregas', $entregasFeitas);
        $sql->bindValue(':erros', $erros);
        $sql->bindValue(':numDev', $numDev);
        $sql->bindValue(':entregasLiq', $entregasLiq);
        $sql->bindValue(':uso', $usoFusion);
        $sql->bindValue(':checklist', $checklist);
        $sql->bindValue(':media', $consumo);
        $sql->bindValue(':devolucao', $devolucao);
        $sql->bindValue(':diasRota', $diasRota);
        $sql->bindValue(':velMax', $velMax);
        $sql->bindValue(':premioPossivel', $premiopossivel);
        $sql->bindValue(':premio_ganho', $premioReal);
        $sql->bindValue(':percPremio', $percPremio);
        $sql->bindValue(':situacao', $situacao);
        $sql->bindValue(':id', $id);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Viagem Atualizada com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Atualizar Viagem';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
}
header("Location: fusion.php");
exit();
?>