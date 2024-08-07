<?php

session_start();
require("../conexao.php");


$idModudulo = 22;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo, PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result > 0) && isset($_GET['id'])) {

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $idponto = filter_input(INPUT_GET, 'id');
 
    $sql = $db->prepare('SELECT * FROM motoristas_ponto WHERE idponto = :idponto');
    $sql->bindValue(':idponto', $idponto);
    $sql->execute();
    $dado = $sql->fetch(PDO::FETCH_ASSOC);
    $paradas = explode('<br>', $dado['tempo_parado']);

} else {
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon'] = 'warning';
    header("Location: pontos.php");
    exit();
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.css" />

</head>

<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/icone-nf.png" alt="">
                </div>
                <div class="title">
                    <h2> Registrar Ponto Motorista </h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <form action="atualiza-ponto.php" method="post">
                    <input type="hidden" name="mdfe" value="<?=$dado['mdfe']?>">
                    <div id="formulario">
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco ">
                                <label for="motorista"> Motorista</label>
                                <input type="text" name="motorista" class="form-control" id="motorista" value="<?=$dado['motorista'] ?>" required readonly>
                            </div>
                            <div class="form-group col-md-3 espaco ">
                                <label for="dia">Data</label>
                                <input type="date" name="dia" class="form-control" id="dia" required value="<?=$dado['data_ponto']?>">
                            </div>
                            <div class="form-group col-md-3 espaco ">
                                <label for="hrInicio"> Hora Início </label>
                                <input type="time" id="hrInicio" name="hrInicio" class="form-control" required value="<?=$dado['hora_inicio']?>">
                            </div>
                            <div class="form-group col-md-1 espaco ">
                                <label for="hrFim"> Hora Fim </label>
                                <input type="time" id="hrFim" name="hrFim" class="form-control" required value="<?=$dado['hora_final']?>">
                            </div>
                            <div style="margin: auto; margin-left: 0;">
                                <button type="button" class="btn btn-danger" id="add-parada">Adicionar Parada</button>
                            </div>
                        </div>
                        <?php   for($i=0;$i<(count($paradas)-1);$i++): 
                            preg_match_all('/(\d{2}:\d{2})/', $paradas[$i], $matches);
                        ?>
                            
                            <div class="form-row" data-id="<?=$i?>">
                                <div class="form-group col-md-4 espaco ">
                                    <label for="paradaInicio">Parada Início</label>
                                    <input type="time" name="paradaInicio[]" class="form-control" id="paradaInicio" required value="<?=$matches[0][0]?>">
                                </div>
                                <div class="form-group col-md-4 espaco ">
                                    <label for="paradaFinal">Parada Final</label>
                                    <input type="time" name="paradaFinal[]" class="form-control" id="paradaFinal" required value="<?=$matches[0][1]?>">
                                </div>
                                <div style="margin: auto; margin-left: 0;">
                                    <button type="button" class="btn btn-danger excluir" >Excluir</button>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <button type="submit" name="analisar" class="btn btn-primary">Atualizar</button>
                </form>
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
        var cont = 1;
        $('#add-parada').click(function(){
            cont++;
           
            $('#formulario').append('<div class="form-row" data-id="'+cont+'"> <div class="form-group col-md-4 espaco "> <label for="paradaInicio">Parada Início</label> <input type="time" name="paradaInicio[]" class="form-control" id="paradaInicio" required> </div> <div class="form-group col-md-4 espaco "> <label for="paradaFinal">Parada Final</label> <input type="time" name="paradaFinal[]" class="form-control" id="paradaFinal" required> </div> <div style="margin: auto; margin-left: 0;"><button type="button" class="btn btn-danger excluir" >Excluir</button> </div> </div>');
        });

        $('#formulario').on('click', '.excluir', function(){
            // alert('clicou');
            let id = $(this).closest('.form-row').data('id');
            $(this).closest('.form-row').remove();
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