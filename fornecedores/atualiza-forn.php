<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 99){

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

    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='fornecedores.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }

}else{

}

?>