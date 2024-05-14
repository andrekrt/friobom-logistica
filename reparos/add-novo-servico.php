<?php

session_start();
require("../conexao.php");

$idModudulo = 9;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    if(isset($_POST['novo-servico'])){
        $idSolicPrincipal = filter_input(INPUT_POST, 'id');
        $servicoAdicional = filter_input(INPUT_POST, 'servico02');
        $descricao = filter_input(INPUT_POST, 'descricao02');
        $filial = $_SESSION['filial'];

        $sql = $db->query("INSERT INTO solicitacoes02 (servico, descricao, idSocPrinc, :filial) VALUES('$servicoAdicional', '$descricao', $idSolicPrincipal, '$filial')");

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