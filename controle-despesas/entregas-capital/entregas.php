<?php

session_start();
require("../../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==1 || $_SESSION['tipoUsuario'] == 99 || $_SESSION['tipoUsuario']==10) {

   
} else {
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../../index.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entregas</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../../assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="../../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- arquivos para datatable -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.css"/>

</head>
<body>
    <div class="container-fluid corpo">
        <?php require('../../menu-lateral02.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                    <div class="icone-menu-superior">
                        <img src="../../assets/images/icones/despesas.png" alt="">
                    </div>
                    <div class="title">
                        <h2> Entregas Capital </h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <a href="entregas-xls.php" ><img src="../../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableCap' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Carga  </th>
                                <th scope="col" class="text-center">Sequência </th>
                                <th scope="col" class="text-center"> Motorista </th>
                                <th scope="col" class="text-center"> Veículo </th>
                                <th scope="col" class="text-center"> Entregas Restantes </th>
                                <th scope="col" class="text-center"> Tempo em Rota </th>
                                <th scope="col" class="text-center">Km Rodado </th>
                                <th scope="col" class="text-center">Valor Abastecido </th>
                                <th scope="col" class="text-center">Média de Consumo </th>
                                <th scope="col" class="text-center">Gastos </th>
                                <th scope="col" class="text-center">Lançado </th>
                                <th scope="col" class="text-center"> Ações </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/menu.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $('#tableCap').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_cap.php'
                },
                'columns': [
                    { data: 'carga' },
                    { data: 'sequencia'},
                    { data: 'nome_motorista' },
                    { data: 'placa_veiculo' },
                    { data: 'qtd_falta' },
                    { data: 'hr_rota' },
                    { data: 'km_rodado' },
                    { data: 'vl_abastec' },
                    { data: 'media_consumo'},
                    { data: 'outros_gastos'},
                    { data: 'nome_usuario'},
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "aoColumnDefs":[
                    {'bSortable':false, 'aTargets':[11]}
                ],
            });
        });


        //abrir modal edtiar
        $('#tableCap').on('click', '.editbtn', function(event){
            var table = $('#tableCap').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_single_cap.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#idEntrega').val(json.identregas_capital);
                    $('#data').val(json.data_atual);
                    $('#carga').val(json.carga);
                    $('#sequencia').val(json.sequencia);
                    $('#motorista').val(json.motorista);
                    $('#veiculo').val(json.veiculo);
                    $('#defeito').val(json.defeito_carro);
                    $('#nEntregas').val(json.qtd_total);
                    $('#nEntregue').val(json.qtd_entregue);
                    $('#nRestante').val(json.qtd_falta);
                    $('#hrSaida').val(json.hr_saida);
                    $('#hrChegada').val(json.hr_chegada);
                    $('#hrRota').val(json.hr_rota);
                    $('#kmSaida').val(json.km_saida);
                    $('#kmChegada').val(json.km_chegada);
                    $('#kmRodado').val(json.km_rodado);
                    $('#ltAbast').val(json.lt_abastec);
                    $('#vlAbast').val(json.vl_abastec);
                    $('#mediaConsumo').val(json.media_consumo);
                    $('#diariaMot').val(json.diaria_motorista);
                    $('#diariaAux').val(json.diaria_auxiliar);
                    $('#outrosGastos').val(json.outros_gastos);
                }
            })
        }); 
    </script>

