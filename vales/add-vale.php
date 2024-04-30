<?php

session_start();
require("../conexao.php");
date_default_timezone_set('America/Sao_Paulo');


$idModudulo = 19;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    
    $motorista = filter_input(INPUT_POST,'motorista');
    $rota = filter_input(INPUT_POST, 'rota');
    $valor = filter_input(INPUT_POST, 'valor');
    $valor = str_replace(".","",$valor);
    $valorFormat=str_replace(",",".",$valor);
    $dataAtual = date('Y-m-d');
    $usuario = $_SESSION['idUsuario'];

    // Recupere a assinatura e decodifique-a
    $signature = $_POST['assinatura'];
    $signature = str_replace('data:image/png;base64,', '', $signature);
    $signature = str_replace(' ', '+', $signature);
    $data = base64_decode($signature);

    $db->beginTransaction();

    try{

        $inserir = $db->prepare("INSERT INTO vales (data_lancamento, motorista, rota, valor, usuario) VALUES (:data, :motorista, :rota, :valor,:usuario)");
        $inserir->bindValue(':motorista', $motorista);
        $inserir->bindValue(':rota', $rota);
        $inserir->bindValue(':valor', $valorFormat);   
        $inserir->bindValue(':usuario', $usuario);   
        $inserir->bindValue(':data',$dataAtual);
        $inserir->execute();

        $ultimoId = $db->lastInsertId();

        // Salve a imagem no servidor (ou faça o que desejar com ela)
        $file = 'assinaturas/'.$ultimoId.'.png';
        file_put_contents($file, $data);

        $db->commit();

        $_SESSION['msg'] = 'Vale Lançado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Lançar Vale';
        $_SESSION['icon']='error';

    }  

    header("Location: vales.php");
    exit();


}

?>