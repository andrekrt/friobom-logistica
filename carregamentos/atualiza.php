<?php

session_start();
require("../conexao.php");
date_default_timezone_set('America/Sao_Paulo');

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 5 || $_SESSION['tipoUsuario'] == 99 || $_SESSION['tipoUsuario'] == 6){

    //definindo o dia da semana
    $numDiaSemana = date('w');
    switch ($numDiaSemana) {
        case '0':
            $diaSemana = 'Domingo';
            break;
        case '1':
            $diaSemana = 'Segunda-Feira';
            break;
        case '2':
            $diaSemana = 'Terça-Feira';
            break;
        case '3':
            $diaSemana = 'Quarta-Feira';
            break;
        case '4':
            $diaSemana = 'Quinta-Feira';
            break;
        case '5':
            $diaSemana = 'Sexta-Feira';
            break;
        case '6':
            $diaSemana = 'Sábado';
            break;
        default:
            $diaSemana = '';
            break;
    }

    $atraso = "";
    $id = filter_input(INPUT_POST, 'id');
    $numCarreg = filter_input(INPUT_POST, 'carregamento');
    $doca = filter_input(INPUT_POST, 'doca');
    $rota = filter_input(INPUT_POST, 'rota');
    $placaVeiculo = filter_input(INPUT_POST, 'placa');
    $horaCaminhaDoca = filter_input(INPUT_POST, 'horaDoca');
    $problemaCaminhao = filter_input(INPUT_POST, 'problemaCaminhao');
    $carregadorPrincipal = filter_input(INPUT_POST, 'carregadorPrincipal');
    $carregadoresAdicionais = filter_input(INPUT_POST, 'carregadoresAdicionais');
    $carregadorErro = filter_input(INPUT_POST, 'carregadorErro');
    $fotoErro = isset($_FILES['fotoErro'])?$_FILES['fotoErro']:null;
    $fotoErro_nome = isset($fotoErro['name'][0])?$fotoErro['name'][0]:null;
    $notaCarregamento = str_replace(',', ".", filter_input(INPUT_POST, 'notaCarregamento'))?str_replace(',', ".", filter_input(INPUT_POST, 'notaCarregamento')):null;
    $fotoCarregamento =isset($_FILES['fotoCarregamento'])?$_FILES['fotoCarregamento']:null;
    $fotoCarregamento_nome = isset($fotoCarregamento['name'][0])?$fotoCarregamento['name'][0]:null;
    $peso = str_replace(",", ".",filter_input(INPUT_POST, 'peso'));
    
    
    if(empty($notaCarregamento) && !empty($placaVeiculo)){
        $situacao ='Na Doca';
    }elseif(!empty($notaCarregamento)){
        $situacao = 'Carregado';
    }else{
        $situacao='Iniciado';
    }   

    $codRota = substr($rota, 0, 2);
    $buscaRota = $db->query("SELECT * FROM rotas WHERE cod_rota = '$codRota'");
    $rotas = $buscaRota->fetch();
    $diaFechamento1 = $rotas['fechamento1'];
    $diaFechamento2 = $rotas['fechamento2'];
    
    if($diaSemana==$diaFechamento1 && isset($horaCaminhaDoca)){
        $horaDoca = new DateTime($horaCaminhaDoca);
        $tempoAtraso = $horaDoca->diff(new DateTime($rotas['hora_fechamento1']));
        
        $horaAtraso = $tempoAtraso->h;
        $minutoAtraso = $tempoAtraso->i;
        $atraso = $horaAtraso . ":". $minutoAtraso;

        $atraso = date('H:i', strtotime($atraso));

    }elseif($diaSemana==$diaFechamento2 && isset($horaCaminhaDoca)){
        $horaDoca = new DateTime($horaCaminhaDoca);
        $tempoAtraso = $horaDoca->diff(new DateTime($rotas['hora_fechamento2']));
        
        $horaAtraso = $tempoAtraso->h;
        $minutoAtraso = $tempoAtraso->i;
        $atraso = $horaAtraso . ":". $minutoAtraso;

        $atraso = date('H:i', strtotime($atraso));
        
    }elseif($diaFechamento1=="Segunda à Sexta" && isset($horaCaminhaDoca)){
        $horaDoca = new DateTime($horaCaminhaDoca);
        $tempoAtraso = $horaDoca->diff(new DateTime($rotas['hora_fechamento1']));
        
        $horaAtraso = $tempoAtraso->h;
        $minutoAtraso = $tempoAtraso->i;
        $atraso = $horaAtraso . ":". $minutoAtraso;

        $atraso = date('H:i', strtotime($atraso));
    }

    

    $atualiza = $db->prepare('UPDATE carregamentos SET num_carreg = :carregamento, doca = :doca, rota = :rota, peso = :peso, placa_veiculo = :placaVeiculo, hora_caminhao_doca = :horaCaminhaoDoca, tempo_atraso = :tempoAtraso, problema_caminhao = :problemaCaminhao, carregador_principal = :carregadorPrincipal, carregadores_adicionais = :carregadoresAdicionais, usuario_errou = :carregadorErro, foto_erro = :fotoErro, nota_carregamento = :notaCarregamento, foto_carregamento = :fotoCarregamento, situacao = :situacao WHERE id = :id');
    $atualiza->bindValue(':doca', $doca);
    $atualiza->bindValue(':rota', $rota);
    $atualiza->bindValue(':peso', $peso);
    $atualiza->bindValue(':placaVeiculo', $placaVeiculo);
    $atualiza->bindValue(':horaCaminhaoDoca', $horaCaminhaDoca);
    $atualiza->bindValue(':tempoAtraso', $atraso);
    $atualiza->bindValue(':problemaCaminhao', $problemaCaminhao);
    $atualiza->bindValue(':carregadorPrincipal', $carregadorPrincipal);
    $atualiza->bindValue(':carregadoresAdicionais', $carregadoresAdicionais);
    $atualiza->bindValue(':carregadorErro', $carregadorErro);
    $atualiza->bindValue(':fotoErro', $fotoErro_nome);
    $atualiza->bindValue(':notaCarregamento', $notaCarregamento);
    $atualiza->bindValue(':fotoCarregamento', $fotoCarregamento_nome);
    $atualiza->bindValue(':situacao', $situacao);
    $atualiza->bindValue(':carregamento', $numCarreg);
    $atualiza->bindValue(':id', $id);

    //uploads de fotos dos erros de carregamentos
    $diretorio = "uploads/erros/".$id;
    if(is_dir($diretorio)==false){
        mkdir($diretorio, 0755);
    }      
    for($i=0;$i<count($fotoErro['name']);$i++){
        $destino = "uploads/erros/".$id."/".$fotoErro['name'][$i];
        move_uploaded_file($fotoErro['tmp_name'][$i], $destino);
    }

    //uploads de fotos do carregamento finalizado
    $diretorio = "uploads/concluidos/".$id;
    if(is_dir($diretorio)==false){
        mkdir($diretorio, 0755);
    }        
    for($i=0;$i<count($fotoCarregamento['name']);$i++){
        $destino = "uploads/concluidos/".$id."/".$fotoCarregamento['name'][$i];
        move_uploaded_file($fotoCarregamento['tmp_name'][$i], $destino);
    }
    
    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='carregamentos.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }

}else{
    header("Location:carregamentos.php");
}

?>