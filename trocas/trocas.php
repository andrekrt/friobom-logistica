<?php

session_start();
require("../conexao.php");
include '../conexao-oracle.php';

$idModudulo = 20;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $filial = $_SESSION['filial'];

    $sqlCarregamento = $dbora->prepare('SELECT C.NUMCAR FROM friobom.pcpedc C INNER JOIN FRIOBOM.pcpedi d ON c.numped=d.numped INNER JOIN  friobom.pccarreg g ON c.numcar = g.numcar WHERE g.dtretorno IS NULL AND c.condvenda=11 AND d.posicao=:posicao AND c.dtfat BETWEEN SYSDATE -10 AND SYSDATE GROUP BY C.NUMCAR ');
    $sqlCarregamento->bindValue(':posicao', 'F');
    $sqlCarregamento->execute();
    $carregamentos = $sqlCarregamento->fetchAll(PDO::FETCH_ASSOC);

    // var_dump(count($carregamentos));
    
    foreach($carregamentos as $carregamento){
        $sqlTroca = $db->prepare("SELECT carregamento FROM trocas WHERE carregamento=:carregamento");
        $sqlTroca->bindValue(':carregamento', $carregamento['NUMCAR']);
        $sqlTroca->execute();
        if($sqlTroca->rowCount()<1){
            $sqlWint = $dbora->prepare("SELECT c.dtfat, dp.codprod, p.descricao, dp.qt, dp.pvenda, dp.numped, dp.numcar,c.destino, c.codveiculo, v.placa, c.codmotorista, m.nome FROM friobom.pcpedc cp INNER JOIN friobom.pcpedi dp ON cp.numped=dp.numped LEFT JOIN friobom.pccarreg c ON dp.numcar = c.numcar LEFT JOIN friobom.pcprodut p ON dp.codprod=p.codprod LEFT JOIN FRIOBOM.pcveicul v ON c.codveiculo=v.codveiculo LEFT JOIN FRIOBOM.pcempr m ON c.codmotorista=m.matricula WHERE  cp.condvenda=11 AND dp.posicao=:posicao AND dp.NUMCAR = :carregamento ");
            $sqlWint->bindValue(':posicao', 'F');
            $sqlWint->bindValue(':carregamento', $carregamento['NUMCAR']);
            $sqlWint->execute();
            $trocas = $sqlWint->fetchAll(PDO::FETCH_ASSOC);

            foreach($trocas as $troca){
                $preco = str_replace(",",".",$troca['PVENDA']);
                $qtd= str_replace(",",".", $troca['QT']);
            
                $valorTotal = $qtd*$preco;

                // echo "Preço unit=$preco - Qtd= $qtd - Valor total=$valorTotal Carregamento = $carregamento[NUMCAR]<br>";

                $sqlInsert = $db->prepare('INSERT INTO trocas (cod_produto, nome_produto, qtd, valor_unit, valor_total, pedido, carregamento, rota, veiculo, motorista, filial) VALUES (:codProd, :nomeProd, :qtd, :vlUnit, :vlTotal, :pedido, :carregamento, :rota, :veiculo, :motorista, :filial)');
                $sqlInsert->bindValue(':codProd', $troca['CODPROD']);
                $sqlInsert->bindValue(':nomeProd', $troca['DESCRICAO']);
                $sqlInsert->bindValue(':qtd', $qtd);
                $sqlInsert->bindValue(':vlUnit',$preco);
                $sqlInsert->bindValue(':vlTotal',$valorTotal);
                $sqlInsert->bindValue(':pedido', $troca['NUMPED']);
                $sqlInsert->bindValue(':carregamento', $troca['NUMCAR']);
                $sqlInsert->bindValue(':rota', mb_convert_encoding($troca['DESTINO'], 'UTF-8','ISO-8859-1'));
                $sqlInsert->bindValue(':veiculo', $troca['PLACA']);
                $sqlInsert->bindValue(':motorista', $troca['NOME']);
                $sqlInsert->bindValue(':filial', $filial);
                $sqlInsert->execute();
            }

        }
       
    }
       

} else {
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRIOBOM - TRANSPORTE</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- arquivos para datatable -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css"/>
</head>
<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/trocas.png" alt="">
                </div>
                <div class="title">
                    <h2>Trocas</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <a href="pecas-xls.php" ><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableTrocas' class='table table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap">Carregamento</th>
                                <th scope="col" class="text-center text-nowrap">Qtd de Itens</th>
                                <th scope="col" class="text-center text-nowrap">Rota</th>
                                <th scope="col" class="text-center text-nowrap">Veículo</th>
                                <th scope="col" class="text-center text-nowrap">Motorista</th>
                                <th scope="col" class="text-center text-nowrap">Situação</th>
                                <th scope="col" class="text-center text-nowrap">Valor Faltante</th>
                                <th scope="col" class="text-center text-nowrap">Ações</th>
                            </tr>
                        </thead>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
    <!-- sweert alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function(){
            $('#tableTrocas').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pesq_troca.php'
                },
                'columns': [
                    { data: 'carregamento' },
                    { data: 'qtd' },
                    { data: 'rota' },
                    { data: 'veiculo' },
                    { data: 'motorista'},
                    { data: 'situacao' },
                    { data: 'valor'},
                    { data: 'acoes'}
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "aoColumnDefs":[
                    {'bSortable':false, 'aTargets':[7]}
                ],
                "order":[
                    0, 'desc'
                ],
                "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull ){
                   
                    if(aData['situacao']==='Não Conferido'){
                       $(nRow).css('background-color', 'red');
                       $(nRow).css('color', 'white')
                    }
                   return nRow;
               },
            });
        });

        //abrir modal
        $('#tablePecas').on('click', '.editbtn', function(event){
            var table = $('#tablePecas').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_single_peca.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#id').val(json.id_peca_reparo);
                    $('#descricao').val(json.descricao);
                    $('#categoria').val(json.categoria);
                    $('#medida').val(json.un_medida);
                    $('#estoqueMin').val(json.estoque_minimo);
                }
            })
        });
    </script>

<!-- msg de sucesso ou erro -->
<?php
    // Verifique se há uma mensagem de confirmação na sessão
    if (isset($_SESSION['msg']) && isset($_SESSION['icon'])) {
        // Exiba um alerta SweetAlert
        echo "<script>
                Swal.fire({
                  icon: '$_SESSION[icon]',
                  title: '$_SESSION[msg]',
                  showConfirmButton: true,
                });
              </script>";

        // Limpe a mensagem de confirmação da sessão
        unset($_SESSION['msg']);
        unset($_SESSION['status']);
    }
?>
</body>
</html>