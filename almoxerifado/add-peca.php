<?php

session_start();
require("../conexao.php");

$idModudulo = 11;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idUsuario = $_SESSION['idUsuario'];

    $descricao = filter_input(INPUT_POST, 'descricao');
    $medida = filter_input(INPUT_POST, 'medida');
    $grupo = filter_input(INPUT_POST, 'grupo');
    $estoqueMinimo = str_replace(",", ".", filter_input(INPUT_POST, 'estoqueMinimo'));
    $dataCadastro = date('Y-m-d');
    $situacao = 'Solicitar';

    $verificaPeca = $db->prepare("SELECT * FROM peca_estoque WHERE descricao_peca = :descricao");
    $verificaPeca->bindValue(':descricao', $descricao);
    $verificaPeca->execute();
    if($verificaPeca->rowCount()>0){

        echo "<script>alert('Essa Peça já está cadastrada no Estoque!');</script>";
        echo "<script>window.location.href='pecas.php'</script>";

    }else{

        $inserir = $db->prepare("INSERT INTO peca_estoque (descricao_peca, un_medida, grupo_peca, estoque_minimo, situacao, data_cadastro, id_usuario) VALUES (:descricao, :medida, :grupo, :estoqueMinimo, :situacao, :dataCadastro, :idUsuario)");
        $inserir->bindValue(':descricao', $descricao);
        $inserir->bindValue(':medida', $medida);
        $inserir->bindValue(':grupo', $grupo);
        $inserir->bindValue(':estoqueMinimo', $estoqueMinimo);
        $inserir->bindValue(':situacao', $situacao);
        $inserir->bindValue(':dataCadastro', $dataCadastro);
        $inserir->bindValue(':idUsuario', $idUsuario);

        if($inserir->execute()){
            echo "<script>alert('Peça Cadastrada com Sucesso!');</script>";
            echo "<script>window.location.href='pecas.php'</script>";
        }else{
            print_r($inserir->errorInfo());
        }

    }

    

}

?>