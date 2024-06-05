<?php

session_start();
require("../conexao.php");

$idModudulo = 5;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
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
                    <img src="../assets/images/icones/motoristas.png" alt="">
                </div>
                <div class="title">
                    <h2>Colaboradores</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <div class="area-opcoes-button">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newPagamento" data-whatever="@mdo" name="">Novo Pagamento</button>
                    </div>
                    <a href="pagamento-xls.php" ><img src="../assets/images/excel.jpg" alt=""></a>    
                </div>
                <div class="table-responsive">
                    <table id='tablePag' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center"> Filial </th>
                                <th scope="col" class="text-center"> Mês/Ano </th>
                                <th scope="col" class="text-center"> Valor  </th>
                                <th scope="col" class="text-center"> Grupo Funcionários </th>
                                <th scope="col" class="text-center"> Usuário </th>
                                <th scope="col" class="text-center"> Ações</th>
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
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    <!-- sweert alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function(){
            $('#tablePag').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pagamento.php'
                },
                'columns': [
                    { data: 'filial'},
                    { data: 'mes_ano'},
                    { data: 'pagamento' },
                    { data: 'tipo_funcionarios' },
                    { data: 'nome_usuario' },
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "order":[1, 'desc'],
                "columnDefs":[{
                    "targets":5,
                    "orderable":false
                }]
            });
        });

        //abrir modal edtiar
        $('#tablePag').on('click', '.editbtn', function(event){
            var table = $('#tablePag').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_pagamento.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#id').val(json.idpagamento);
                    $('#mesAno').val(json.mes_ano);
                    $('#pagamentoEdit').val(json.pagamento);
                    $('#funcionarios').val(json.tipo_funcionarios);
                }
            })
        });
    </script>

<!-- modal visualisar e editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Colaborador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="atualiza-pagamento.php" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="mesAno" class="col-form-label">Mês/Ano </label>
                            <input type="month" required name="mesAno" id="mesAno" class="form-control"> 
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pagamento" class="col-form-label">Folha de Pagamento </label>
                            <input type="text" required name="pagamento" class="form-control" id="pagamentoEdit" value="">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="funcionarios" class="col-form-label">Grupo de Funcionários</label>
                            <select name="funcionarios" id="funcionarios" class="form-control">
                                <option value=""></option>
                                <option value="Interno">Interno</option>
                                <option value="Motoristas">Motoristas</option>
                                <option value="Auxiliares">Auxiliares</option>
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

<!-- modal cadastrar pagamento -->
<div class="modal fade" id="newPagamento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Novo Colaborador</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add-pagamento.php" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="mesAno"   class="col-form-label">Mês/Ano </label>
                            <input type="month" required name="mesAno" id="mesAno" class="form-control"> 
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pagamento" class="col-form-label">Folha de Pagamento </label>
                            <input type="text" required name="pagamento" class="form-control" id="pagamento" value="">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="funcionarios" class="col-form-label">Grupo de Funcionários</label>
                            <select name="funcionarios" id="funcionarios" class="form-control">
                                <option value=""></option>
                                <option value="Interno">Interno</option>
                                <option value="Motoristas">Motoristas</option>
                                <option value="Auxiliares">Auxiliares</option>
                            </select>
                        </div>
                    </div>  
            </div>
            <div class="modal-footer">
                    <div class="text-center">
                            <button type="submit" class="btn btn-success">Lançar</button>
                        </div>
                </form> 
            </div>
        </div>
    </div>
</div>
<script src="../assets/js/jquery.mask.js"></script>
<script >
    $(document).ready(function(){
        $('#pagamento').mask("###0,00", {reverse: true});
        $('#pagamentoEdit').mask("###0,00", {reverse: true});
    });

    function confirmaDelete(id){
        Swal.fire({
            title: 'Tem certeza?',
            text: 'Você realmente deseja excluir este Pagamento?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Se o usuário confirmar, redirecione para a página de exclusão
                window.location.href = 'excluir-pagamento.php?id=' + id;
            }
        });
    }
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