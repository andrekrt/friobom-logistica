<?php

session_start();
require("../conexao.php");

$idModudulo = 17;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idUsuario = $_SESSION['idUsuario'];

    $razaSocial = filter_input(INPUT_POST, 'razaoSocial');
    $cnpj = filter_input(INPUT_POST, 'cnpj');
    $nomeFantasia = filter_input(INPUT_POST, 'nomeFantasia');
    $apelido = filter_input(INPUT_POST, 'apelido');
    $endereco = filter_input(INPUT_POST, 'endereco');
    $bairro = filter_input(INPUT_POST, 'bairro');
    $cidade = filter_input(INPUT_POST, 'cidade');
    $estado = filter_input(INPUT_POST, 'estado');
    $cep = filter_input(INPUT_POST, 'cep');
    $telefone = filter_input(INPUT_POST, 'telefone');

    $verificaFornecedor = $db->prepare("SELECT * FROM fornecedores WHERE cnpj = :cnpj");
    $verificaFornecedor->bindValue(':cnpj', $cnpj);
    $verificaFornecedor->execute();
    if($verificaFornecedor->rowCount()>0){

        echo "<script>alert('Esse fornecedor já está cadastrado!');</script>";
        echo "<script>window.location.href='fornecedores.php'</script>";

    }else{

        $inserir = $db->prepare("INSERT INTO fornecedores (razao_social, endereco, bairro, cidade, cep, uf, cnpj, nome_fantasia, apelido, telefone) VALUES (:razaoSocial, :endereco, :bairro, :cidade, :cep, :uf, :cnpj, :nomeFantasia, :apelido, :telefone)");
        $inserir->bindValue(':razaoSocial', $razaSocial);
        $inserir->bindValue(':endereco', $endereco);
        $inserir->bindValue(':bairro', $bairro);
        $inserir->bindValue(':cidade', $cidade);
        $inserir->bindValue(':cep', $cep);
        $inserir->bindValue(':uf', $estado);
        $inserir->bindValue(':cnpj', $cnpj);
        $inserir->bindValue(':nomeFantasia', $nomeFantasia);
        $inserir->bindValue(':apelido', $apelido);
        $inserir->bindValue(':telefone', $telefone);

        if($inserir->execute()){
            echo "<script>alert('Fornecedor Cadastrado com Sucesso!');</script>";
            echo "<script>window.location.href='fornecedores.php'</script>";
        }else{
            print_r($inserir->errorInfo());
        }

    }

    

}

?>