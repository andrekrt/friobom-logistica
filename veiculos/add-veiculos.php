<?php

session_start();
require("../conexao.php");

$idModudulo = 1;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idUsuario = $_SESSION['idUsuario'];

    $codVeiculo = filter_input(INPUT_POST, 'codVeiculo');
    $tipoVeiculo = filter_input(INPUT_POST, 'tipoVeiculo');
    $placaVeiculo = filter_input(INPUT_POST, 'placaVeiculo');
    $categoria = filter_input(INPUT_POST, 'categoria');
    $peso = filter_input(INPUT_POST, 'peso');
    $cubagem = str_replace(",", ".",filter_input(INPUT_POST, 'cubagem') ) ;
    $metaCombustivel = str_replace(",", ".",filter_input(INPUT_POST, 'metaCombustivel') ) ;
    $marca = filter_input(INPUT_POST, 'marca');
    $base = filter_input(INPUT_POST, 'base');

    $db->beginTransaction();

    try{
        // verificar se veiculo ja esta cadastrado
        $consulta = $db->prepare("SELECT * FROM veiculos WHERE cod_interno_veiculo = :codVeiculo OR placa_veiculo = :placa");
        $consulta->bindValue(':codVeiculo', $codVeiculo);
        $consulta->bindValue(':placa', $placaVeiculo);
        $consulta->execute();

        if($consulta->rowCount()>0){
            $_SESSION['msg'] = 'Esse Veículo já está cadastrado!';
            $_SESSION['icon']='warning';
            header("Location: form-veiculos.php");
            exit(); 
        }
        
        $sql= $db->prepare("INSERT INTO veiculos (cod_interno_veiculo, tipo_veiculo, placa_veiculo, categoria, marca, peso_maximo, cubagem, meta_combustivel, cidade_base) VALUES (:codVeiculo, :tipoVeiculo, :placaVeiculo, :categoria, :marca, :peso, :cubagem, :metaCombustivel, :base) ");
        $sql->bindValue(':codVeiculo', $codVeiculo);
        $sql->bindValue(':tipoVeiculo', $tipoVeiculo);
        $sql->bindValue(':placaVeiculo', $placaVeiculo);
        $sql->bindValue(':categoria', $categoria);
        $sql->bindValue(':marca',$marca);
        $sql->bindValue(':peso', $peso);
        $sql->bindValue(':cubagem', $cubagem);
        $sql->bindValue(':metaCombustivel', $metaCombustivel);
        $sql->bindValue(':base', $base);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Veículo Cadastrado';
        $_SESSION['icon']='success';
        

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Cadastrar Veículo!';
        $_SESSION['icon']='error';
    } 

    header("Location: form-veiculos.php");
    exit(); 
}

?>