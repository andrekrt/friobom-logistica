<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && ($_SESSION['tipoUsuario'] == 2 || $_SESSION['tipoUsuario']==99)){

    $usuario = $_SESSION['idUsuario'];
    $veiculo = filter_input(INPUT_POST, 'veiculo');
    $saida = filter_input(INPUT_POST, 'saida');
    $cabine = filter_input(INPUT_POST, 'cabine');
    $retrovisores = filter_input(INPUT_POST,'retrovisores');
    $parabrisa = filter_input(INPUT_POST, 'parabrisa');
    $quebasol = filter_input(INPUT_POST, 'quebasol');
    $bordo = filter_input(INPUT_POST, 'bordo');
    $buzina = filter_input(INPUT_POST, 'buzina');
    $cinto = filter_input(INPUT_POST,'cinto');
    $extintor = filter_input(INPUT_POST, 'extintor');
    $triangulo = filter_input(INPUT_POST, 'triangulo');
    $macaco = filter_input(INPUT_POST, 'macaco');
    $tanque = filter_input(INPUT_POST, 'tanque');
    $janela = filter_input(INPUT_POST,'janela');
    $banco = filter_input(INPUT_POST, 'banco');
    $porta = filter_input(INPUT_POST, 'porta');
    $cambio = filter_input(INPUT_POST, 'cambio');
    $seta = filter_input(INPUT_POST, 'seta');
    $luzFreio = filter_input(INPUT_POST,'luzFreio');
    $luzRe = filter_input(INPUT_POST, 'luzRe');
    $alerta = filter_input(INPUT_POST, 'alerta');
    $luzTeto =filter_input(INPUT_POST, 'luzTeto');
    $faixas = filter_input(INPUT_POST, 'faixas');
    $pneus = filter_input(INPUT_POST, 'pneus');
    $rodas = filter_input(INPUT_POST, 'rodas');
    $estepe = filter_input(INPUT_POST,'estepe');
    $molas = filter_input(INPUT_POST, 'molas');
    $cabo = filter_input(INPUT_POST, 'cabo');
    $refrigeracao = filter_input(INPUT_POST, 'refrigeracao');
    $ventilador = filter_input(INPUT_POST, 'ventilador');
    $farolDianteiro = filter_input(INPUT_POST,'farolDianteiro');
    $farolTraseiro = filter_input(INPUT_POST, 'farolTraseiro');
    $farolNeblina = filter_input(INPUT_POST, 'farolNeblina');
    $farolAlto = filter_input(INPUT_POST, 'farolAlto');
    $luzPainel = filter_input(INPUT_POST, 'luzPainel');
    $imagem = $_FILES['fotos'];

    //echo count($imagem['name']);

    //  echo "$veiculo<br>$saida<br>$cabine<br>$retrovisores<br>$parabrisa<br>$quebasol<br>$bordo<br>$buzina<br>$cinto<br>$extintor<br>$triangulo<br>$macaco<br>$tanque<br>$janela<br>$banco<br>$porta<br>$cambio<br>$seta<br>$luzFreio<br>$luzRe<br>$alerta<br>$luzTeto<br>$faixas<br>$pneus<br>$rodas<br>$estepe<br>$molas<br>$cabo<br>$refrigeracao<br>$ventilador<br><br>";
    // print_r($imagem);
    
    $sql = $db->prepare("INSERT INTO checklist (veiculo, saida, cabine, retrovisores, parabrisas, quebra_sol, bordo, buzina, cinto, extintor, triangulo, macaco, tanque, janelas, banco, porta, cambio, seta, luz_freio, luz_re, alerta, luz_teto, faixas, farol_dianteiro, farol_traseiro, farol_neblina, farol_alto, painel, pneus, rodas, estepe, molas, cabo_forca, refrigeracao, ventiladores, usuario) VALUES (:veiculo, :saida, :cabine, :retrovisores, :parabrisas, :quebraSol, :bordo, :buzina, :cinto, :extintor, :triangulo, :macaco, :tanque, :janelas, :banco, :porta, :cambio, :seta, :luzFreio, :luzRe, :alerta, :luz_teto, :faixas, :farolDianteiro, :farolTraseiro, :farolNeblina, :farolAlto, :painel, :pneus, :rodas, :estepe, :molas, :cabo_forca, :refrigeracao, :ventilador, :usuario)");
    $sql->bindValue(':veiculo', $veiculo);
    $sql->bindValue(':saida', $saida);
    $sql->bindValue(':cabine', $cabine);
    $sql->bindValue(':retrovisores', $retrovisores);
    $sql->bindValue(':parabrisas', $parabrisa);
    $sql->bindValue(':quebraSol', $quebasol);
    $sql->bindValue(':bordo', $bordo);
    $sql->bindValue(':buzina', $buzina);
    $sql->bindValue(':cinto', $cinto);
    $sql->bindValue(':extintor', $extintor);
    $sql->bindValue(':triangulo', $triangulo);
    $sql->bindValue(':macaco', $macaco);
    $sql->bindValue(':tanque', $tanque);
    $sql->bindValue(':janelas', $janela);
    $sql->bindValue(':banco', $banco);
    $sql->bindValue(':porta', $porta);
    $sql->bindValue(':cambio', $cambio);
    $sql->bindValue(':seta', $seta);
    $sql->bindValue(':luzFreio', $luzFreio);
    $sql->bindValue(':luzRe', $luzRe);
    $sql->bindValue(':alerta', $alerta);
    $sql->bindValue(':luz_teto', $luzTeto);
    $sql->bindValue(':faixas', $faixas);
    $sql->bindValue(':farolDianteiro', $farolDianteiro);
    $sql->bindValue(':farolTraseiro', $farolTraseiro);
    $sql->bindValue(':farolNeblina', $farolNeblina);
    $sql->bindValue(':farolAlto', $farolAlto);
    $sql->bindValue(':painel', $luzPainel);
    $sql->bindValue(':pneus', $pneus);
    $sql->bindValue(':rodas', $rodas);
    $sql->bindValue(':estepe', $estepe);
    $sql->bindValue(':molas', $molas);
    $sql->bindValue(':cabo_forca', $cabo);
    $sql->bindValue(':refrigeracao', $refrigeracao);
    $sql->bindValue(':ventilador', $ventilador);
    $sql->bindValue(':usuario', $usuario);
    
    if($sql->execute()){
        $ultimoId = $db->lastInsertId();
        $pasta = 'uploads/'.$ultimoId;
        mkdir($pasta);
        $pasta = $pasta."/saida";
        mkdir($pasta);

        for($i=0;$i<count($imagem['name']);$i++){
            $mover = move_uploaded_file($imagem['tmp_name'][$i],$pasta."/".$imagem['name'][$i]);
        }
        echo "<script> alert('Check-List Realizado!!')</script>";
        echo "<script> window.location.href='checklists.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }


}else{

}

?>