<?php

require("../conexao.php");

function contaEntradas($idPeca){
    require("../conexao.php");

    $totalEntradas = $db->prepare("SELECT SUM(qtd) as entradas FROM entrada_estoque WHERE peca_idpeca = :idPeca");
    $totalEntradas->bindValue(':idPeca', $idPeca);
    $totalEntradas->execute();
    $entradas = $totalEntradas->fetch();

    if(empty($entradas['entradas'])){
        return 0;
    }else{
        return $entradas['entradas'];
    }
    
}

function contaSaida($idPeca){
    require("../conexao.php");

    $totalSaida = $db->prepare("SELECT SUM(qtd) as saidas FROM saida_estoque WHERE peca_idpeca = :idPeca");
    $totalSaida->bindValue(':idPeca', $idPeca);
    $totalSaida->execute();
    $saidas = $totalSaida->fetch();

    if(empty($saidas['saidas'])){
        return 0;
    }else{
        return $saidas['saidas'];
    }

}

function contaEstoque($idPeca){
    require("../conexao.php");

    $totalEstoque = $db->prepare("SELECT total_estoque as estoque FROM peca_reparo WHERE id_peca_reparo = :idPeca");
    $totalEstoque->bindValue(':idPeca', $idPeca);
    $totalEstoque->execute();
    $estoque = $totalEstoque->fetch();

    if(empty($estoque['estoque'])){
        return 0;
    }else{
        return $estoque['estoque'];
    }

}

function valorTotalPeca($idPeca){
    require("../conexao.php");

    $totalComprado = $db->prepare("SELECT SUM(vl_total_comprado) as totalComprado FROM entrada_estoque WHERE peca_idpeca = :idPeca");
    $totalComprado->bindValue(':idPeca', $idPeca);
    $totalComprado->execute();
    $totalComprado = $totalComprado->fetch();

    if(empty($totalComprado['totalComprado'])){
        return 0;
    }else{
        return $totalComprado['totalComprado'];
    }

}

function precoMedio($idPeca){
    require("../conexao.php");

    $preco = $db->prepare("SELECT * FROM peca_reparo WHERE id_peca_reparo = :idPeca");
    $preco->bindValue(':idPeca', $idPeca);
    $preco->execute();
    $precos = $preco->fetch();

    if($precos['total_estoque']==0 || $precos['total_estoque']==0){
        $precoMedio =0;
    }else{
        $precoMedio = $precos['valor_total']/$precos['total_estoque'];
    }

    return $precoMedio;

}

function estoqueMinimo($idPeca){
    require("../conexao.php");

    $estoqueMinimo = $db->prepare("SELECT estoque_minimo FROM peca_reparo WHERE id_peca_reparo = :idPeca");
    $estoqueMinimo->bindValue(':idPeca', $idPeca);
    $estoqueMinimo->execute();
    $estoqueMinimo = $estoqueMinimo->fetch();
    $estoqueMinimo = $estoqueMinimo['estoque_minimo'];

    if(empty($estoqueMinimo)){
        return 0;
    }else{
        return $estoqueMinimo;
    }    

   
}

function atualizaEStoque($idPeca){
    require("../conexao.php");

    $qtdEntradas = contaEntradas($idPeca);
    $qtdSaidas = contaSaida($idPeca);
    $qtdEstoque = $qtdEntradas-$qtdSaidas;
    $valorComprado = valorTotalPeca($idPeca);
    $estoqueMinimo = estoqueMinimo($idPeca);

    if($qtdEstoque<$estoqueMinimo){
        $situacao = "SOLICITAR";
    }else{
        $situacao = "OK";
    }

    $atualiza = $db->prepare("UPDATE peca_reparo SET total_entrada = :totalEntrada, total_saida = :totalSaida, total_estoque = :totalEstoque, situacao = :situacao, valor_total = :totalComprado WHERE id_peca_reparo = :idPeca ");
    $atualiza->bindValue(':totalEntrada', $qtdEntradas);   
    $atualiza->bindValue(':totalSaida', $qtdSaidas);    
    $atualiza->bindValue(':totalEstoque', $qtdEstoque);  
    $atualiza->bindValue(':idPeca', $idPeca);  
    $atualiza->bindValue(':situacao', $situacao);   
    $atualiza->bindValue(':totalComprado', $valorComprado);

    if($atualiza->execute()){
        return true;
    }else{
        return false;
    }
    
}

function addSaida($qtd, $peca, $placa, $obs, $servico, $os, $usuario, $requisicao){
    require("../conexao.php");

        $dataSaida = date("Y-m-d");
        $inserir = $db->prepare("INSERT INTO saida_estoque (data_saida, qtd, peca_idpeca, placa, obs, servico, os, requisicao_saida, id_usuario) VALUES (:dataSaida, :qtd, :peca, :placa, :obs, :servico, :os, :requisicao_saida, :idUsuario)");
        $inserir->bindValue(':dataSaida', $dataSaida);
        $inserir->bindValue(':qtd', $qtd);
        $inserir->bindValue(':peca', $peca);
        $inserir->bindValue(':placa', $placa);
        $inserir->bindValue(':obs', $obs);
        $inserir->bindValue(':servico', $servico);
        $inserir->bindValue(':os', $os);
        $inserir->bindValue(':requisicao_saida', $requisicao);
        $inserir->bindValue(':idUsuario', $usuario);
 
        if($inserir->execute()){  
            atualizaEStoque($peca);
            return true;    
        }else{
            return false;
        }

}

function atualisaSaida($idSaida, $servico, $peca, $qtd, $requisicao, $placa, $obs){
    require("../conexao.php");

    $atualiza=$db->prepare("UPDATE saida_estoque SET servico = :servico, peca_idpeca = :peca, qtd = :qtd, requisicao_saida = :requisicao, placa = :placa, obs = :obs WHERE idsaida_estoque = :idSaida; ");
    $atualiza->bindValue(':servico', $servico);
    $atualiza->bindValue(':peca', $peca);
    $atualiza->bindValue(':qtd', $qtd);
    $atualiza->bindValue(':requisicao', $requisicao);
    $atualiza->bindValue(':placa', $placa);
    $atualiza->bindValue(':obs', $obs);
    $atualiza->bindValue(':idSaida', $idSaida);
    if($atualiza->execute()){  
        atualizaEStoque($peca);
        return true;    
    }else{
        return false;
    }
}

?>