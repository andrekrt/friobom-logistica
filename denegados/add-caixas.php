<?php

session_start();
require("../conexao.php");
$idModudulo = 8;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    
    $idUsuario = $_SESSION['idUsuario'];
    $carga = filter_input(INPUT_POST, 'carga');
    $qtd =filter_input(INPUT_POST, 'qtd');
    $situacao = "Saída";

    $db->beginTransaction();

    try{
        // verificar se já existe registro para essa carga
        $consulta = $db->prepare("SELECT * FROM caixas WHERE carregamento=:carregamento");
        $consulta->bindValue(':carregamento', $carga);
        $consulta->execute();
        $qtdCarga = $consulta->rowCount();
        if($qtdCarga>0){
            $_SESSION['msg'] = 'Já existe registro nesse carregamento!';
            $_SESSION['icon']='warning';
            header("Location: caixas.php");
            exit();

        }

        $inserir = $db->prepare("INSERT INTO caixas (carregamento, qtd_caixas, situacao, usuario) VALUES (:carregamento, :qtd, :situacao, :usuario)");
        $inserir->bindValue(':carregamento', $carga);
        $inserir->bindValue(':qtd', $qtd);
        $inserir->bindValue(':situacao', $situacao);
        $inserir->bindValue(':usuario', $idUsuario);
        $inserir->execute();

        $db->commit();

        $_SESSION['msg'] = 'Saída de Caixa Registrada com Sucesso';
        $_SESSION['icon']='success';
        
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Registrar Saída de Caixa';
        $_SESSION['icon']='error';
    } 
    header("Location: caixas.php");
    exit();
}   

?>