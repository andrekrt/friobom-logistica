<?php

session_start();
require("../conexao.php");

$idModudulo = 4;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

        $primeiroDia = date("Y-m-01", strtotime('-1 months', strtotime(date('Y-m-d'))));
        $ultimodia = date("Y-m-t",strtotime('-1 months', strtotime(date('Y-m-d'))));

        $minimo =  filter_input(INPUT_GET, 'min')?filter_input(INPUT_GET, 'min'):$primeiroDia;
        $maximo = filter_input(INPUT_GET, 'max')?filter_input(INPUT_GET, 'max'):$ultimodia;
        $motorista = filter_input(INPUT_GET, 'motorista')?filter_input(INPUT_GET, 'motorista'):"%";

        //echo "$dataInicio<br>$dataFim<br>$motorista";

        $arquivo = 'media-combustivel.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> Motorista </td>';
        $html .= '<td class="text-center font-weight-bold">Placa</td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Categoria Veículo') .'</td>';
        $html .= '<td class="text-center font-weight-bold"> Rota </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Saída')  .'</td>';
        $html .= '<td class="text-center font-weight-bold">Chegada</td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Média Alcançada').'</td>';
        $html .= '<td class="text-center font-weight-bold">'.utf8_decode('Meta Média').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('% Atingido').' </td>';
        $html .= '<td class="text-center font-weight-bold">Qtd Viagem </td>';
        $html .= '<td class="text-center font-weight-bold">Valor Ganho</td>';
        $html .= '</tr>';

        $sql=$db->prepare("SELECT nome_motorista, viagem.placa_veiculo, veiculos.categoria,nome_rota, data_saida, data_chegada, media_comtk FROM `viagem` LEFT JOIN veiculos ON viagem.cod_interno_veiculo = veiculos.cod_interno_veiculo WHERE data_chegada BETWEEN :dtInicial AND :dtFinal AND nome_motorista LIKE :motorista ORDER BY nome_motorista ASC");
        $sql->bindValue(':motorista', $motorista);
        $sql->bindValue(':dtInicial', $minimo);
        $sql->bindValue(':dtFinal', $maximo);
        $sql->execute();
        $dados = $sql->fetchAll();

        foreach($dados as $dado):              
            switch ($dado['categoria']) {
                case 'Truck':
                    $meta = 3.5;
                    $percAting = $dado['media_comtk']==0?0:($dado['media_comtk']/$meta);
                    break;
                case 'Toco':
                    $meta = 3.9;
                    $percAting = $dado['media_comtk']==0?0:($dado['media_comtk']/$meta);
                    break;
                case 'Mercedinha':
                    $meta = 5.2;
                    $percAting = $dado['media_comtk']==0?0:($meta/$dado['media_comtk']/$meta);
                    break;
                default:
                    $meta = 1;
                    $percAting = $dado['media_comtk']==0?0:($dado['media_comtk']/$meta);
                    break;
            } 
            
            $qtdViagem = $db->prepare("SELECT COUNT(*) as qtd FROM viagem WHERE nome_motorista = :motorista AND data_chegada BETWEEN :dtInicio AND :dtFinal");
            $qtdViagem->bindValue(':motorista', $dado['nome_motorista']);
            $qtdViagem->bindValue(':dtInicio', $primeiroDia);
            $qtdViagem->bindValue(':dtFinal', $ultimodia);
            $qtdViagem->execute();
            $qtdViagem = $qtdViagem->fetch();

            //valor ganho
            if($percAting<1){
                $valor = 0;
            }elseif($percAting>=1 && $percAting<=1.2){
                $valor = (100/$qtdViagem['qtd'])*$percAting;
            }else{
                $valor = (100/$qtdViagem['qtd'])*1.2;
            }

            $html.='<tr>';
            $html.='<td>'.utf8_decode($dado['nome_motorista']).'</td>';
            $html.='<td>'.$dado['placa_veiculo'].'</td>';
            $html.='<td>'.$dado['categoria'].'</td>';
            $html.='<td>'.utf8_decode($dado['nome_rota']).'</td>';
            $html.='<td>'.date("d/m/Y", strtotime($dado['data_saida'])).'</td>';
            $html.='<td>'.date("d/m/Y", strtotime($dado['data_chegada'])).'</td>';
            $html.='<td>'.number_format($dado['media_comtk'],2,",",".").'</td>';
            $html.='<td>'.number_format($meta,2,",",".").'</td>';
            $html.='<td>'.number_format($percAting*100,2,",",".")."%".'</td>';
            $html.='<td>'.$qtdViagem['qtd'].'</td>';
            $html.='<td>'."R$".number_format($valor,2,",",".").'</td>';     
        endforeach;

        $html .= '</table>';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$arquivo.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header ("Content-Type: text/html; charset=UTF-8"); 

        echo $html;

    }

?>