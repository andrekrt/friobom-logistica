<?php

session_start();
require("../conexao.php");

$idModudulo = 6;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    //valores padrões sem anexo
    $advertenciaPadrao = filter_input(INPUT_POST, 'advertenciaPadrao');
    $ocorrenciaPadrao = filter_input(INPUT_POST, 'ocorrenciaPadrao');
    $laudoaPadrao = filter_input(INPUT_POST, 'laudoPadrao');

    $idOcorrencia = filter_input(INPUT_POST, 'idOcorrencia');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $data = filter_input(INPUT_POST, 'data');
    $tipoOcorrencia = filter_input(INPUT_POST, 'tipo');
    $advertencia = filter_input(INPUT_POST, 'advertencia');
    $laudo = filter_input(INPUT_POST, 'laudo');
    $descricaoCusto = filter_input(INPUT_POST, 'descricaoCusto');
    $vlTotal = str_replace(",",".",filter_input(INPUT_POST,'vlTotal')) ;
    $situacao = filter_input(INPUT_POST,'situacao');   
    $placa = filter_input(INPUT_POST, 'placa');
    $carregamento = filter_input(INPUT_POST, 'carregamento');
    $descricaoProblema = filter_input(INPUT_POST, 'descricaoProblema');

    //anexos
    $anexoOcorrencia = $_FILES['anexoOcorrencia'];
    $anexoOcorrencia_nome = $anexoOcorrencia['name'][0]?$anexoOcorrencia['name'][0]:$ocorrenciaPadrao;
    $anexoAdvertencia = $_FILES['anexoAdvertencia'];
    $anexoAdvertencia_nome = $anexoAdvertencia['name'][0]?$anexoAdvertencia['name'][0]:$advertenciaPadrao;
    $anexoLaudo = $_FILES['anexoLaudo'];
    $anexoLaudo_nome = $anexoLaudo['name'][0]?$anexoLaudo['name'][0]:$laudoaPadrao;

    $db->beginTransaction();

    try{
        $atualiza = $db->prepare("UPDATE ocorrencias SET cod_interno_motorista = :motorista, data_ocorrencia = :dataOcorrencia, tipo_ocorrencia = :tipo, advertencia = :advertencia, laudo = :laudo, descricao_custos = :descricaoCusto, vl_total_custos = :vlTotal, situacao = :situacao, img_ocorrencia = :anexoOcorrencia, img_advertencia = :anexoAdvertencia, img_laudo = :anexoLaudo,  placa = :placa, num_carregamento = :carregamento, descricao_problema = :descricaoProblema  WHERE idocorrencia = :idOcorrencia");
        $atualiza->bindValue(':motorista', $motorista);
        $atualiza->bindValue(':dataOcorrencia', $data);
        $atualiza->bindValue(':tipo', $tipoOcorrencia);
        $atualiza->bindValue(':advertencia', $advertencia);
        $atualiza->bindValue(':laudo', $laudo);
        $atualiza->bindValue(':descricaoCusto', $descricaoCusto);
        $atualiza->bindValue(':vlTotal', $vlTotal);
        $atualiza->bindValue(':situacao', $situacao);
        $atualiza->bindValue(':idOcorrencia', $idOcorrencia);
        $atualiza->bindValue(':anexoOcorrencia', $anexoOcorrencia_nome);
        $atualiza->bindValue(':anexoAdvertencia', $anexoAdvertencia_nome);
        $atualiza->bindValue(':anexoLaudo', $anexoLaudo_nome);
        $atualiza->bindValue(':placa', $placa);
        $atualiza->bindValue(':carregamento', $carregamento);
        $atualiza->bindValue(':descricaoProblema', $descricaoProblema);
        $atualiza->execute();

        //uploads de ocorrencias
        for($i=0;$i<count($anexoOcorrencia['name']);$i++){
            $destinoOcorrencia = "uploads/". $idOcorrencia . "/ocorrencias". "/".  $anexoOcorrencia['name'][$i];
            move_uploaded_file($anexoOcorrencia['tmp_name'][$i], $destinoOcorrencia);
        }

        //uploads de advertencias
        for($i=0;$i<count($anexoAdvertencia['name']);$i++){
            $destinoAdvertencia = "uploads/". $idOcorrencia . "/advertencias". "/".  $anexoAdvertencia['name'][$i];
            move_uploaded_file($anexoAdvertencia['tmp_name'][$i], $destinoAdvertencia);
        }

        //uploads de laudos
        for($i=0;$i<count($anexoLaudo['name']);$i++){
            $destinoLaudo = "uploads/". $idOcorrencia . "/laudos". "/".  $anexoLaudo['name'][$i];
            move_uploaded_file($anexoLaudo['tmp_name'][$i], $destinoLaudo);
        }

        $db->commit();

        $_SESSION['msg'] = 'Ocorrência Atualizado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();

        $_SESSION['msg'] = 'Erro ao Atualizar Ocorrência';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}

header("Location: ocorrencias.php");
exit();
?>