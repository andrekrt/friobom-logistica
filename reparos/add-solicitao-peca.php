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

    //$token = sprintf('%07X', mt_rand(0, 0xFFFFFFF));
    $consultaToken = $db->query("SELECT MAX(token) as token FROM solicitacoes_new");
    $token = $consultaToken->fetch();
    if(empty($token['token'])){
        $newToken = 0+1;
    }else{
        $newToken = $token['token']+1;
    }

    $dataAtual = date("Y/m/d");
    $placa = filter_input(INPUT_POST, 'veiculo');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $rota = filter_input(INPUT_POST,'rota');
    $problema = filter_input(INPUT_POST, 'descricao');
    $frete = str_replace(",",".", filter_input(INPUT_POST, 'frete'));
    $nf = filter_input(INPUT_POST, 'nf');
    $filial = $_SESSION['filial'];

    $situacao = "Em análise";
    $usuario = $_SESSION['idUsuario'];

    $peca = $_POST['peca'];
    $qtd = str_replace(",",".",$_POST['qtd']) ;
    $valorUnit = str_replace(",", ".",$_POST['vlUnit'] ) ;
    $desconto = str_replace(",", ".", $_POST['desconto']);
    $fornecedor  =filter_input(INPUT_POST, 'fornecedor');
    $imagem = $_FILES['imagem']['name']?$_FILES['imagem']['name']:null;

    $db->beginTransaction();

    try{
        // verificar cidade base do veiculo para registrar no bd da viagem
        $sqlCidade = $db->prepare("SELECT cidade_base FROM veiculos WHERE placa_veiculo =:veiculo");
        $sqlCidade->bindValue(':veiculo', $placa);
        $sqlCidade->execute();
        if($sqlCidade->rowCount()>0){
            $cidadeBase = $sqlCidade->fetch();
            $cidadeBase = $cidadeBase['cidade_base'];
        }else{
            $cidadeBase='Bacabal';
        }  

        for($i=0; $i<count($peca); $i++){

            $valorTotal = ($valorUnit[$i]-$desconto[$i])*$qtd[$i];
    
            $sql = $db->prepare("INSERT INTO solicitacoes_new (token, data_atual, placa, motorista, rota, problema, imagem, peca_servico, fornecedor, qtd, vl_unit, desconto,  vl_total, frete, num_nf, situacao, usuario, cidade_base, filial) VALUES (:token, :dataAtual, :placa, :motorista, :rota, :problema, :imagem, :peca, :fornecedor, :qtd, :vlUnit, :desconto, :vlTotal, :frete, :nf, :situacao, :usuario, :cidadeBase, :filial)");
            $sql->bindValue(':token', $newToken);
            $sql->bindValue(':dataAtual', $dataAtual);
            $sql->bindValue(':placa', $placa);
            $sql->bindValue(':motorista', $motorista);
            $sql->bindValue(':rota', $rota);
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
            $sql->bindValue(':cidadeBase', $cidadeBase);
            $sql->bindValue(':filial', $filial);
            $sql->execute();

            $pasta = 'uploads/';
            $mover = move_uploaded_file($_FILES['imagem']['tmp_name'][$i],$pasta.$imagem[$i]);
        }
        $db->commit();

        $_SESSION['msg'] = 'Solicitação Lançada com Sucesso';
        $_SESSION['icon']='success';
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Lançar Solicitação';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}

header("Location: solicitacoes.php");
exit();
?>