<?php
include '../conexao.php';
session_start();

$assinatura = $_POST['assinatura'];
$carregamento = filter_input(INPUT_POST,'carregamento');
$idtroca = $_POST['idtroca'];
$situacao = $_POST['situacao'];
$falta = $_POST['falta'];

// Verifique se a assinatura foi enviada
if (isset($_POST['assinatura'])) {
    // Recupere a assinatura e decodifique-a
    $signature = $_POST['assinatura'];
    $signature = str_replace('data:image/png;base64,', '', $signature);
    $signature = str_replace(' ', '+', $signature);
    $data = base64_decode($signature);

    $db->beginTransaction();

    try{

        for($i=0;$i<count($idtroca);$i++){
            $qtd = $falta[$i]?$falta[$i]:0;
            
            $sql = $db->prepare("UPDATE trocas SET situacao=:situacao,  qtd_falta=:qtd WHERE idtroca = :troca");
            $sql->bindValue(':situacao', $situacao[$i]);
            $sql->bindValue(':qtd', $qtd);
            $sql->bindValue(':troca', $idtroca[$i]);
            $sql->execute();
        }
       
        // Salve a imagem no servidor (ou faça o que desejar com ela)
        $file = 'assinaturas/'.$carregamento.'.png';
        file_put_contents($file, $data);
        
        // Retorne uma resposta de sucesso
        $db->commit();
        $_SESSION['msg'] = 'Trocas Conferidas e Assinada com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Conferir e Assinar Trocas';
        $_SESSION['icon']='error';

    }
    
} else {
    $_SESSION['msg'] = ' Assinatura não encontrada!';
    $_SESSION['icon']='error';
}

header("Location: trocas.php");
exit();
?>