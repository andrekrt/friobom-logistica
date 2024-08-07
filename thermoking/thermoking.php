<?php

session_start();
require("../conexao.php");

$idModudulo = 2;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $nomeUsuario = $_SESSION['nomeUsuario'];
    $filial = $_SESSION['filial'];
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
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
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
                    <img src="../assets/images/icones/thermoking.png" alt="">
                </div>
                <div class="title">
                    <h2>Thermoking</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <div class="area-opcoes-button">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newTk" data-whatever="@mdo" name="newTk">Cadastrar Thermoking</button>
                    </div>
                    <a href="thermoking-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableThermo' class='table table-striped table-bordered nowrap' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap" > Filial </th>
                                <th scope="col" class="text-center text-nowrap" > ID </th>
                                <th scope="col" class="text-center text-nowrap">Placa Veículo</th>
                                <th scope="col" class="text-center text-nowrap">Modelo Veículo</th>
                                <th scope="col" class="text-center text-nowrap">Tipo TK</th>
                                <th scope="col" class="text-center text-nowrap">Horímetro</th>
                                <th scope="col" class="text-center text-nowrap">Hora Última Revisão</th>
                                <th scope="col" class="text-center text-nowrap"> Data Última Revisão</th>
                                <th scope="col" class="text-center text-nowrap">Hora Restante </th>
                                <th scope="col" class="text-center text-nowrap"> Situação </th>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- sweert alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function(){
            $('#tableThermo').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'peq_thermo.php'
                },
                'columns': [
                    { data: 'filial'},
                    { data: 'idthermoking'},
                    { data: 'placa_veiculo'},
                    { data: 'tipo_veiculo'},
                    { data: 'tipo_tk'},
                    { data: 'hora_atual'},
                    { data: 'hora_ultima_revisao'},
                    { data: 'data_revisao'},
                    { data: 'hora_restante'},
                    { data: 'situacao'},
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "order":[
                    1, 'desc'
                ]
            });
           
        });

        //abrir modal
        $('#tableThermo').on('click', '.editbtn', function(event){
            var table = $('#tableThermo').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_thermo.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#id').val(json.idthermoking);
                    $('#placaEdit').val(json.veiculo);
                    $('#tipotk').val(json.tipo_tk);
                    $('#horaAtual').val(json.hora_atual);
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
                <form action="atualiza-thermo.php" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <div class="form-group col-md-3 espaco ">
                            <label for="descricao"> Placa </label>
                            <select required name="placa" id="placaEdit" class="form-control">
                                <option value=""></option>
                                <?php $pecas = $db->query("SELECT * FROM veiculos WHERE ativo = 1 AND filial = $filial");
                                $pecas = $pecas->fetchAll();
                                foreach($pecas as $peca):
                                ?>
                                <option value="<?=$peca['cod_interno_veiculo']?>"><?=$peca['placa_veiculo']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3 espaco ">
                            <label for="tipotk">Tipo TK </label>
                            <input type="text" required name="tipotk" id="tipotk" class="form-control">
                        </div>
                        <div class="form-group col-md-3 espaco ">
                            <label for="horaAtual">Horímetro Atual </label>
                            <input type="text" required name="horaAtual" id="horaAtual" class="form-control">
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

<!-- modal cadastrar thermoking -->
<div class="modal fade" id="newTk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add-thermoking.php" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-3 espaco ">
                            <label for="descricao"> Placa </label>
                            <select required name="placa" id="placa" class="form-control">
                                <option value=""></option>
                                <?php $pecas = $db->query("SELECT * FROM veiculos WHERE ativo = 1 AND filial = $filial ");
                                $pecas = $pecas->fetchAll();
                                foreach($pecas as $peca):
                                ?>
                                <option value="<?=$peca['cod_interno_veiculo']?>"><?=$peca['placa_veiculo']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3 espaco ">
                            <label for="tipotk">Tipo TK </label>
                            <input type="text" required name="tipotk" id="tipotk" class="form-control">
                        </div>
                        <div class="form-group col-md-3 espaco ">
                            <label for="horaAtual">Horímetro Atual </label>
                            <input type="text" required name="horaAtual" id="horaAtual" class="form-control">
                        </div>
                    </div>    
            </div>
            <div class="modal-footer">
                    <button type="submit" name="analisar" class="btn btn-primary">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#placa').select2({
            width: '100%',
            dropdownParent:"#newTk",
            theme: 'bootstrap4'
        });
      
    });

    function confirmaDelete(id){
        Swal.fire({
            title: 'Tem certeza?',
            text: 'Você realmente deseja desativar o Thermoking '+id+ '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, Desativar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Se o usuário confirmar, redirecione para a página de exclusão
                window.location.href = 'desativar-thermo.php?idTk=' + id;
            }
        });
    }
</script>

<!-- msg de sucesso ou erro -->
<?php
    // Verifique se há uma mensagem de confirmação na sessão
    if (isset($_SESSION['msg']) && $_SESSION['icon']) {
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