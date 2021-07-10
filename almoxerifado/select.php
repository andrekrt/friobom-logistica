<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>
    <div class="form-group col-md-5 espaco ">
        <label for="grupo"> Grupo </label>
        <select required name="grupo" id="grupo" class="form-control">
            <option value=""></option>
            <option value="Serviços">Serviços</option>
            <option value="Borracharia">Borracharia</option>
            <option value="Taxas/IPVA/Multas">Taxas/IPVA/Multas</option>
            <option value="Cabos">Cabos</option>
            <option value="Peças">Peças</option>
            <option value="Combustíveis/Lubrificantes">Combustíveis/Lubrificantes</option>
            <option value="Suprimentos da Borracharia">Suprimentos da Borracharia</option>
            <option value="Molas e Suspensão">Molas e Suspensão</option>
            <option value="Eletrica">Eletrica</option>
            <option value="Correias">Correias</option>
            <option value="Acessórios">Acessorios</option>
            <option value="Filtros">Filtros</option>
            <option value="Gás">Gás</option>
            <option value="Limpeza">Limpeza</option>
        </select>
    </div>

    <script>
        $(document).ready(function() {
            $('#grupo').select2();
        });
    </script>
</body>
</html>