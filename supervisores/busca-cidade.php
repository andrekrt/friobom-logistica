<?php

session_start();
require("../conexao-on.php");
require_once("../conexao-oracle.php");

$idModudulo = 14;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $cliente = filter_input(INPUT_GET,'cod');

    //echo "$dataCadastro<br>$nFogo<br>$medida<br>$calibMax<br>$marca<br>$modelo<br>$serie<br>$vida<br>$posicao<br>$situacao<br>$localizacao<br>$veiculo<br>$kmVeiculo<br>$suco01<br>$suco02<br>$suco03<br>$suco04<br>$ativoVeiculo<br>$uso";

    $sql = $dbora->prepare("SELECT ENDERENT, BAIRROENT, MUNICENT from FRIOBOM.pcclient WHERE CODCLI =:cliente");
    $sql->bindValue(':cliente', $cliente);
    
    
    if($sql->execute()){
        $dados = $sql->fetch(PDO::FETCH_ASSOC);
        print_r($dados);
        echo count($dados);
    }else{
        echo "Encontrou nada!!!";
        
    }

}else{

}

?>