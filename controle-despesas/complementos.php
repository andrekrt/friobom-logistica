<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] != 4) {

   
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>
<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/despesas.png" alt="">
                </div>
                <div class="title">
                    <h2> Complementos</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <div class="area-opcoes-button">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalComplemento" data-whatever="@mdo" name="idpeca">Novo Complemento</button>
                    </div>
                    <a href="complementos-xls.php" ><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableComple' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">ID  </th>
                                <th scope="col" class="text-center">Veículo </th>
                                <th scope="col" class="text-center"> Motorista </th>
                                <th scope="col" class="text-center"> Km Saída </th>
                                <th scope="col" class="text-center"> Km Chegada </th>
                                <th scope="col" class="text-center"> Litros Abast. </th>
                                <th scope="col" class="text-center">Valor Abast. </th>
                                <th scope="col" class="text-center">Lançado </th>
                                <th scope="col" class="text-center"> Ações </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/menu.js"></script>
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#tableComple').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_comp.php'
                },
                'columns': [
                    { data: 'id_complemento' },
                    { data: 'placa_veiculo'},
                    { data: 'nome_motorista' },
                    { data: 'km_saida' },
                    { data: 'km_chegada' },
                    { data: 'litros_abast' },
                    { data: 'valor_abast' },
                    { data: 'nome_usuario' },
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "aoColumnDefs":[
                    {'bSortable':false, 'aTargets':[8]}
                ],
            });
        });


        //abrir modal edtiar
        $('#tableComple').on('click', '.editbtn', function(event){
            var table = $('#tableComple').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_single_comp.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#id').val(json.id_complemento);
                    $('#veiculo').val(json.veiculo);
                    $('#motorista').val(json.motorista);
                    $('#kmSaida').val(json.km_saida);
                    $('#kmChegada').val(json.km_chegada);
                    $('#ltAbast').val(json.litros_abast);
                    $('#vlAbast').val(json.valor_abast);
                }
            })
        }); 
    </script>

<!-- modal visualisar e editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Complemento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="atualiza-complemento.php" method="post">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="id" class="col-form-label">ID</label>
                            <input type="text" readonly name="id" class="form-control" id="id" value="">
                        </div>
                        <div class="form-group col-md-3 ">
                            <label for="veiculo" class="col-form-label">Veículo</label>
                            <select required name="veiculo" id="veiculoEdit" class="form-control">
                                <?php $pecas = $db->query("SELECT * FROM veiculos");
                                $pecas = $pecas->fetchAll();
                                foreach($pecas as $peca):
                                ?>
                                <option value="<?=$peca['cod_interno_veiculo']?>"><?=$peca['placa_veiculo']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-7">
                            <label for="motorista" class="col-form-label">Motorista</label>
                            <select required name="motorista" id="motoristaEdit" class="form-control">
                                <?php $motoristas = $db->query("SELECT * FROM motoristas");
                                $motoristas = $motoristas->fetchAll();
                                foreach($motoristas as $motorista):
                                ?>
                                <option value="<?=$motorista['cod_interno_motorista']?>"><?=$motorista['nome_motorista']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>    
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3  ">
                            <label for="kmSaida" class="col-form-label"> Km Saída</label>
                            <input type="text" value="" class="form-control" name="kmSaida" id="kmSaida">
                        </div>
                        <div class="form-group col-md-3  ">
                            <label for="kmChegada" class="col-form-label"> Km Chegada</label>
                            <input type="text" value="" class="form-control" name="kmChegada" id="kmChegada">
                        </div>
                        <div class="form-group col-md-3  ">
                            <label for="ltAbast" class="col-form-label"> Litros Abast.</label>
                            <input type="text" value=""  class="form-control" name="ltAbast" id="ltAbast">
                        </div>
                        <div class="form-group col-md-3  ">
                            <label for="vlAbast" class="col-form-label"> Valor Abast.</label>
                            <input type="text" value="" class="form-control" name="vlAbast" id="vlAbast">
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


<!-- MODAL lançamento de complemento -->
<div class="modal fade" id="modalComplemento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lançar Complemento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add-complemento.php" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-4 espaco ">
                            <label for="veiculo"> Veículo</label>
                            <select required name="veiculo" id="veiculo" class="form-control">
                                <option value=""></option>
                                
                                <?php $pecas = $db->query("SELECT * FROM veiculos");
                                $pecas = $pecas->fetchAll();
                                foreach($pecas as $peca):
                                ?>
                                <option value="<?=$peca['cod_interno_veiculo']?>"><?=$peca['placa_veiculo']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4 espaco ">
                            <label for="motorista">Motorista</label>
                            <select required name="motorista" id="motorista" class="form-control">
                                <option value=""></option>
                                <?php $motoristas = $db->query("SELECT * FROM motoristas");
                                $motoristas = $motoristas->fetchAll();
                                foreach($motoristas as $motorista):
                                ?>
                                <option value="<?=$motorista['cod_interno_motorista']?>"><?=$motorista['nome_motorista']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4 espaco ">
                            <label for="kmSaida"> Km Saída </label>
                            <input type="text" name="kmSaida" id="kmSaida" class="form-control">
                        </div>
                    </div>    
                    <div class="form-row">
                        <div class="form-group col-md-4 espaco ">
                            <label for="kmChegada"> Km Chegada</label>
                            <input type="text" class="form-control" name="kmChegada" id="kmChegada">
                        </div>
                        <div class="form-group col-md-4 espaco ">
                            <label for="ltAbast"> Litros Abast.</label>
                            <input type="text" class="form-control" name="ltAbast" id="ltAbast">
                        </div>
                        <div class="form-group col-md-4 espaco ">
                            <label for="vlAbast"> Valor Abast.</label>
                            <input type="text" class="form-control" name="vlAbast" id="vlAbast">
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
<!-- FIM MODAL lançamento de complemento-->
<script src="../assets/js/jquery.mask.js"></script>
<script>
    $('#vlAbast').mask('#.##0,00', {reverse: true});
    $('#ltAbast').mask('#.##0,00', {reverse: true});
    $(document).ready(function(){
        $('#veiculo').select2({
            width: '100%',
            dropdownParent:"#modalComplemento"
        });
        $('#motorista').select2({
            width: '100%',
            dropdownParent:"#modalComplemento"
        });
        $('#veiculoEdit').select2({
            width: '100%',
            dropdownParent:"#modalEditar"
        });
        $('#motoristaEdit').select2({
            width: '100%',
            dropdownParent:"#modalEditar"
        });
    });
</script>
</body>
</html>