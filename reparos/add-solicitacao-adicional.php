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
    $frete = filter_input(INPUT_POST, 'frete');
    $situacao = "Em análise";
    $nf = filter_input(INPUT_POST, 'nf');
    $usuario = $_SESSION['idUsuario'];

    $peca = $_POST['peca'];
    $qtd = str_replace(",",".",$_POST['qtd']) ;
    $valorUnit = str_replace(",", ".",$_POST['vlUnit'] ) ;
    $desconto = str_replace(",", ".", $_POST['desconto']);
    $fornecedor =  strstr(filter_input(INPUT_POST, 'fornecedor'), "-", true) ;
    $imagem = $_FILES['imagem']['name']?$_FILES['imagem']['name']:null;

    $db->beginTransaction();

    try{
        for($i=0; $i<count($peca); $i++){

            $valorTotal = ($valorUnit[$i]-$desconto[$i])*$qtd[$i];
    
            $sql = $db->prepare("INSERT INTO solicitacoes_new (token, data_atual, placa, problema, imagem, peca_servico, fornecedor, qtd, vl_unit, desconto, vl_total, frete,num_nf, situacao, usuario) VALUES (:token, :dataAtual, :placa, :problema, :imagem, :peca, :fornecedor, :qtd, :vlUnit, :desconto, :vlTotal, :frete,:nf, :situacao, :usuario)");
            $sql->bindValue(':token', $newToken);
            $sql->bindValue(':dataAtual', $dataAtual);
            $sql->bindValue(':placa', $placa);
            $sql->bindValue(':problema', $problema);
            $sql->bindValue(':imagem', $imagem[$i]);
            $sql->bindValue(':peca', $peca[$i]);
            $sql->bindValue(':fornecedor', $fornecedor);
            $sql->bindValue(':qtd', $qtd[$i]);
            $sql->bindValue(':vlUnit', $valorUnit[$i]);
            $sql->bindValue(':desconto', $desconto[$i]);
            $sql->bindValue(':vlTotal', $valorTotal);
            $sql->bindValue(':frete', $frete);
            $sql->bindValue(':nf', $nf);
            $sql->bindValue(':situacao', $situacao);
            $sql->bindValue(':usuario', $usuario);
            $sql->execute();

            $atualiaza = $db->prepare("UPDATE solicitacoes_new SET situacao = :situacao WHERE token=:token");
            $atualiaza->bindValue(':token', $newToken);
            $atualiaza->bindValue(':situacao', $situacao);
            $atualiaza->execute();

            $pasta = 'uploads/';
            $mover = move_uploaded_file($_FILES['imagem']['tmp_name'][$i],$pasta.$imagem[$i]);               
        }

        $db->commit();

        $_SESSION['msg'] = 'Peça Adicionada com Sucesso';
        $_SESSION['icon']='success';
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Lançar Solicitação';
        $_SESSION['icon']='error';
    }
    header("Location: solicitacoes.php");
    exit();
}else{

}

?>