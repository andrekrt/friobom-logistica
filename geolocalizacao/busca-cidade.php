<?php

session_start();
require("../conexao-on.php");
require_once("../conexao-oracle.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

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