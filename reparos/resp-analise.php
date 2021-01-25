<?php

session_start();
require("../conexao.php");


if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false){
    $id=$_SESSION['idUsuario'];
    
    $idSolic = filter_input(INPUT_POST, 'idSolic');
    $servico = filter_input(INPUT_POST, 'servico');
    $descricao =filter_input(INPUT_POST, 'descricao');
    $placa = filter_input(INPUT_POST, 'placa');
    $obs = ucfirst(filter_input(INPUT_POST, 'obs'));
    $statusSolic = filter_input(INPUT_POST, 'status');
    $valor = filter_input(INPUT_POST, 'valor');
    $dataAprovacao ="00-00-00";
    if($statusSolic=="Aprovado"){
        $dataAprovacao = date("Y-m-d");
    }
    
    $atualiza = $db->query("UPDATE solicitacoes SET obs = '$obs', statusSolic = '$statusSolic', valor = '$valor', dataAprov = '$dataAprovacao' WHERE id = $idSolic ");
    if(isset($_POST['servicoAdicional'])){
        $novoValor = filter_input(INPUT_POST, 'valorNovo');
        $novaSolic = $db->query("UPDATE solicitacoes02 SET valor = '$novoValor' ");
    }
    if($atualiza){
        echo "<script>alert('Atualizado com Sucesso!');</script>";
        echo "<script>window.location.href='solicitacoes.php'</script>";
    }else{
        echo "erro";
    }
    

}else{
    header("Location:login.php");
}
?>