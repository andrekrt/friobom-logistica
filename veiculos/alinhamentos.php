<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 4 ) {

   
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
                    <h2>Alinhamentos</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <div class="area-opcoes-button">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalRevisao" data-whatever="@mdo" name="revisao">Novo Alinhamento</button>
                    </div>
                    <a href="alinhamentos-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableAli' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap">Data Revisão</th>
                                <th scope="col" class="text-center text-nowrap" > Placa Veículo </th>
                                <th scope="col" class="text-center text-nowrap">Km Alinhamento</th>
                                <th scope="col" class="text-center text-nowrap">Tipo Alinhamento</th>
                                <th scope="col" class="text-center text-nowrap"> Ações</th>
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
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $('#tableAli').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_ali.php'
                },
                'columns': [
                    { data: 'data_alinhamento'},
                    { data: 'placa_veiculo'},
                    { data: 'km_alinhamento'},
                    { data: 'tipo_alinhamento'},
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
            });
        });

        //abrir modal edtiar
        $('#tableAli').on('click', '.editbtn', function(event){
            var table = $('#tableAli').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_single_ali.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#id').val(json.idalinhamento);
                    $('#dataAlinhamento').val(json.data_alinhamento);
                    $('#placa').val(json.placa_veiculo);
                    $('#kmAlinhamento').val(json.km_alinhamento);
                    $('#tipo').val(json.tipo_alinhamento);
                }
            })

        });
    </script>

<!-- modal visualisar e editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Alinhamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="atualiza-alinhamento.php" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="placa" readonly  class="col-form-label">Placa</label>
                            <select required name="placa" id="placa" class="form-control">
                                <option value=""></option>
                                <?php $pecas = $db->query("SELECT * FROM veiculos WHERE ativo = 1");
                                $pecas = $pecas->fetchAll();
                                foreach($pecas as $peca):
                                ?>
                                <option value="<?=$peca['placa_veiculo']?>"><?=$peca['placa_veiculo']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="kmAlinhamento" class="col-form-label">Km do Alinhamento</label>
                            <input type="text" required name="kmAlinhamento" class="form-control" id="kmAlinhamento" value="">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="dataAlinhamento" class="col-form-label">Data do Alinhamento</label>
                            <input type="date" required name="dataAlinhamento" class="form-control" id="dataAlinhamento" value="">
                        </div>
                        <div class="form-group col-md-3 ">
                            <label for="tipo" class="col-form-label">Tipo de Alinhamento </label>
                            <select name="tipo" id="tipo" class="form-control">
                                <option value=""></option>
                                <option value="Alinhamento">Alinhamento</option>
                                <option value="Balanceamento">Balanceamento</option>
                            </select>
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


<!-- modal lançar revisão -->
<div class="modal fade" id="modalRevisao" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add-alinhamento.php" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-3 espaco ">
                            <label for="descricao">Placa</label>
                            <select required name="placa" id="placa" class="form-control">
                                <option value=""></option>
                                <?php $pecas = $db->query("SELECT * FROM veiculos");
                                $pecas = $pecas->fetchAll();
                                foreach($pecas as $peca):
                                ?>
                                <option value="<?=$peca['placa_veiculo']?>"><?=$peca['placa_veiculo']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3 espaco ">
                            <label for="kmAlinhamento">Km do Alinhamento </label>
                            <input type="text" required name="kmAlinhamento" id="kmAlinhamento" class="form-control">
                        </div>
                        <div class="form-group col-md-3 espaco ">
                            <label for="dataAlinhamento">Data do Alinhamento </label>
                            <input type="date" required name="dataAlinhamento" id="dataAlinhamento" class="form-control">
                        </div>
                        <div class="form-group col-md-3 espaco ">
                            <label for="tipo">Tipo </label>
                            <select name="tipo" id="tipo" class="form-control">
                                <option value=""></option>
                                <option value="Alinhamento">Alinhamento</option>
                                <option value="Balanceamento">Balanceamento</option>
                            </select>
                        </div>
                    </div>    
            </div>
            <div class="modal-footer">
                    <button type="submit" name="analisar" class="btn btn-primary">Lançar</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>