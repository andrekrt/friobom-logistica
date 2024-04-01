<?php

session_start();

require_once __DIR__.'/../vendor/autoload.php';
require "../conexao.php";
include 'extenso.php';

use Mpdf\Mpdf;

$idModudulo = 19;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $id = filter_input(INPUT_GET, 'idvale');
    $sql = $db->prepare("SELECT * FROM vales LEFT JOIN motoristas ON motoristas.cod_interno_motorista=vales.motorista LEFT JOIN rotas ON rotas.cod_rota=vales.rota LEFT JOIN usuarios on usuarios.idusuarios=vales.usuario WHERE idvale =:id");
    $sql->bindValue(':id', $id);
    $sql->execute();
    $dados=$sql->fetch(); 

    $dataAbastecimento =date('d/m/Y', strtotime($dados['data_lancamento'])) ;
    $motorista =$dados['nome_motorista'] ;
    $rota =$dados['nome_rota'] ;
    $valor =number_format($dados['valor'],2,",",".") ;
    $carregamento = $dados['carregamento'];
    $situacao = $dados['situacao'];
    $usuario = $dados['nome_usuario'];
    $extenso = ucwords(extenso($dados['valor']));
  
    $mpdf = new \Mpdf\Mpdf(['mode'=>'utf-8', 'format'=>[148, 105], 'margin_top'=>5]);
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
        <table border='1' style='margin-top:15'>
            <tr>
                <td > VALE  Nº: $id</td>               
                <td>VALOR: R$ $valor</td>
                <td>DATA: $dataAbastecimento </td>
            </tr>
            
        </table>
           
        <table border='1' style='margin-top:15px'>
            <tr>
                <td>Valor por Extenso: $extenso</td>
            </tr>
            <tr>
                <td>Nome: $motorista</td>
            </tr>
            <tr>
                <td>Rota: $rota</td>
            </tr>
        </table>

        <div style='margin-top:30px; width:100%; text-align:center; '>
            <div style=' width: 100%; float:left; border-top: 1px solid #000; '>RESPONSÁVEL </div>
        </div>

        <div style='margin-top:20px; width:100%; text-align:center '>
            <div style=' width: 100%; float:left; border-top: 1px solid #000;'>$motorista </div>
        </div>

       
        
       
    </body>
    </html>
    ");
    $mpdf->Output();
    
}

?>