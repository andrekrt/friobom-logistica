<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if($tipoUsuario==99 || $tipoUsuario==2){

        $arquivo = 'ocorrencias-por-motorista.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold">Motorista</td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Ocorrências por Má Condução').'  </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Ocorrências por Mau Comportamento').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Qtd Ocorrências').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Qtd Advertências').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Total de Custos </td>';
        
        $html .= '</tr>';

        $sql = $db->query("SELECT ocorrencias.cod_interno_motorista, nome_motorista, COUNT(*) as ocorrencias, SUM(advertencia) as advertencia, SUM(vl_total_custos) as custoTotal FROM ocorrencias LEFT JOIN motoristas ON ocorrencias.cod_interno_motorista = motoristas.cod_interno_motorista GROUP BY ocorrencias.cod_interno_motorista");
        $dados = $sql->fetchAll();
        foreach($dados as $dado):
                //qtd de má condução
                $qtdConduta = $db->prepare("SELECT * FROM ocorrencias WHERE cod_interno_motorista = :motorista AND tipo_ocorrencia = :ocorrencia");
                $qtdConduta->bindValue(':motorista', $dado['cod_interno_motorista']);
                $qtdConduta->bindValue(':ocorrencia', 'Má Condução');
                $qtdConduta->execute();
                $qtdConduta = $qtdConduta->rowCount();

                //qtd de mau comportamento
                $qtdComportamneto = $db->prepare("SELECT * FROM ocorrencias WHERE cod_interno_motorista = :motorista AND tipo_ocorrencia = :ocorrencia");
                $qtdComportamneto->bindValue(':motorista', $dado['cod_interno_motorista']);
                $qtdComportamneto->bindValue(':ocorrencia', 'Mau Comportamento');
                $qtdComportamneto->execute();
                $qtdComportamneto = $qtdComportamneto->rowCount();

            $html .= '<tr id="ok">';
            $html .= '<td>'. utf8_decode($dado['nome_motorista']) . '</td>';
            $html .= '<td>'.$qtdConduta. '</td>';
            $html .= '<td>'.$qtdComportamneto. '</td>';
            $html .= '<td>'. $dado['ocorrencias']. '</td>';
            $html .= '<td>'. $dado['advertencia']. '</td>';
            $html .= '<td>'. str_replace(".",",",$dado['custoTotal']) . '</td>';
            $html .= '</tr>';

        endforeach;

        $html .= '</table>';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$arquivo.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        echo $html;
        
    }

?>