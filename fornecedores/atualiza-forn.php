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
    $id = filter_input(INPUT_POST, 'id');
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
        $atualiza = $db->prepare("UPDATE fornecedores SET razao_social = :razaoSocial, endereco = :endereco, bairro = :bairro, cidade = :cidade, cep = :cep, uf = :estado, cnpj = :cnpj, nome_fantasia = :nomeFantasia, apelido = :apelido, telefone = :telefone  WHERE id = :id");
        $atualiza->bindValue(':razaoSocial', $razaSocial);
        $atualiza->bindValue(':endereco', $endereco);
        $atualiza->bindValue(':bairro', $bairro);
        $atualiza->bindValue(':cidade', $cidade);
        $atualiza->bindValue(':cep', $cep);
        $atualiza->bindValue(':estado', $estado);
        $atualiza->bindValue(':cnpj', $cnpj);
        $atualiza->bindValue(':nomeFantasia', $nomeFantasia);
        $atualiza->bindValue(':apelido', $apelido);
        $atualiza->bindValue(':telefone', $telefone);
        $atualiza->bindValue(':id', $id);
        $atualiza->execute();

        $db->commit();

        $_SESSION['msg'] = 'Fornecedor Atualizado com Sucesso!';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Atualizar Fornecedor';
        $_SESSION['icon']='error';
    }

    header("Location: fornecedores.php");
    exit();

}else{

}

?>