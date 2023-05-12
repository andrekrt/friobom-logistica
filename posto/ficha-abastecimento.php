<?php

session_start();
use Mpdf\Mpdf;
require_once __DIR__.'/../vendor/autoload.php';
require "../conexao.php";

if($_SESSION['tipoUsuario']==1 || $_SESSION['tipoUsuario']==99){

    $id = filter_input(INPUT_GET, 'id');
    $sql = $db->prepare("SELECT * FROM combustivel_saida WHERE idcombustivel_saida =:id");
    $sql->bindValue(':id', $id);
    $sql->execute();
    $dados=$sql->fetch(); 

    $idAbastecimento = $dados['idcombustivel_saida'];
    $dataAbastecimento =date('d/m/Y', strtotime($dados['data_abastecimento'])) ;
    $totalLitros =number_format($dados['litro_abastecimento'],2,",",".") ;
    $precoLitro =number_format($dados['preco_medio'],2,",",".") ;
    $valorTotal =number_format($dados['valor_total'],2,",",".") ;
    $carregamento = $dados['carregamento'];
    $km = $dados['km'];
    $placa = $dados['placa_veiculo'];
    $rota = $dados['rota'];
    $motorista = $dados['motorista'];
    $tipo = $dados['tipo_abastecimento'];
  
    $mpdf = new Mpdf(['mode'=>'utf-8', 'format'=>[50, 65], 'margin_top'=>1, 'margin_left'=>1, 'margin_right'=>1, 'margin_bottom'=>0]);
    $mpdf->AddPage();
    $mpdf->WriteHTML("
    !DOCTYPE html>
    <html lang='pt-br'>
    <head>
        
    <body>
        <div style='width: 1000px;'>
            <div style='text-align:center; margin-bottom:15px; margin-top:-50px'> 
                <img style='width: 3cm; ' src='../assets/images/logo.png'> 
            </div>
            <div style='text-align:center;  border: 1px solid #000; border-radius: 15px; margin-bottom:10px'>
                <h2 style='font-size:10px'>Abastecimento </h2>
            </div>
        </div>
        <div>
            <table border='1' style='width: 100%; font-size:8px'>
                <tr >
                    <td>Nº: $idAbastecimento </td>
                    <td > Data: $dataAbastecimento</td>
                </tr>    
                <tr>
                    <td>Litros: $totalLitros</td>
                    <td>Valor Médio:R$ $precoLitro</td>
                   
                </tr>          
                <tr>
                    <td>Valor Total: R$ $valorTotal</td>
                    <td>Carregamento: $carregamento</td>
                </tr>          
                <tr>
                    <td>Placa: $placa</td>
                    <td>Km: $km</td>
                </tr>  
                <tr>
                    <td>Rota: $rota</td>
                    <td>Abastecimento: $tipo</td>
                </tr>
                <tr>
                    <td colspan='2'>Motorista: $motorista</td>
                </tr>
            </table>
            <div style='margin-top:20px; width:100%; text-align:center '>
                <div style=' width: 100%; float:left; border-top: 1px solid #000; font-size:8px'>Assinatura </div>
            </div>
        </div>
       
    </body>
    </html>
    ");
    $mpdf->Output();
}

?>