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

    $newToken = filter_input(INPUT_POST, 'token');
    $dataAtual = filter_input(INPUT_POST, 'data');
    $placa = filter_input(INPUT_POST, 'veiculo');
    $problema = filter_input(INPUT_POST, 'problema');
    $localReparo = filter_input(INPUT_POST, 'localReparo');
    $frete = filter_input(INPUT_POST, 'frete');
    $situacao = "Em análise";
    $usuario = $_SESSION['idUsuario'];

    $peca = $_POST['peca'];
    $qtd = str_replace(",",".",$_POST['qtd']) ;
    $valorUnit = str_replace(",", ".",$_POST['vlUnit'] ) ;
    $desconto = str_replace(",", ".", $_POST['desconto']);
    $imagem = $_FILES['imagem']['name']?$_FILES['imagem']['name']:null;

    for($i=0; $i<count($peca); $i++){

        $valorTotal = ($valorUnit[$i]-$desconto[$i])*$qtd[$i];

        $sql = $db->prepare("INSERT INTO solicitacoes_new (token, data_atual, placa, problema, local_reparo, imagem, peca_servico, qtd, vl_unit, desconto, vl_total, frete, situacao, usuario) VALUES (:token, :dataAtual, :placa, :problema, :localReparo, :imagem, :peca, :qtd, :vlUnit, :desconto, :vlTotal, :frete, :situacao, :usuario)");
        $sql->bindValue(':token', $newToken);
        $sql->bindValue(':dataAtual', $dataAtual);
        $sql->bindValue(':placa', $placa);
        $sql->bindValue(':problema', $problema);
        $sql->bindValue(':localReparo', $localReparo);
        $sql->bindValue(':imagem', $imagem[$i]);
        $sql->bindValue(':peca', $peca[$i]);
        $sql->bindValue(':qtd', $qtd[$i]);
        $sql->bindValue(':vlUnit', $valorUnit[$i]);
        $sql->bindValue(':desconto', $desconto[$i]);
        $sql->bindValue(':vlTotal', $valorTotal);
        $sql->bindValue(':frete', $frete);
        $sql->bindValue(':situacao', $situacao);
        $sql->bindValue(':usuario', $usuario);
        
        if($sql->execute()){
            $atualiaza = $db->prepare("UPDATE solicitacoes_new SET situacao = :situacao WHERE token=:token");
            $atualiaza->bindValue(':token', $newToken);
            $atualiaza->bindValue(':situacao', $situacao);
            if($atualiaza->execute()){
                $pasta = 'uploads/';
                $mover = move_uploaded_file($_FILES['imagem']['tmp_name'][$i],$pasta.$imagem[$i]);

                echo "<script> alert('Peça Adicionada!')</script>";
                echo "<script> window.location.href='solicitacoes.php' </script>"; 
            }else{
                print_r($sql->errorInfo());
            }
            
        }else{
            print_r($sql->errorInfo());
        }

    }

}else{

}

?>