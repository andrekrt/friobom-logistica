<?php

session_start();
require("../conexao.php");
$idModudulo = 8;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    
    $idUsuario = $_SESSION['idUsuario'];
    $carga = filter_input(INPUT_POST, 'carga');
    $qtd =filter_input(INPUT_POST, 'qtd');
    $situacao = "Saída";

    // verificar se já existe registro para essa carga
    $consulta = $db->prepare("SELECT * FROM caixas WHERE carregamento=:carregamento");
    $consulta->bindValue(':carregamento', $carga);
    $consulta->execute();
    $qtdCarga = $consulta->rowCount();
    if($qtdCarga>0){
        echo "<script>alert('Já existe registro nesse carregamento');</script>";
        echo "<script>window.location.href='caixas.php'</script>";  
    }else{
        // echo "$idUsuario<br>$carga<br>$qtd<br>$situacao";

        $inserir = $db->prepare("INSERT INTO caixas (carregamento, qtd_caixas, situacao, usuario) VALUES (:carregamento, :qtd, :situacao, :usuario)");
        $inserir->bindValue(':carregamento', $carga);
        $inserir->bindValue(':qtd', $qtd);
        $inserir->bindValue(':situacao', $situacao);
        $inserir->bindValue(':usuario', $idUsuario);

        if($inserir->execute()){
            echo "<script>alert('Saída de Caixa Registrada');</script>";
            echo "<script>window.location.href='caixas.php'</script>";    
            
        }else{
            print_r($inserir->errorInfo());
        }
    }

    
    

}

?>