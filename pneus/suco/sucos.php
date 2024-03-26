<?php

session_start();
require("../../conexao.php");

$idModudulo = 12;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

   
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
                    <img src="../../assets/images/icones/pneu.png" alt="">
                </div>
                <div class="title">
                    <h2>Registro de Sucos</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <a href="sucos-xls.php"><img src="../../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableSuco' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap">Nº fogo</th>
                                <th scope="col" class="text-center text-nowrap">Data de Medição</th>
                                <th scope="col" class="text-center text-nowrap">Km Veículo</th>
                                <th scope="col" class="text-center text-nowrap">Km Pneu</th>
                                <th scope="col" class="text-center text-nowrap">Carcaça</th>
                                <th scope="col" class="text-center text-nowrap">Suco 01</th>
                                <th scope="col" class="text-center text-nowrap">Suco 02</th>
                                <th scope="col" class="text-center text-nowrap">Suco 03</th>
                                <th scope="col" class="text-center text-nowrap">Suco 04</th>
                                <th scope="col" class="text-center text-nowrap">Calibragem</th>
                                <th scope="col" class="text-center text-nowrap">Registrado por</th>
                                <th scope="col" class="text-center text-nowrap">Ações</th>
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
    <!-- sweert alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function(){
            $('#tableSuco').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_suco.php'
                },
                'columns': [
                    { data: 'num_fogo' },
                    { data: 'data_medicao'},
                    { data: 'km_veiculo' },
                    { data: 'km_pneu' },
                    { data: 'carcaca' },
                    { data: 'suco01'},
                    { data: 'suco02'},
                    { data: 'suco03'},
                    { data: 'suco04'},
                    { data: 'calibragem'},
                    { data: 'nome_usuario'},
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
            });
        });


        //abrir modal edtiar
        $('#tableSuco').on('click', '.editbtn', function(event){
            var table = $('#tableSuco').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_single_suco.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#id').val(json.idsucos);
                    $('#dataMedida').val(json.data_medicao);
                    $('#pneu').val(json.pneus_idpneus);
                    $('#medida').val(json.medida);
                    $('#calibMax').val(json.calibragem_maxima);
                    $('#marca').val(json.marca);
                    $('#numSerie').val(json.num_serie);
                    $('#vida').val(json.vida);
                    $('#veiculo').val(json.veiculo);
                    $('#suco01').val(json.suco01);
                    $('#suco02').val(json.suco02);
                    $('#suco03').val(json.suco03);
                    $('#suco04').val(json.suco04);
                    $('#kmVeiculo').val(json.km_veiculo);
                    $('#kmPneu').val(json.km_pneu);
                    $('#carcaca').val(json.carcaca);
                    $('#posicao').val(json.posicao_inicio);
                    $('#calibragemAtual').val(json.calibragem);
                }
            })
        }); 

        function confirmaDelete(id){
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Você realmente deseja excluir este Suco?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Se o usuário confirmar, redirecione para a página de exclusão
                    window.location.href = 'excluir-suco.php?idsuco=' + id;
                }
            });
        }
    </script>

<!-- modal visualisar e editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Suco</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="atualiza-suco.php" method="post">
                    <input type="hidden" name="trid" id="trid" value="">
                    <input type="hidden" id="id" name="idsuco">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="dataMedida">Data de Medição</label>
                            <input type="datetime-local" name="dataMedida" id="dataMedida" readonly class="form-control" value="">
                        </div>
                        <div class="form-group col-md-1">
                            <label for="dataMedida">Nº Fogo</label>
                            <select required name="pneu" id="pneu" class="form-control">
                                <?php
                                $sql=$db->query("SELECT * FROM pneus");
                                $pneus = $sql->fetchAll();
                                foreach($pneus as $pneu):
                                ?>
                                <option value="<?=$pneu['idpneus'] ?>"><?=$pneu['num_fogo']?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="medida">Medida</label>
                            <input type="text" name="medida" id="medida" readonly class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="calibMax">Calibragem Máxima</label>
                            <input type="text" name="calibMax" id="calibMax" readonly class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="marca">Marca</label>
                            <input type="text" name="marca" id="marca" readonly class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="numSerie">Nº</label>
                            <input type="text" name="numSerie" id="numSerie" readonly class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-1">
                            <label for="vida">Vida Pneu</label>
                            <input type="text" name="vida" id="vida" readonly class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="veiculo">Veículo</label>
                            <input type="text" name="veiculo" id="veiculo" readonly class="form-control" value="">
                        </div>
                        <div class="form-group col-md-1">
                            <label for="kmVeiculo">Km Veículo</label>
                            <input type="text" name="kmVeiculo" id="kmVeiculo" required class="form-control" value="">
                        </div>
                        <div class="form-group col-md-1">
                            <label for="kmPneu">Km Pneu</label>
                            <input type="text" name="kmPneu" id="kmPneu" readonly class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="carcaca">Carcaça</label>
                            <input type="text" name="carcaca" id="carcaca" required class="form-control" value="">
                        </div>
                        <div class="form-group col-md-1">
                            <label for="posicao">Posição</label>
                            <input type="text" name="posicao" id="posicao" readonly class="form-control" value="">
                        </div>
                        <div class="form-group col-md-1 ">
                            <label for="suco01">Suco 01</label>
                            <input type="text" required name="suco01" class="form-control" id="suco01" value="">
                        </div>
                        <div class="form-group col-md-1 spaco">
                            <label for="suco02">Suco 02</label>
                            <input type="text" required name="suco02" class="form-control" id="suco02" value="">
                        </div>
                        <div class="form-group col-md-1 ">
                            <label for="suco03">Suco 03</label>
                            <input type="text" required name="suco03" class="form-control" id="suco03" value="">
                        </div>
                        <div class="form-group col-md-1 ">
                            <label for="suco04">Suco 04</label>
                            <input type="text" required name="suco04" class="form-control" id="suco04" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="calibragemAtual">Calibragem Atual</label>
                            <input type="text" name="calibragemAtual" id="calibragemAtual" class="form-control" value="">
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