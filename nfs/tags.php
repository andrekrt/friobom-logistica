<?php

session_start();
require("../conexao.php");

$idModudulo = 21;
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
                    <img src="../assets/images/icones/icon-tag.png" alt="">
                </div>
                <div class="title">
                    <h2> Tags NF's</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <div class="area-opcoes-button">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalTag" data-whatever="@mdo" name="idpeca">Nova Tag</button>
                    </div>
                    <a href="motoristas-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableTag' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center"> ID </th>
                                <th scope="col" class="text-center"> Coluna </th>
                                <th scope="col" class="text-center"> Descrição</th>
                                <th scope="col" class="text-center"> Legenda </th>
                                <th scope="col" class="text-center"> Cor </th>
                                <th scope="col" class="text-center"> Valor </th>
                                <th scope="col" class="text-center"> Ações</th>
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
    <!-- sweert alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function(){
            $('#tableTag').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pesq_tags.php'
                },
                'columns': [
                    { data: 'idtags' },
                    { data: 'nome_coluna'},
                    { data: 'descricao'},
                    { data: 'legenda' },
                    { data: 'cor' },
                    { data: 'valor' },
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "aoColumnDefs":[
                    {'bSortable':false, 'aTargets':[6]}
                ],
            });
        });

        //abrir modal edtiar
        $('#tableTag').on('click', '.editbtn', function(event){
            var table = $('#tableTag').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_tag.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#id').val(json.idtags);
                    $('#coluna').val(json.nome_coluna);
                    $('#descricao').val(json.descricao);
                    $('#valor').val(json.valor);
                    $('#legenda').val(json.legenda);
                    $('#cor').val(json.cor);
                  
                }
            })
        });

        function confirmaDelete(id){
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Você realmente deseja excluir esta Tag?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Se o usuário confirmar, redirecione para a página de exclusão
                    window.location.href = 'excluir-tag.php?id=' + id;
                }
            });
        }
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
                <form action="atualiza-tag.php" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <div class="form-group col-md-3 espaco ">
                            <label for="coluna"> Coluna</label>
                            <select name="coluna" id="coluna" class="form-control" required>
                                <option value=""></option>
                                <option value="cnpj">CNPJ</option>
                                <option value="cfop">CFOP</option>
                                <option value="status_carga">Status Carga</option>
                                <option value="divergia">Divergência nos Produtos</option>
                            </select>
                        </div>
                        <div class="form-group col-md-5 espaco ">
                            <label for="descricao"> Descrição Coluna</label>
                            <input type="text"  name="descricao" class="form-control" id="descricao" required>
                        </div>
                        <div class="form-group col-md-3 espaco ">
                            <label for="legenda"> Legenda </label>
                            <input type="text" id="legenda" name="legenda" class="form-control" required> 
                        </div>
                        <div class="form-group col-md-1 espaco ">
                            <label for="cor"> Cor </label>
                            <input type="color" id="cor" name="cor" class="form-control" required> 
                        </div>
                    </div>    
                    <div class="form-row">
                        <div class="form-group col-md-4 espaco ">
                            <label for="valor">Valor Coluna</label>
                            <input type="text"  name="valor" class="form-control" id="valor" required>
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

<!-- modal cadastrar -->
<div class="modal fade" id="modalTag" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastrar Tag</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add-tag.php" method="post">
                    <div id="formulario">
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco ">
                                <label for="coluna"> Coluna</label>
                                <select name="coluna" id="coluna" class="form-control" required>
                                    <option value=""></option>
                                    <option value="cnpj">CNPJ</option>
                                    <option value="cfop">CFOP</option>
                                    <option value="status_carga">Status Carga</option>
                                    <option value="divergia">Divergência nos Produtos</option>
                                </select>
                            </div>
                            <div class="form-group col-md-5 espaco ">
                                <label for="descricao"> Descrição Coluna</label>
                                <input type="text"  name="descricao" class="form-control" id="descricao" required>
                            </div>
                            <div class="form-group col-md-3 espaco ">
                                <label for="legenda"> Legenda </label>
                                <input type="text" id="legenda" name="legenda" class="form-control" required> 
                            </div>
                            <div class="form-group col-md-1 espaco ">
                                <label for="cor"> Cor </label>
                                <input type="color" id="cor" name="cor" class="form-control" required> 
                            </div>
                        </div>    
                        <div class="form-row">
                            <div class="form-group col-md-4 espaco ">
                                <label for="valor">Valor Coluna</label>
                                <input type="text"  name="valor[]" class="form-control" id="valor" required>
                            </div>
                            <div style="margin: auto; margin-left: 0;">
                                <button type="button" class="btn btn-danger" id="add-valor">Adicionar Valor</button>
                            </div>
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
    var cont = 1;
        $('#add-valor').click(function(){
            cont++;

            $('#formulario').append('<div class="form-row"> <div class="form-group col-md-4 espaco "> <label for="valor">Valor Coluna</label> <input type="text"  name="valor[]" class="form-control" id="valor" required> </div> </div>');
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