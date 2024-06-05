<?php

session_start();
require("../conexao.php");

$idModudulo = 3;
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
                    <img src="../assets/images/icones/veiculo.png" alt="">
                </div>
                <div class="title">
                    <h2> Rotas Cadastradas</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="table-responsive">
                    <table id='tableRota' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap" > Filial </th>
                                <th scope="col" class="text-center text-nowrap" > Código Rota </th>
                                <th scope="col" class="text-center text-nowrap">Rota</th>
                                <th scope="col" class="text-center text-nowrap">Dia 1º Fechamento</th>
                                <th scope="col" class="text-center text-nowrap">Hora 1º Fechamento</th>
                                <th scope="col" class="text-center text-nowrap"> Dia 2º Fechamento</th>
                                <th scope="col" class="text-center text-nowrap"> Hora 2º Fechamento</th>
                                <th scope="col" class="text-center text-nowrap">Meta de Dias em Rota</th>
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
    <!-- sweert alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmaDelete(id){
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Você realmente deseja excluir a Rota '+id+'?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Se o usuário confirmar, redirecione para a página de exclusão
                    window.location.href = 'excluir.php?codRotas=' + id;
                }
            });
        }

        $(document).ready(function(){
            $('#tableRota').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_rota.php'
                },
                'columns': [
                    { data: 'filial'},
                    { data: 'cod_rota' },
                    { data: 'nome_rota'},
                    { data: 'fechamento1' },
                    { data: 'hora_fechamento1' },
                    { data: 'fechamento2' },
                    { data: 'hora_fechamento2' },
                    { data: 'meta_dias' },
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
        $('#tableRota').on('click', '.editbtn', function(event){
            var table = $('#tableRota').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_single_rota.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#codRota').val(json.cod_rota);
                    $('#nomeRota').val(json.nome_rota);
                    $('#fechamento1').val(json.fechamento1);
                    $('#horaFechamento1').val(json.hora_fechamento1);
                    $('#fechamento2').val(json.fechamento2);
                    $('#horaFechamento2').val(json.hora_fechamento2);
                    $('#metaDias').val(json.meta_dias);
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
                        <div class="form-group col-md-2">
                            <label for="codRota"   class="col-form-label">Código Rota</label>
                            <input type="text" readonly name="codRota" id="codRota" class="form-control"> 
                        </div>
                        <div class="form-group col-md-3">
                            <label for="nomeRota" class="col-form-label">Rota</label>
                            <input type="text" required name="nomeRota" class="form-control" id="nomeRota" value="">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fechamento1" class="col-form-label">1º Fechamentos</label>
                            <select name="fechamento1" id="fechamento1" class="form-control">
                                <option value="Segunda-Feira">Segunda-Feira</option>
                                <option value="Terça-Feira">Terça-Feira</option>
                                <option value="Quarta-Feira">Quarta-Feira</option>
                                <option value="Quinta-Feira">Quinta-Feira</option>
                                <option value="Sexta-Feira">Sexta-Feira</option>
                                <option value="Sábado">Sábado</option>
                                <option value="Segunda à Sexta">Segunda à Sexta</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3 ">
                            <label for="horaFechamento1" class="col-form-label">Hora 1º Fechamento</label>
                            <input type="time" name="horaFechamento1" id="horaFechamento1" value="" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="fechamento2" class="col-form-label">2º Fechamentos</label>
                            <select name="fechamento2" id="fechamento2" class="form-control">
                                <option value="Segunda-Feira">Segunda-Feira</option>
                                <option value="Terça-Feira">Terça-Feira</option>
                                <option value="Quarta-Feira">Quarta-Feira</option>
                                <option value="Quinta-Feira">Quinta-Feira</option>
                                <option value="Sexta-Feira">Sexta-Feira</option>
                                <option value="Sábado">Sábado</option>
                                <option value="Segunda à Sexta">Segunda à Sexta</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3 ">
                            <label for="horaFechamento2" class="col-form-label">Hora 1º Fechamento</label>
                            <input type="time" name="horaFechamento2" id="horaFechamento2" value="" class="form-control">
                        </div>
                        <div class="form-group col-md-3 ">
                            <label for="metaDias" class="col-form-label">Meta de Dias em Rota</label>
                            <input type="text" name="metaDias" id="metaDias" value="" class="form-control">
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