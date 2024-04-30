<?php

$motorista =filter_input(INPUT_POST,'motorista');
$valor = filter_input(INPUT_POST, 'valor');
$rota = filter_input(INPUT_POST, 'rota');

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assinatura</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        #signature-pad {
            border: 2px solid black;
            width: 100%;
            height: 200px;
        }
    </style>
</head>

<body>

    <h2>Assinatura do Motorista:</h2>

    <div id="signature-pad" >
        <canvas height="200"></canvas>
    </div>
    <form action="add-vale.php" id="form-assinatura" method="post" style="display: block;">
        <div id="dinamico" style="display: none;">
            <input type="hidden" name="motorista" value="<?= $motorista ?>">
            <input type="hidden" name="rota" value="<?=$rota?>">
            <input type="hidden" name="valor" value="<?=$valor?>">
        </div>

        <input type="hidden" name="assinatura" id="assinatura">
        <div class="mt-2 ml-3">
            <a id="clear-button" class="btn btn-danger btn-sm" style="color: #FFF;">Limpar</a>
            <button id="save-button" type="submit" class="btn btn-info btn-sm">Salvar</button>
        </div>
       
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
    <script>
        window.onload = function() {
            var canvas = document.querySelector("canvas");
            var area = document.getElementById('signature-pad');
            // Defina a largura do canvas como a largura da janela do navegador
            canvas.width = area.offsetWidth-7;
            var signaturePad = new SignaturePad(canvas,{
                minWidth: 1,
                maxWidth: 1,
            });

            document.getElementById("clear-button").addEventListener("click", function() {
                signaturePad.clear();
            });

            document.getElementById("save-button").addEventListener("click", function() {
                if (signaturePad.isEmpty()) {
                    alert("Por favor, assine antes de salvar.");
                } else {
                    var dataURL = signaturePad.toDataURL();
                    document.getElementById('assinatura').value = dataURL;
                    document.getElementById('save-button').submit();

                }
            });
        };
    </script>
</body>

</html>