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
    $filial = $_SESSION['filial'];
    $motorista = filter_input(INPUT_POST, 'motorista');
    $placa = filter_input(INPUT_POST, 'placa');
    $carregamento = filter_input(INPUT_POST, 'carregamento');
    $data = filter_input(INPUT_POST, 'data');
    $tipoOcorrencia = filter_input(INPUT_POST, 'tipo');
    $anexoOcorrencia = $_FILES['anexoOcorrencia'];
    $anexoOcorrencia_nome = $anexoOcorrencia['name'][0];
    $advertencia = filter_input(INPUT_POST, 'advertencia');
    $anexoAdvertencia = $_FILES['anexoAdvertencia'];
    $anexoAdvertencia_nome = $anexoAdvertencia['name'][0];
    $laudo = filter_input(INPUT_POST, 'laudo');
    $anexoLaudo = $_FILES['anexoLaudo'];
    $anexoLaudo_nome = $anexoLaudo['name'][0];
    $descricaoCusto = filter_input(INPUT_POST, 'descricaoCusto');
    $descricaoProblema = filter_input(INPUT_POST, 'descricaoProblema');
    $vlTotal = str_replace(",",".",filter_input(INPUT_POST, 'vlTotal')) ;
    $situacao = filter_input(INPUT_POST, 'situacao');
    $usuario = $_SESSION['idUsuario'];

    $db->beginTransaction();

    try{

        $inserir = $db->prepare("INSERT INTO ocorrencias (cod_interno_motorista, data_ocorrencia, placa, num_carregamento , tipo_ocorrencia, img_ocorrencia, advertencia, img_advertencia, laudo, img_laudo, descricao_custos, descricao_problema, vl_total_custos, situacao, usuario_lancou, filial) VALUES (:motorista, :dataOcorrencia, :placa, :carregamento, :tipo, :imgOcorrencia, :advertencia, :imgAdvertencia, :laudo, :imgLaudo, :descricaoCusto, :descricaoProblema, :vlTotal, :situacao, :usuario, :filial)");
        $inserir->bindValue(':motorista', $motorista);
        $inserir->bindValue(':dataOcorrencia', $data);
        $inserir->bindValue(':tipo', $tipoOcorrencia);
        $inserir->bindValue(':imgOcorrencia', $anexoOcorrencia_nome);
        $inserir->bindValue(':advertencia', $advertencia);
        $inserir->bindValue(':imgAdvertencia', $anexoAdvertencia_nome);
        $inserir->bindValue(':laudo', $laudo);
        $inserir->bindValue(':imgLaudo', $anexoLaudo_nome);
        $inserir->bindValue(':descricaoCusto', $descricaoCusto);
        $inserir->bindValue(':vlTotal', $vlTotal);
        $inserir->bindValue(':situacao', $situacao);
        $inserir->bindValue(':usuario', $usuario);
        $inserir->bindValue(':placa', $placa);
        $inserir->bindValue(':carregamento', $carregamento);
        $inserir->bindValue(':descricaoProblema', $descricaoProblema);
        $inserir->bindValue(':filial', $filial);
        $inserir->execute();

        $ultimoId = $db->lastInsertId();
        //criando pasta principal
        $diretorioPrincipal = "uploads/".$ultimoId;
        mkdir($diretorioPrincipal,0755);

        //pasta e upload das provas da ocorrencias
        $diretorioOcorrencias = $diretorioPrincipal."/ocorrencias";
        mkdir($diretorioOcorrencias,0755);
        for($i=0;$i<count($anexoOcorrencia['name']);$i++){
            $destinoOcorrencia =$diretorioOcorrencias."/".$anexoOcorrencia['name'][$i];
            move_uploaded_file($anexoOcorrencia['tmp_name'][$i], $destinoOcorrencia);
        }

        //pasta e upload das provas da advertencia
        $diretorioAdvertencia = $diretorioPrincipal."/advertencias";
        mkdir($diretorioAdvertencia,0755);
        for($i=0;$i<count($anexoAdvertencia['name']);$i++){
            $destinoAdvertencia =$diretorioAdvertencia."/".$anexoAdvertencia['name'][$i];
            move_uploaded_file($anexoAdvertencia['tmp_name'][$i], $destinoAdvertencia);
        }

        //pasta e upload das provas da laudos
        $diretorioLaudo= $diretorioPrincipal."/laudos";
        mkdir($diretorioLaudo,0755);
        for($i=0;$i<count($anexoLaudo['name']);$i++){
            $destinoLaudo =$diretorioLaudo."/".$anexoLaudo['name'][$i];
            move_uploaded_file($anexoLaudo['tmp_name'][$i], $destinoLaudo);
        }

        $db->commit();

        $_SESSION['msg'] = 'Ocorrência Registrada com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Registrar Ocorrência';
        $_SESSION['icon']='error';
    }

    header("Location: form-ocorrencias.php");
    exit();

    
}

?>