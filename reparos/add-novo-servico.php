<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false){
    if(isset($_POST['novo-servico'])){
        $idSolicPrincipal = filter_input(INPUT_POST, 'id');
        $servicoAdicional = filter_input(INPUT_POST, 'servico02');
        $descricao = filter_input(INPUT_POST, 'descricao02');

        $sql = $db->query("INSERT INTO solicitacoes02 (servico, descricao, idSocPrinc) VALUES('$servicoAdicional', '$descricao', $idSolicPrincipal)");

        $atualiza = $db->query("UPDATE solicitacoes SET statusSolic = 'Nova análise' WHERE id = $idSolicPrincipal ");

        if($sql){
            echo "<script>alert('Atualizado com Sucesso!');</script>";
            echo "<script>window.location.href='solicitacoes.php'</script>";
        }else{
            echo "Erro";
        }


    }else{
        header("Location:index.php");
    }

           
    
}else{
    header("Location:login.php");
}

?>