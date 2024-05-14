<?php

session_start();

require_once __DIR__.'/../vendor/autoload.php';
require "../conexao.php";

use Mpdf\Mpdf;

$idModudulo = 20;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $carregamento = filter_input(INPUT_GET, 'carregamento');
    $sql = $db->prepare("SELECT carregamento, COUNT(DISTINCT cod_produto) as qtd, rota, veiculo, motorista, SUM(valor_unit*qtd_falta) as vlTotal,
    CASE 
      WHEN COUNT(DISTINCT situacao) = 1 THEN MIN(situacao)
          ELSE 'Faltando'
      END AS situacao 
   FROM trocas t1 WHERE carregamento=:carregamento GROUP BY carregamento");
    $sql->bindValue(':carregamento', $carregamento);
    $sql->execute();
    $dados=$sql->fetch(PDO::FETCH_ASSOC);

    $qtd =$dados['qtd'];
    $motorista =$dados['motorista'] ;
    $rota =$dados['rota'] ;
    $valor =number_format($dados['vlTotal'],2,",",".") ;
    $carregamento = $dados['carregamento'];
    $situacao = $dados['situacao'];
    $veiculo = $dados['veiculo'];

    // listar trocas com valor faltando maior que um
    $sqlValor = $db->prepare('SELECT cod_produto, nome_produto, qtd_falta, valor_unit, (qtd_falta*valor_unit) as vlTotal, motorista_ausente FROM trocas WHERE carregamento = :carregamento AND qtd_falta>0');
    $sqlValor->bindValue(':carregamento', $carregamento);
    $sqlValor->execute();
    $produtos = $sqlValor->fetchAll(PDO::FETCH_ASSOC);

    if($produtos[0]['motorista_ausente']==1){
        $assinatura = "AUSENTE";
    }else{
        $assinatura=" <img src='assinaturas/$carregamento.png' style='' >";
    }

    $linhaProdutos="";
    $linhaProdutos .= '<table border="1">';
    $linhaProdutos .='<tr>
        <td>Cód. Produto</td>
        <td>Produto</td>
        <td>Valor Unit.</td>
        <td>Qtd Faltou</td>
        <td>Valor Total</td>
    </tr>';
    foreach($produtos as $produto){
        
        $linhaProdutos.= '<tr>';
        $linhaProdutos .='<td>'. $produto['cod_produto']. '</td>';
        $linhaProdutos .='<td>'. $produto['nome_produto']. '</td>';
        $linhaProdutos .='<td>R$'. number_format($produto['valor_unit'],2,",",".") . '</td>';
        $linhaProdutos .='<td>'. number_format($produto['qtd_falta'],2,",",".") . '</td>';
        $linhaProdutos .='<td> R$'. number_format( $produto['vlTotal'],2,",","."). '</td>';
        $linhaProdutos .= '</tr>';
        
    }
    $linhaProdutos .= '</table>';

  
    $mpdf = new \Mpdf\Mpdf(['mode'=>'utf-8', 'format'=>"A4", 'margin_top'=>5, 'margin-left'=>1]);
    $mpdf->AddPage();
    $mpdf->WriteHTML("
    !DOCTYPE html>
    <html lang='pt-br'>
    <head>
        <style>
            table{
                width:100%;
            }
        </style>
    <body>
        <div style='text-align:center; margin-top:-500px'> 
            <img style='width: 50%; ' src='../assets/images/logo.png'> 
        </div>
        <h2 style='text-align:center; '>Comprovante de Conferência</h2>

        <table border='1' style='margin-top:15'>
            <tr>
                <td >Carregamento: $carregamento </td>               
                <td>Valor Total a ser Pago: R$ $valor </td>
            </tr>
            <tr>
                <td colspan='2'>Rota: $rota</td>
            </tr>
            <tr>
                <td colspan='2'>Motorista: $motorista</td>
            </tr>
            <tr>
                <td colspan='2'>Veículo: $veiculo</td>
            </tr>
        </table>
        $linhaProdutos
        <div style='margin-top:30px; width:100%; text-align:center; '>
            $assinatura
            <div style=' width: 100%; float:left; border-top: 1px solid #000; margin-top:-50px'>$motorista </div>
            <img src='../assets/images/assinatura-makson.png' style='width:75%' > <br>
            <div style=' width: 100%; float:left; border-top: 1px solid #000; margin-top:-30px'>Conferente </div>
        </div>    
       
    </body>
    </html>
    ");
    $mpdf->Output();
    
}

?>