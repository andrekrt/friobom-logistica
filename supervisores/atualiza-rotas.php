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

    $dataSaida = filter_input(INPUT_POST, 'dataSaida');
    $dataChegada = filter_input(INPUT_POST, 'dataChegada');
    $supervisor = filter_input(INPUT_POST, 'supervisor', FILTER_VALIDATE_INT);
    $velMax = filter_input(INPUT_POST, 'velMax');
    $visitas = filter_input(INPUT_POST, 'visitas', FILTER_VALIDATE_INT);
    $cidades = filter_input(INPUT_POST, 'cidades');
    $rca1 = filter_input(INPUT_POST, 'rca1');
    $rca2 = filter_input(INPUT_POST, 'rca2');
    $obs = filter_input(INPUT_POST, 'obs');
    $horaAlmoco = str_replace(",",".", filter_input(INPUT_POST, 'horaAlmoco')) ;
    $idRota = filter_input(INPUT_POST, 'id');
    $kmRodado = filter_input(INPUT_POST, 'kmRodado');
    $usuario = $_SESSION['idUsuario'];

    $db->beginTransaction();

    try{
        $sql = $db->prepare("UPDATE rotas_supervisores SET saida=:saida, supervisor=:supervisor, chegada=:chegada, velocidade_max=:velocidade, rca01=:rca01, rca02=:rca02, obs=:obs, cidades=:cidades, qtd_visitas=:visitas, hora_almoco=:horaAlmoco, km_rodado=:km, usuario=:usuario WHERE idrotas=:idrotas ");
        $sql->bindValue(':saida', $dataSaida);
        $sql->bindValue(':supervisor', $supervisor);
        $sql->bindValue(':chegada', $dataChegada);
        $sql->bindValue(':velocidade', $velMax);
        $sql->bindValue(':rca01', $rca1);
        $sql->bindValue(':rca02', $rca2);
        $sql->bindValue(':obs', $obs);
        $sql->bindValue(':cidades', $cidades);
        $sql->bindValue(':visitas', $visitas);
        $sql->bindValue(':horaAlmoco', $horaAlmoco);
        $sql->bindValue(':usuario', $usuario);
        $sql->bindValue(':km',$kmRodado);
        $sql->bindValue(':idrotas', $idRota);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Rota Atualizada com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Atualizar Rota';
        $_SESSION['icon']='error';
    }

}else{;
    $_SESSION['msg'] = 'Acesso não permitido!';
    $_SESSION['icon']='warning';
}
header("Location: rotas-supervisores.php");
exit();
?>