<!-- modal visualisar e editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Entrega</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="atualiza-entregas.php" method="post">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <div class="form-group col-md-1">
                            <label for="idEntrega" class="col-form-label">ID</label>
                            <input type="text" readonly class="form-control" name="idEntrega" id="idEntrega" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="data" class="col-form-label">Data</label>
                            <input type="date" name="data" id="data" class="form-control" value="">
                        </div>  
                        <div class="form-group col-md-2">
                            <label for="carga" class="col-form-label">Carga</label>
                            <input required type="text" name="carga" id="carga" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-1">
                            <label class="col-form-label" for="sequencia">Sequência</label>
                            <input required type="text" required value="" name="sequencia" class="form-control" id="sequencia">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-form-label" for="motorista">Motorista</label>
                            <select required name="motorista" class="form-control" id="motorista">
                                <?php

                                $sql = $db->query("SELECT * FROM motoristas");
                                $motoristas = $sql->fetchAll();
                                foreach ($motoristas as $motorista):
                                ?>
                                    <option value="<?=$motorista['cod_interno_motorista'] ?>"><?=$motorista['nome_motorista'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-form-label" for="veiculo">Veículo</label>
                            <select required name="veiculo" class="form-control" id="veiculo">
                                <option value=""></option>
                                <?php

                                $sql = $db->query("SELECT * FROM veiculos");
                                $motoristas = $sql->fetchAll();
                                foreach ($motoristas as $motorista):
                                ?>
                                    <option value="<?=$motorista['cod_interno_veiculo'] ?>"><?=$motorista['placa_veiculo'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label class="col-form-label" for="defeito">Carro c/ Defeito</label>
                            <select required name="defeito" class="form-control" id="defeito">
                                <option value="SIM">SIM</option>
                                <OPTIon value="NÃO">NÃO</OPTIon>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-form-label" for="nEntregas">Nº Entregas</label>
                            <input type="text" name="nEntregas" required id="nEntregas" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-form-label" for="nEntregue">Nº Entregue</label>
                            <input type="text" name="nEntregue" required id="nEntregue" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-form-label" for="nRestante">Entregas Restante</label>
                            <input type="text" name="nRestante" readonly id="nRestante" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-form-label" for="hrSaida">Hora de Saída</label>
                            <input type="time" name="hrSaida"  id="hrSaida" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-form-label" for="hrChegada">Hora de Chegada</label>
                            <input type="time" name="hrChegada"  id="hrChegada" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label class="col-form-label" for="hrRota">Tempo em Rota</label>
                            <input type="time" readonly name="hrRota"  id="hrRota" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-1">
                            <label class="col-form-label" for="kmSaida">Km Saída</label>
                            <input type="text" required name="kmSaida"  id="kmSaida" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-form-label" for="kmChegada">Km Chegada</label>
                            <input type="text" required name="kmChegada"  id="kmChegada" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-1">
                            <label class="col-form-label" for="kmRodado">Km Rodado</label>
                            <input type="text" readonly name="kmRodado"  id="kmRodado" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-form-label" for="vlAbast">Valor Abastecido</label>
                            <input type="text" required name="vlAbast"  id="vlAbast" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-form-label" for="ltAbast">Litros Abastecido</label>
                            <input type="text" required name="ltAbast"  id="ltAbast" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label class="col-form-label" for="mediaConsumo">Média Consumo</label>
                            <input type="text" readonly name="mediaConsumo"  id="mediaConsumo" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-form-label" for="diariaMot">Diária Motorista(R$)</label>
                            <input type="text" required name="diariaMot"  id="diariaMot" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-form-label" for="diariaAux">Diária Auxiliar(R$)</label>
                            <input type="text" required name="diariaAux"  id="diariaAux" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-form-label" for="outrosGastos">Outros Gastos(R$)</label>
                            <input type="text" required name="outrosGastos"  id="outrosGastos" class="form-control" value="">
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

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/menu.js"></script>
<script src="../../assets/js/jquery.mask.js"></script>
<script>
    $('#vlAbast').mask('#.##0,00', {reverse: true});
    $('#ltAbast').mask('#.##0,00', {reverse: true});
    $('#diariaMot').mask('#.##0,00', {reverse: true});
    $('#diariaAux').mask('#.##0,00', {reverse: true});
    $('#outrosGastos').mask('#.##0,00', {reverse: true});
    $('#consumo').mask('#.##0,00', {reverse: true})
</script>
</body>
</html>