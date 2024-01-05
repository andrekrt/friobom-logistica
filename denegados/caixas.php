<?php

session_start();
require("../conexao.php");

$idModudulo = 8;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.css"/>

    <!-- select02 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</head>
<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/icone-caixa.png" alt="">
                </div>
                <div class="title">
                    <h2>Caixas em Rota</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <div class="area-opcoes-button">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalNf" data-whatever="@mdo" name="idpeca">Nova Saida </button>
                    </div>
                    <a href="caixas-xls.php" ><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableCaixas' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap">Nº</th>
                                <th scope="col" class="text-center text-nowrap">Carga</th>
                                <th scope="col" class="text-center text-nowrap">Qtd de Caixas</th>
                                <th scope="col" class="text-center text-nowrap">Status</th>
                                <th scope="col" class="text-center text-nowrap">Registrado por:</th>
                                <th scope="col" class="text-center text-nowrap"> Ações  </th>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $('#tableCaixas').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pesq_caixas.php'
                },
                'columns': [
                    { data: 'idcaixas' },
                    { data: 'carregamento' },
                    { data: 'qtd_caixas' },
                    { data: 'situacao' },
                    { data: 'nome_usuario' },
                    { data: 'acoes' },
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "aoColumnDefs":[
                    {'bSortable':false, 'aTargets':[5]}
                ],
                "order":[[0,"desc"]]
            });
        });

        //abrir modal
        $('#tableCaixas').on('click', '.editbtn', function(event){
            var table = $('#tableCaixas').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_caixa.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#id').val(json.idcaixas);
                    $('#carga').val(json.carregamento);
                    $('#qtd').val(json.qtd_caixas);     
                }
            })
        });
    </script>

<!-- modal visualisar e editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Caixas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="atualiza-caixas.php" method="post">
                    <input type="hidden" name="id" id="id">
                    <div class="form-row">
                        <div class="form-group col-md-12 espaco ">
                            <label for="carga">Nº Carga </label>
                            <input type="text" name="carga" id="carga" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12 espaco">
                            <label for="qtd"> Qtd de Caixas </label>
                            <input type="text" name="qtd" class="form-control" id="qtd" required>
                        </div>
                    </div> 
            </div>
            <div class="modal-footer">
                    <div class="text-center">
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </form> 
            </div>
        </div>
    </div>
</div>


<!-- MODAL lançamento de nf -->
<div class="modal fade" id="modalNf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Caixas p/ Rota</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add-caixas.php" method="post">
                    <div id="formulario">
                        <div class="form-row">
                            <div class="form-group col-md-12 espaco ">
                                <label for="carga">Nº Carga </label>
                                <input type="text" name="carga" id="carga" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12 espaco">
                                <label for="qtd"> Qtd de Caixas </label>
                                <input type="text" name="qtd" class="form-control" id="qtd" required>
                            </div>
                        </div> 
                    </div>
                <div class="modal-footer">
                    <button type="submit" name="analisar" class="btn btn-primary">Registar</button>
                </form>
                
                </div>
            </div>
        </div>
    </div>
</div>
<!-- FIM MODAL lançamento de entrada-->
</body>
</html>