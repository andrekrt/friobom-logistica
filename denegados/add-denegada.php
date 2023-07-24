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
    $consultaToken = $db->query("SELECT MAX(token) as token FROM denegadas");
    $token = $consultaToken->fetch();
    if(empty($token['token'])){
        $newToken = 0+1;
    }else{
        $newToken = $token['token']+1;
    }
    
    $idUsuario = $_SESSION['idUsuario'];
    $carga = filter_input(INPUT_POST, 'carga');
    $nf = $_POST['nf'];
    $situacao = "Aguardando Confirmação";

    // echo "$idUsuario<br>$carga<br>$situacao<br>$newToken<br>". count($nf);
    // print_r($nf);

    for($i=0;$i<count($nf);$i++){
        $inserir = $db->prepare("INSERT INTO denegadas (token, carga, nf, situacao, usuario) VALUES (:token, :carga, :nf, :situacao, :usuario)");
        $inserir->bindValue(':token', $newToken);
        $inserir->bindValue(':carga', $carga);
        $inserir->bindValue(':nf', $nf[$i]);
        $inserir->bindValue(':situacao', $situacao);
        $inserir->bindValue(':usuario', $idUsuario);

        if($inserir->execute()){
            echo "<script>alert('NF Denegada Registrada!');</script>";
            echo "<script>window.location.href='denegadas.php'</script>";    
            
        }else{
            print_r($inserir->errorInfo());
        }

    }

    

}

?>