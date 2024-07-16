<?php

session_start();
require("../conexao.php");

$idModudulo = 4;
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
                    <h2> Motoristas Cadastrados</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <a href="motoristas-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableMot' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center"> Filial </th>
                                <th scope="col" class="text-center"> Código Motorista </th>
                                <th scope="col" class="text-center"> Nome Motorista </th>
                                <th scope="col" class="text-center"> Cidade Base</th>
                                <th scope="col" class="text-center"> CNH </th>
                                <th scope="col" class="text-center"> Vencimento CNH </th>
                                <th scope="col" class="text-center"> Toxicológico </th>
                                <th scope="col" class="text-center"> Validade Toxicológico </th>
                                <th scope="col" class="text-center">Salário</th>
                                <th scope="col" class="text-center">Cadastro no Fusion?</th>
                                <th scope="col" class="text-center"> Ações</th>
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
        $(document).ready(function(){
            $('#tableMot').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_mot.php'
                },
                'columns': [
                    { data: 'filial'},
                    { data: 'cod_interno_motorista' },
                    { data: 'nome_motorista'},
                    { data: 'cidade_base'},
                    { data: 'cnh' },
                    { data: 'validade_cnh' },
                    { data: 'toxicologico' },
                    { data: 'validade_toxicologico' },
                    { data: 'salario' },
                    { data: 'fusion'},
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "aoColumnDefs":[
                    {'bSortable':false, 'aTargets':[9]}
                ],
            });
        });

        //abrir modal edtiar
        $('#tableMot').on('click', '.editbtn', function(event){
            var table = $('#tableMot').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_single_mot.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#codMotorista').val(json.cod_interno_motorista);
                    $('#nomeMotorista').val(json.nome_motorista);
                    $('#cnh').val(json.cnh);
                    $('#validadeCnh').val(json.validade_cnh);
                    $('#toxicologico').val(json.toxicologico);
                    $('#validadeToxicologico').val(json.validade_toxicologico);
                    $('#salario').val(json.salario);
                    $('#base').val(json.cidade_base);
                    $('#fusion').val(json.fusion);
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
                        <div class="form-group col-md-3">
                            <label for="codMotorista"   class="col-form-label">Código Motorista</label>
                            <input type="text" readonly name="codMotorista" id="codMotorista" class="form-control"> 
                        </div>
                        <div class="form-group col-md-5">
                            <label for="nomeMotorista" class="col-form-label">Nome Motorista</label>
                            <input type="text" required name="nomeMotorista" class="form-control" id="nomeMotorista" value="">
                        </div>
                        <div class="form-group col-md-2 ">
                            <label for="base"  class="col-form-label">Cidade Base</label>
                            <select name="base" id="base" required class="form-control">
                                <option value=""></option>
                                <option value="São Luís">São Luís</option>
                                <option value="Bacabal">Bacabal</option>
                                <option value="Timon">Timon</option>
                                <option value="Teresina">Teresina</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="salario" class="col-form-label">Salário</label>
                            <input type="text" required name="salario" class="form-control" id="salario" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="cnh" class="col-form-label">CNH</label>
                            <input type="text" required name="cnh" class="form-control" id="cnh" value="">
                        </div>
                        <div class="form-group col-md-3 ">
                            <label for="validadeCnh" class="col-form-label">Vencimento CNH</label>
                            <input type="date" name="validadeCnh" id="validadeCnh" value="" class="form-control">
                        </div>
                        <div class="form-group col-md-3 ">
                            <label for="toxicologico" class="col-form-label">Toxicológico</label>
                            <select name="toxicologico" id="toxicologico" class="form-control">
                                <option value="OK">OK</option>
                                <option value="Aguardando">Aguardando</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3 ">
                            <label for="validadeToxicologico" class="col-form-label">Vencimento Toxicológico</label>
                            <input type="date" name="validadeToxicologico" id="validadeToxicologico" value="" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="fusion" class="col-form-label">Cadastro no Fusion?</label>
                            <select name="fusion" id="fusion" class="form-control">
                                <option value=""></option>
                                <option value="1">Sim</option>
                                <option value="0">Não</option>
                            </select>
                        </div>
                    </div>   
                    <div class="form-row">
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="ativo" name="ativo">
                            <label for="ativo">Desativar Mototista</label>
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
<script src="../assets/js/jquery.mask.js"></script>
<script >
    $(document).ready(function(){
        $('#salario').mask("###0,00", {reverse: true});
    });
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