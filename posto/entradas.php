<?php

session_start();
require("../conexao.php");

$idModudulo = 13;
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

    <!-- select02 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</head>
<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/combustivel-entrada.png" alt="">
                </div>
                <div class="title">
                    <h2>Entrada</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <div class="area-opcoes-button">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalEntrada" data-whatever="@mdo" name="idpeca">Nova Entrada</button>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalAjuste" data-whatever="@mdo" name="idpeca">Novo Ajuste </button>
                    </div>
                    <a href="entradas-xls.php" ><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableComb' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap">Filial</th>
                                <th scope="col" class="text-center text-nowrap">ID</th>
                                <th scope="col" class="text-center text-nowrap">Data Entrada</th>
                                <th scope="col" class="text-center text-nowrap">NF</th>
                                <th scope="col" class="text-center text-nowrap">Valor por Litro</th>
                                <th scope="col" class="text-center text-nowrap">Litros</th>
                                <th scope="col" class="text-center text-nowrap">Valor Total Comb.</th>
                                <th scope="col" class="text-center text-nowrap">Frete</th>
                                <th scope="col" class="text-center text-nowrap">Comb. + Frete</th>
                                <th scope="col" class="text-center text-nowrap">Frete/Litro</th>
                                <th scope="col" class="text-center text-nowrap">(R$) Litro Final</th>
                                <th scope="col" class="text-center text-nowrap">Fornecedor</th>
                                <th scope="col" class="text-center text-nowrap">Qualidade</th>
                                <th scope="col" class="text-center text-nowrap">Situação</th>
                                <th scope="col" class="text-center text-nowrap"> Usuário que Lançou </th>
                                <th scope="col" class="text-center text-nowrap"> Ações  </th>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    <!-- sweert alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function(){
            $('#tableComb').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_combustivel.php'
                },
                'columns': [
                    { data: 'filial'},
                    { data: 'idcombustivel_entrada' },
                    { data: 'data_entrada' },
                    { data: 'nf'},
                    { data: 'valor_litro' },
                    { data: 'total_litros' },
                    {data: 'valor_comb'},
                    { data: 'frete'},
                    { data: 'valor_total' },
                    { data: 'frete_litro'},
                    { data: 'vl_litro_final'},
                    { data: 'nome_fantasia' },
                    { data: 'qualidade' },
                    { data: 'situacao' },
                    { data: 'nome_usuario' },
                    { data: 'acoes' },
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "aoColumnDefs":[
                    {'bSortable':false, 'aTargets':[9]}
                ],
                "order":[
                    1, 'desc'
                ]
            });
        });

        //abrir modal
        $('#tableComb').on('click', '.editbtn', function(event){
            var table = $('#tableComb').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_entrada.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#id').val(json.idcombustivel_entrada);
                    $('#vlLitroEdit').val(json.valor_litro);
                    $('#totalLtEdit').val(json.total_litros);
                    $('#qualidadeEdit').val(json.qualidade);
                    $('#fornecedorEdit').val(json.fornecedor);   
                    $('#freteEdit').val(json.frete);   
                    $('#situacao').val(json.situacao);     
                    $('#nfEdit').val(json.nf);                 
                    $('#dataEntrada').val(json.data_entrada);
                    $('#dataNf').val(json.data_nf)
                }
            })
        });
    </script>

