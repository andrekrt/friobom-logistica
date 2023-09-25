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

    $descricao = trim(filter_input(INPUT_POST, 'descricao'));
    $categoria = filter_input(INPUT_POST, 'categoria');
    $medida = filter_input(INPUT_POST, 'medida');
    $estoqueMinimo = trim(str_replace(",", ".", filter_input(INPUT_POST, 'estoqueMin')));
    $situacao = 'Solicitar';

    $verificaPeca = $db->prepare("SELECT * FROM peca_reparo WHERE descricao = :descricao");
    $verificaPeca->bindValue(':descricao', $descricao);
    $verificaPeca->execute();
    if($verificaPeca->rowCount()>0){
        echo "<script>alert('Essa Peça já está cadastrada no Estoque!');</script>";
        echo "<script>window.location.href='pecas.php'</script>";
    }else{
        $inserir = $db->prepare("INSERT INTO peca_reparo (descricao, categoria, un_medida, estoque_minimo, situacao, usuario) VALUES (:descricao, :categoria, :medida, :estoque, :situacao, :usuario)" );
        $inserir->bindValue(':descricao', $descricao);
        $inserir->bindValue(':categoria', $categoria);
        $inserir->bindValue(':medida', $medida);
        $inserir->bindValue(':estoque', $estoqueMinimo);
        $inserir->bindValue(':situacao', $situacao);
        $inserir->bindValue(':usuario', $idUsuario);
        if($inserir->execute()){
            echo "<script> alert('Cadastrado com Sucesso!')</script>";
            echo "<script> window.location.href='pecas.php' </script>";
        }else{
            print_r($inserir->errorInfo());
        }
    }

    
   
}else{

    echo "<script> alert('Acesso não permitido!')</script>";
    echo "<script> window.location.href='pecas.php' </script>";

}

?>