<?php

session_start();
require("../conexao.php");

$idModudulo = 14;
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

</head>
<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/rotas.png" alt="">
                </div>
                <div class="title">
                    <h2> Rotas Supervisores</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <a href="rotas-supervisores-csv.php"><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableRotas' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>

                                <th scope="col" class="text-center text-nowrap" >Data Saída </th>
                                <th scope="col" class="text-center text-nowrap" >Supervisor</th>
                                <th scope="col" class="text-center text-nowrap">Placa</th>
                                <th scope="col" class="text-center text-nowrap">Data Chegada</th>
                                <th scope="col" class="text-center text-nowrap">Velocidade Máxima</th>
                                <th scope="col" class="text-center text-nowrap" > Nº de Visitas </th>
                                <th scope="col" class="text-center text-nowrap" >RCA1</th>
                                <th scope="col" class="text-center text-nowrap">RCA2</th>
                                <th scope="col" class="text-center text-nowrap">Cidades</th>
                                <th scope="col" class="text-center text-nowrap">Horas de Alomoço</th>
                                <th scope="col" class="text-center text-nowrap" >Obs. </th>
                                <th scope="col" class="text-center text-nowrap">Usuário</th>
                                <th scope="col" class="text-center text-nowrap">Ações</th>
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
            $('#tableRotas').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pesq_rotas.php'
                },
                'columns': [
                    { data: 'saida'},
                    { data: 'nome_supervisor' },
                    { data: 'placa_veiculo' },
                    { data: 'chegada'},
                    { data: 'velocidade_max' },
                    { data: 'qtd_visitas'},
                    { data: 'rca01' },
                    { data: 'rca02' },
                    { data: 'cidades' },
                    { data: 'hora_almoco'},
                    { data: 'obs' },
                    { data: 'nome_usuario' },
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "aoColumnDefs":[
                    {'bSortable':false, 'aTargets':[12]}
                ],
                "order":[[0,"desc"]]
            });
        });

        //abrir modal edtiar
        $('#tableRotas').on('click', '.editbtn', function(event){
            var table = $('#tableRotas').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_rota.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#dataSaida').val(json.saida);
                    $('#dataChegada').val(json.chegada);
                    $('#supervisor').val(json.supervisor);
                    $('#veiculo').val(json.veiculo);
                    $('#velMax').val(json.velocidade_max);
                    $('#visitas').val(json.qtd_visitas)
                    $('#rca1').val(json.rca01);
                    $('#rca2').val(json.rca02);
                    $('#cidades').val(json.cidades)
                    $('#obs').val(json.obs);
                    $('#horaAlmoco').val(json.hora_almoco);
                    $('#id').val(json.idrotas);
                }
            })
        });
    </script>

    <!-- modal buscar endereço -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Rota Supervisor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="atualiza-rotas.php" method="post">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="dataSaida">Data e Hora de Saída</label>
                                <input type="datetime-local" class="form-control" required name="dataSaida" id="dataSaida">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="dataChegada">Data e Hora de Chegada</label>
                                <input type="datetime-local" class="form-control" required name="dataChegada" id="dataChegada">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="supervisor">Supervisor</label>
                                <select name="supervisor" required id="supervisor" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $supervisores = $db->query("SELECT * FROM supervisores ORDER BY nome_supervisor ASC");
                                    $supervisores=$supervisores->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($supervisores as $supervisor):
                                    ?>
                                    <option value="<?=$supervisor['idsupervisor']?>"><?=$supervisor['nome_supervisor']?></option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="velMax">Velocidade Máxima</label>
                                <input type="text" class="form-control" required name="velMax" id="velMax" >
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="visitas">Nº de Visitas</label>
                                <input type="text" id="visitas" name="visitas" class="form-control" required>
                            </div>
                           
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="rca1"> RCA 1 </label>
                                <input type="text" class="form-control"  name="rca1" id="rca1" >
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="rca2"> RCA 2 </label>
                                <input type="text" class="form-control"  name="rca2" id="rca2">
                            </div>
                            <div class="form-group col-md-8 espaco">
                                <label for="cidades"> Cidades Visitadas </label>
                                <input type="text" class="form-control"  name="cidades" id="cidades">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="horaAlmoco">Horas de Almoço</label>
                                <input type="text" name="horaAlmoco" id="horaAlmoco" class="form-control">
                            </div>
                            <div class="form-group col-md-10 espaco">
                                <label for="obs">Obs.</label>
                                <input type="text" class="form-control" required name="obs" id="obs" >
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