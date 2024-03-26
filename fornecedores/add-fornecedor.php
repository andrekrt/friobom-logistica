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

    $db->beginTransaction();

    try{
        $verificaFornecedor = $db->prepare("SELECT * FROM fornecedores WHERE cnpj = :cnpj");
        $verificaFornecedor->bindValue(':cnpj', $cnpj);
        $verificaFornecedor->execute();
        if($verificaFornecedor->rowCount()>0){
            $_SESSION['msg'] = 'Esse fornecedor já está cadastrado!';
            $_SESSION['icon']='warning';
            header("Location: fornecedores.php");
            exit();

        }

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
        $inserir->execute();

        $db->commit();

        $_SESSION['msg'] = 'Fornecedor Cadastrado com Sucesso!';
        $_SESSION['icon']='success';
        
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Cadastrar Fornecedor';
        $_SESSION['icon']='error';
    }
    
    header("Location: fornecedores.php");
    exit();

}

?>