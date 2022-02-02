<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veículos</title>
    
</head>
<body>
    <?php
    
    if($tipoUsuario==3 || $tipoUsuario==4 || $tipoUsuario==99){

        $arquivo = 'solicitacoes.xls';
        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> Token  </td>';
        $html .= '<td class="text-center font-weight-bold"> Data </td>';
        $html .= '<td class="text-center font-weight-bold"> Placa </td>';
        $html .= '<td class="text-center font-weight-bold"> Categoria </td>';
        $html .= '<td class="text-center font-weight-bold"> Motorista </td>';
        $html .= '<td class="text-center font-weight-bold"> Rota </td>';
        $html .= '<td class="text-center font-weight-bold"> Problema </td>';
        $html .= '<td class="text-center font-weight-bold"> Local Reparo </td>';
        $html .= '<td class="text-center font-weight-bold"> Categoria </td>';
        $html .= '<td class="text-center font-weight-bold"> Peça/Serviço </td>';
        $html .= '<td class="text-center font-weight-bold"> Qtd. </td>';
        $html .= '<td class="text-center font-weight-bold"> Medida </td>';
        $html .= '<td class="text-center font-weight-bold"> Valor Unit. </td>';
        $html .= '<td class="text-center font-weight-bold"> Valor Total </td>';
        $html .= '<td class="text-center font-weight-bold"> Solicitante </td>';
        $html .= '<td class="text-center font-weight-bold"> Situação </td>';
        $html .= '<td class="text-center font-weight-bold"> Data Aprovação </td>';
        $html .= '<td class="text-center font-weight-bold"> Obs. </td>';
        $html .= '</tr>';

        $revisao = $db->query("SELECT solicitacoes_new.*, usuarios.nome_usuario, peca_reparo.*, veiculos.categoria FROM solicitacoes_new LEFT JOIN peca_reparo ON solicitacoes_new.peca_servico = peca_reparo.id_peca_reparo LEFT JOIN usuarios ON solicitacoes_new.usuario = usuarios.idusuarios LEFT JOIN veiculos ON solicitacoes_new.placa = veiculos.placa_veiculo" );
        $dados = $revisao->fetchAll();
        foreach($dados as $dado){
            
            $html .= '<tr>';
            $html .= '<td>'.$dado['token'] .'</td>';
            $html .= '<td>'. date("d/m/Y",strtotime($dado['data_atual'] )) .'</td>';
            $html .= '<td>'. $dado['placa'] .'</td>';
            $html .= '<td>'. $dado['categoria'] .'</td>';
            $html .= '<td>'. $dado['motorista'] .'</td>';
            $html .= '<td>'. $dado['rota'] .'</td>';
            $html .= '<td>'. $dado['problema'] .'</td>';
            $html .= '<td>'. $dado['local_reparo'] .'</td>';
            $html .= '<td>'. $dado['categoria']  .'</td>';
            $html .= '<td>'. $dado['descricao']  .'</td>';
            $html .= '<td>'. str_replace(".",",",$dado['qtd'])  .'</td>';
            $html .= '<td>'. $dado['un_medida']  .'</td>';
            $html .= '<td>'. str_replace(".",",",$dado['vl_unit'])   .'</td>';
            $html .= '<td>'. str_replace(".",",",$dado['vl_total'])   .'</td>';
            $html .= '<td>'. $dado['nome_usuario']  .'</td>';
            $html .= '<td>'. $dado['situacao']  .'</td>';
            $html .= '<td>'. date("d/m/Y", strtotime($dado['data_aprovacao'] ))  .'</td>';
            $html .= '<td>'. $dado['obs']  .'</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$arquivo.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        echo $html;

    }
    
    ?>
</body>
</html>