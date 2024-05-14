<?php

session_start();
require("../conexao.php");

$filial = $_SESSION['filial'];
if($filial===99){
    $condicao = " ";
}else{
    $condicao = "AND complementos_combustivel.filial=$filial";
}
$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if($_SESSION['tipoUsuario'] != 4){
        

        $arquivo = 'complementos.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> ID </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Veículo').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Motorista </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Km Saída').'  </td>';
        $html .= '<td class="text-center font-weight-bold"> Km Chegada </td>';
        $html .= '<td class="text-center font-weight-bold"> Litros Abast. </td>';
        $html .= '<td class="text-center font-weight-bold"> Valor Abast. </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Usuário').'  </td>';               
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM complementos_combustivel LEFT JOIN veiculos ON complementos_combustivel.veiculo = veiculos.cod_interno_veiculo LEFT JOIN motoristas ON complementos_combustivel.motorista = motoristas.cod_interno_motorista LEFT JOIN usuarios ON complementos_combustivel.id_usuario = usuarios.idusuarios WHERE 1 $condicao");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>'.$dado['id_complemento']. '</td>';
            $html .= '<td>'.$dado['placa_veiculo']. '</td>';
            $html .= '<td>'. utf8_decode($dado['nome_motorista']) . '</td>';
            $html .= '<td>'.$dado['km_saida']. '</td>';
            $html .= '<td>'. $dado['km_chegada']. '</td>';
            $html .= '<td>'. str_replace(".",",",$dado['litros_abast'])  . '</td>';
            $html .= '<td>'. str_replace(".",",",$dado['valor_abast'])  . '</td>';
            $html .= '<td>'. utf8_decode($dado['nome_usuario'])  . '</td>';
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