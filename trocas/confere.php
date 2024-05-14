<?php

use Mpdf\Tag\Header;

session_start();
require("../conexao.php");

$idModudulo = 20;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result > 0) ) {
    $carregamento = filter_input(INPUT_POST, 'carregamento');
    $trocas = $_POST['idtroca'];
    $falta = $_POST['falta'];  
    $ausencia = filter_input(INPUT_POST, 'ausencia');
    $situacao = array();

    try{
        foreach($trocas as $troca){
            $qtd = $falta[$troca]?$falta[$troca]:0;
        
            if (isset($_POST['situacao'][$troca]) && $_POST['situacao'][$troca] == '1') {
               $situacao[] = "Conferido e OK";
            
            } elseif($_POST['falta'][$troca]>0) {
               $situacao[] = "Faltando Produto";
            
            }
        }

        $query_string = http_build_query(array(
            'troca' => $trocas,
            'situacao' => $situacao,
            'falta' => $falta,
            'carregamento' => $carregamento,
            'ausencia' => $ausencia
        ));

        if($ausencia==='on'){
            header("Location:add-troca.php?".$query_string);
            exit();
        }
     
        header("Location:assinatura.php?".$query_string);
        exit();
        // var_dump($query_string);

    }catch(Exception $e){
        echo "Erro: " . $e;

    }   

}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='trocas.php'</script>"; 
}

?>