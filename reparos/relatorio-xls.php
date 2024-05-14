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

    $filial = $_SESSION['filial'];
    if($filial===99){
        $condicao = " ";
    }else{
        $condicao = "AND solicitacoes.filial=$filial";
    }

        $arquivo = 'pecas-solicitadas.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> ID </td>';
        $html .= '<td class="text-center font-weight-bold"> Data </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Serviço').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Descrição').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Placa Veículo').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Solicitante </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Situação').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Obs. </td>';
        $html .= '<td class="text-center font-weight-bold"> Valor </td>';
        $html .= '<td class="text-center font-weight-bold"> Local </td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM solicitacoes WHERE 1 $condicao");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>' .$dado['id'].  '</td>';
            $html .= '<td>' .$dado['dataAtual'].  '</td>';
            $html .= '<td>' . utf8_decode($dado['servico']) .  '</td>';
            $html .= '<td>' . utf8_decode($dado['descricao']) .  '</td>';
            $html .= '<td>' .$dado['placarVeiculo'].  '</td>';
            $html .= '<td>' .$dado['idSolic'].  '</td>';
            $html .= '<td>' . utf8_decode($dado['statusSolic']) .  '</td>';
            $html .= '<td>' . utf8_decode($dado['obs']) .  '</td>';
            $html .= '<td>' . number_format($dado['valor'],2,",",".") .  '</td>';
            $html .= '<td>' . utf8_decode($dado['localReparo']) .  '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$arquivo.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        echo $html;
        exit;

    }

?>