<!-- modal visualisar e editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Entrada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="atualiza-entrada.php" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <input type="hidden" id="dataEntrada" name="dataEntrada">
                    <div class="form-row">
                        <div class="form-group col-md-2 espaco ">
                            <label for="vlLitro"> Valor Litro</label>
                            <input type="text" required name="vlLitro" class="form-control" id="vlLitroEdit">
                        </div>
                        <div class="form-group col-md-2 espaco ">
                            <label for="totalLt"> Total de Litros</label>
                            <input type="text" required name="totalLt" class="form-control" id="totalLtEdit">
                        </div>
                        <div class="form-group col-md-2 espaco ">
                            <label for="frete"> Frete</label>
                            <input type="text" required name="frete" class="form-control" id="freteEdit">
                        </div>
                        <div class="form-group col-md-2 espaco ">
                            <label for="nf">NF</label>
                            <input type="text" required name="nf" class="form-control" id="nfEdit">
                        </div>
                        <div class="form-group col-md-2 espaco ">
                            <label for="dataNf">Data NF</label>
                            <input type="date" required name="dataNf" class="form-control" id="dataNf">
                        </div>
                        <div class="form-group col-md-3 espaco ">
                            <label for="qualidade"> Qualidade do Combustível</label>
                            <input type="text" required name="qualidade" class="form-control" id="qualidadeEdit">
                        </div>
                        <div class="form-group col-md-3 espaco ">
                            <label for="fornecedor"> Fornecedor </label>
                            <select required name="fornecedor" id="fornecedorEdit" class="form-control">
                                <option value=""></option>
                                <?php $fornecedores = $db->query("SELECT * FROM fornecedores");
                                $fornecedores = $fornecedores->fetchAll();
                                foreach($fornecedores as $fornecedor):
                                ?>
                                <option value="<?=$fornecedor['id']?>"><?= $fornecedor['id']." - ". $fornecedor['nome_fantasia'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3 espaco ">
                            <label for="situacao"> Situação </label>
                            <select required name="situacao" id="situacao" class="form-control">
                                <option value=""></option>
                                <option value="Reprovado">Reprovado</option>
                                <option value="Aprovado">Aprovado</option>
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


<!-- MODAL lançamento de entrada -->
<div class="modal fade" id="modalEntrada" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lançar Entrada</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add-combustivel.php" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-2 espaco ">
                            <label for="vlLitro"> Valor Litro</label>
                            <input type="text" required name="vlLitro" class="form-control" id="vlLitro">
                        </div>
                        <div class="form-group col-md-2 espaco ">
                            <label for="totalLt"> Total de Litros</label>
                            <input type="text" required name="totalLt" class="form-control" id="totalLt">
                        </div>
                        <div class="form-group col-md-2 espaco ">
                            <label for="frete">Frete</label>
                            <input type="text" required name="frete" class="form-control" id="frete">
                        </div>
                        <div class="form-group col-md-2 espaco ">
                            <label for="nf">NF</label>
                            <input type="text" required name="nf" class="form-control" id="nf">
                        </div>
                        <div class="form-group col-md-2 espaco ">
                            <label for="dataNf">Data NF</label>
                            <input type="date" required name="dataNf" class="form-control" id="dataNf">
                        </div>
                        <div class="form-group col-md-3 espaco ">
                            <label for="qualidade"> Qualidade do Combustível</label>
                            <input type="text" required name="qualidade" class="form-control" id="qualidade">
                        </div>
                        <div class="form-group col-md-3 espaco ">
                            <label for="fornecedor"> Fornecedor </label>
                            <select required name="fornecedor" id="fornecedor" class="form-control">
                                <option value=""></option>
                                <?php $fornecedores = $db->query("SELECT * FROM fornecedores");
                                $fornecedores = $fornecedores->fetchAll();
                                foreach($fornecedores as $fornecedor):
                                ?>
                                <option value="<?=$fornecedor['id']?>"><?= $fornecedor['id']." - ". $fornecedor['nome_fantasia']?></option>
                                <?php endforeach; ?>
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
<!-- FIM MODAL lançamento de entrada-->


<!-- modal ajuste de combustivel -->
<div class="modal fade" id="modalAjuste" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajuste de Combustível</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add-ajuste_entrada.php" method="post">
                    <div class="form-row">
                        
                        <div class="form-group col-md-12 espaco ">
                            <label for="ajustLt"> Diferença de combustível em litros</label>
                            <input type="text" required name="ajustLt" class="form-control" id="ajustLt">
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

<script src="../assets/js/jquery.mask.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    jQuery(function($){
        $("#vlLitro").mask('###0,0000', {reverse: true});
        $("#totalLt").mask('###0,00', {reverse: true});
        $("#frete").mask('###0,00', {reverse: true});
        $('#nf').mask('0000000000');
        $("#vlLitroEdit").mask('###0,0000', {reverse: true});
        $("#totalLtEdit").mask('###0,00', {reverse: true});
        $("#freteEdit").mask('###0,00', {reverse: true});
        $('#nfEdit').mask('0000000000');
        $('#ajustLt').mask('###0,00', {reverse: true});
    })

    $(document).ready(function(){
        $('#fornecedor').select2({
            width: '100%',
            dropdownParent:"#modalEntrada",
            theme: 'bootstrap4'
        });
    });

    function confirmaAprovacao(id, litros){
        Swal.fire({
            title: 'Tem certeza?',
            text: 'Você realmente deseja Aprovar esta Entrada?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, Aprovar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Se o usuário confirmar, redirecione para a página de exclusão
                window.location.href = 'aprovar-entrada.php?idEntrada=' + id+ '&lt='+litros;
            }
        });
    }

    function confirmaDelete(id){
        Swal.fire({
            title: 'Tem certeza?',
            text: 'Você realmente deseja excluir esta Entrada?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, Excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Se o usuário confirmar, redirecione para a página de exclusão
                window.location.href = 'excluir-entrada.php?idEntrada=' + id;
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