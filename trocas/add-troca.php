<?php
include '../conexao.php';
session_start();

$carregamento = filter_input(INPUT_GET,'carregamento');
$idtroca = $_GET['troca'];
$situacao = $_GET['situacao'];
$falta = $_GET['falta'];
$ausencia = $_GET['ausencia'];

$db->beginTransaction();

try{

    for($i=0;$i<count($idtroca);$i++){
        $qtd = $falta[$idtroca[$i]]?$falta[$idtroca[$i]]:0;
        $ausente = $ausencia==='on'?1:0;
       
        $sql = $db->prepare("UPDATE trocas SET situacao=:situacao,  qtd_falta=:qtd, motorista_ausente=:ausente WHERE idtroca = :troca");
        $sql->bindValue(':situacao', $situacao[$i]);
        $sql->bindValue(':qtd', $qtd);
        $sql->bindValue(':ausente', $ausente);
        $sql->bindValue(':troca', $idtroca[$i]);
        $sql->execute();
    }
    
    // Retorne uma resposta de sucesso
    $db->commit();

    $_SESSION['msg'] = 'Trocas Conferidas com Sucesso';
    $_SESSION['icon']='success';

}catch(Exception $e){
    $db->rollBack();
    $_SESSION['msg'] = 'Erro ao Conferir Trocas';
    $_SESSION['icon']='error';

}
    
header("Location: trocas.php");
exit();
?>