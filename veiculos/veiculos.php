<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false  && $_SESSION['tipoUsuario'] != 4 ) {

    $nomeUsuario = $_SESSION['nomeUsuario'];
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

</head>
<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/veiculo.png" alt="">
                </div>
                <div class="title">
                    <h2>Veículos Cadastrados</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <a href="veiculos-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableVeiculos' class='table table-striped table-bordered nowrap' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap" >  Código Veículo </th>
                                <th scope="col" class="text-center text-nowrap">Tipo Veículo</th>
                                <th scope="col" class="text-center text-nowrap">Categoria</th>
                                <th scope="col" class="text-center text-nowrap">Placa Veículo</th>
                                <th scope="col" class="text-center text-nowrap">Peso Máximo</th>
                                <th scope="col" class="text-center text-nowrap">Cubagem</th>
                                <th scope="col" class="text-center text-nowrap"> Data Revisão Óleo</th>
                                <th scope="col" class="text-center text-nowrap"> Revisão Óleo (KM) </th>
                                <th scope="col" class="text-center text-nowrap"> Data Revisão Diferencial</th>
                                <th scope="col" class="text-center text-nowrap">Revisão Diferencial (KM) </th>
                                <th scope="col" class="text-center text-nowrap"> Km Atual </th>
                                <th scope="col" class="text-center text-nowrap"> Km Restante(Revisão)</th>
                                <th scope="col" class="text-center text-nowrap"> Revisão </th>
                                <th scope="col" class="text-center text-nowrap"> Km Alinhamento</th>
                                <th scope="col" class="text-center text-nowrap"> Km Restante(Alinhamento)</th>
                                <th scope="col" class="text-center text-nowrap"> Alinhamento</th>
                                <th scope="col" class="text-center text-nowrap"> Ações</th>
                            </tr>
                        </thead>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>


    <script src="../assets/js/menu.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $('#tableVeiculos').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_veic.php'
                },
                'columns': [
                    { data: 'cod_interno_veiculo' },
                    { data: 'tipo_veiculo' },
                    { data: 'categoria' },
                    { data: 'placa_veiculo' },
                    { data: 'peso_maximo' },
                    { data: 'cubagem' },
                    { data: 'data_revisao_oleo' },
                    { data: 'km_ultima_revisao' },
                    { data: 'data_revisao_diferencial' },
                    { data: 'km_revisao_diferencial' },
                    { data: 'km_atual' },
                    { data: 'km_restante'},
                    { data: 'situacao'},
                    { data: 'km_alinhamento'},
                    {data: 'km_restante_alinhamento'}, 
                    {data: 'alinhamento'}, 
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "aoColumnDefs":[
                    {'bSortable':false, 'aTargets':[10]},
                    {'bSortable':false, 'aTargets':[11]},
                    {'bSortable':false, 'aTargets':[12]}
                ],
            });
           
        });

        //abrir modal
        $('#tableVeiculos').on('click', '.editbtn', function(event){
            var table = $('#tableVeiculos').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_single_data.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#codVeiculo').val(json.cod_interno_veiculo);
                    $('#tipoVeiculo').val(json.tipo_veiculo);
                    $('#categoria').val(json.categoria);
                    $('#placa').val(json.placa_veiculo);
                    $('#peso').val(json.peso_maximo);
                    $('#cubagem').val(json.cubagem);
                    $('#kmUltimaRevisao').val(json.km_ultima_revisao);
                    $('#dataRevisao').val(json.data_revisao);
                    $('#kmAtual').val(json.km_atual);
                }
            })
        });
    </script>

<!-- modal visualisar e editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Veículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="atualiza.php" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="codVeiculo" class="col-form-label">Código Veículo</label>
                            <input type="text" name="codVeiculo" class="form-control" id="codVeiculo" Readonly >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="tipoVeiculo" class="col-form-label">Tipo Veículo</label>
                            <input type="text" class="form-control" name="tipoVeiculo" id="tipoVeiculo" >
                        </div>
                        <div class="form-group col-md-4">
                            <label for="categoria" class="col-form-label">Categoria</label>
                            <select class="form-control" name="categoria" id="categoria">
                                <option >  </option>
                                <option value="3/4">3/4</option>
                                <option value="Toco">Toco</option>
                                <option value="Truck">Truck</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="placa" class="col-form-label">Placa</label>
                            <input type="text" class="form-control" name="placa" id="placa" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="peso" class="col-form-label">Peso Máximo</label>
                            <input type="text" class="form-control" name="peso" id="peso" >
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cubagem" class="col-form-label">Cubagem</label>
                            <input type="text" class="form-control" name="cubagem" id="cubagem" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label for="kmUltimaRevisao" class="col-form-label">Km da Última Revisão</label>
                            <input type="text" class="form-control" name="kmUltimaRevisao" id="kmUltimaRevisao" required >
                        </div>
                        <div class="form-group col-md-4">
                            <label for="dataRevisao" class="col-form-label">Data Última Revisão</label>
                            <input type="date" class="form-control" name="dataRevisao" id="dataRevisao" required >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="kmAtual" class="col-form-label">Km Atual</label>
                            <input type="text" required class="form-control" name="kmAtual" id="kmAtual" >
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
</body>
</html>