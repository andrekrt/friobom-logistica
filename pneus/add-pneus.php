<?php

session_start();
require("../conexao.php");

$idModudulo = 12;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $dataCadastro = date("Y-m-d H:i:s");
    $nFogo = filter_input(INPUT_POST, 'nFogo');
    $medida = strtoupper(filter_input(INPUT_POST, 'medida'));
    $calibMax = filter_input(INPUT_POST, 'calibMax');
    $marca = strtoupper(filter_input(INPUT_POST, 'marca'));
    $modelo = strtoupper(filter_input(INPUT_POST, 'modelo'));
    $serie = strtoupper(filter_input(INPUT_POST, 'nSerie'));
    $vida = filter_input(INPUT_POST, 'vida');
    $posicao = strtoupper(filter_input(INPUT_POST, 'posicao'));
    $situacao = filter_input(INPUT_POST, 'situacao');
    $localizacao = filter_input(INPUT_POST, 'localizacao');
    $veiculo = strtoupper(filter_input(INPUT_POST, 'veiculo'));
    $kmVeiculo = filter_input(INPUT_POST, 'kmVeiculo');
    $suco01 = filter_input(INPUT_POST, 'suco01');
    $suco02 = filter_input(INPUT_POST, 'suco02');
    $suco03 = filter_input(INPUT_POST, 'suco03');
    $suco04 = filter_input(INPUT_POST, 'suco04');
    $usuario = $_SESSION['idUsuario'];
    $ativoVeiculo = 1;
    $uso = 1;

    $db->beginTransaction();

    try{
        $consultaPneu = $db->prepare("SELECT * FROM pneus WHERE num_fogo = :nFogo");
        $consultaPneu->bindValue(':nFogo', $nFogo);
        $consultaPneu->execute();
        if($consultaPneu->rowCount()>0){
            $_SESSION['msg'] = 'Esse pneu já está cadastrado!';
            $_SESSION['icon']='warning';
            header("Location: form-pneus.php");
            exit(); 
        }
        $sql = $db->prepare("INSERT INTO pneus (data_cadastro, num_fogo, medida, calibragem_maxima, marca, modelo, num_serie, vida, posicao_inicio, situacao, localizacao, veiculo, km_inicial, suco01, suco02, suco03, suco04, usuario, uso) VALUES (:dataCadastro, :nFogo, :medida, :calibMax, :marca, :modelo, :nSerie, :vida, :posicao, :situacao, :localizacao, :veiculo, :kmVeiculo, :suco01, :suco02, :suco03, :suco04, :usuario, :uso)");
        $sql->bindValue(':dataCadastro', $dataCadastro);
        $sql->bindValue(':nFogo', $nFogo);
        $sql->bindValue(':medida', $medida);
        $sql->bindValue(':calibMax', $calibMax);
        $sql->bindValue(':marca', $marca);
        $sql->bindValue(':modelo', $modelo);
        $sql->bindValue(':nSerie', $serie);
        $sql->bindValue(':vida', $vida);
        $sql->bindValue(':posicao', $posicao);
        $sql->bindValue(':situacao', $situacao);
        $sql->bindValue(':localizacao', $localizacao);
        $sql->bindValue(':veiculo', $veiculo);
        $sql->bindValue(':kmVeiculo', $kmVeiculo);
        $sql->bindValue(':suco01', $suco01);
        $sql->bindValue(':suco02', $suco02);
        $sql->bindValue(':suco03', $suco03);
        $sql->bindValue(':suco04', $suco04);
        $sql->bindValue(':usuario', $usuario);
        $sql->bindValue(':uso', $uso);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Pneu Cadastrado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Cadastrar Pneu';
        $_SESSION['icon']='error';
    }

    header("Location: pneus.php");
    exit();
    
}else{

}

?>