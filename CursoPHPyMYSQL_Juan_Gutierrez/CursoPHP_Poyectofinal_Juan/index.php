<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/ion-rangeslider/css/ion.rangeSlider.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            height: 100%;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 270px;
            padding: 20px;
            background-color: #00B4AF;
            color: white;
        }
        .content {
            margin-left: 270px; 
            padding: 20px;
        }
        .results {
            margin-top: 20px;
        }
        .header-line {
            background-color: #00B4AF;
            color: white;
            padding: 10px 0;
            text-align: center;
            font-size: 24px;
        }
    </style>

</head>
<body>
    <div class="sidebar">
        <h2>Buscador</h2>
        <div class="container">
            <div class="form-container">
                <form id="searchForm">
                    <div class="mb-3">
                        <label for="ciudad" class="form-label">Ciudad:</label>
                        <select name="ciudad" id="ciudad" class="form-select" required>
                            <option value="">Elige una Ciudad</option>
                            <?php
                            $data = json_decode(file_get_contents('data-1.json'), true);
                            $ciudades = array_unique(array_column($data, 'Ciudad'));
                            foreach ($ciudades as $ciudad) {
                                echo "<option value='$ciudad'>$ciudad</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo:</label>
                        <select name="tipo" id="tipo" class="form-select" required>
                            <option value="">Elige un Tipo</option>
                            <?php
                            $tipos = array_unique(array_column($data, 'Tipo'));
                            foreach ($tipos as $tipo) {
                                echo "<option value='$tipo'>$tipo</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <!-- RANGO DE PRECIOS CON IONSLIDER -->
                    <div class="mb-3">
                        <label for="price_range" class="form-label">Rango de precios:</label>
                        <input type="text" id="price_range" name="price_range" class="form-control" value=""/>
                    </div>

                    <div class="mb-2">
                        <button type="submit" class="btn btn-danger">Buscar</button>
                        <button type="button" id="showAll" class="btn btn-success">Mostrar Todo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div style="color: black;" class="header-line"> Resultados del Buscador: </div>

    <div class="content">
        
        <div id="results" class="results">
            <!-- LOS RESULTADOS DE BUSCAR.PHP APARECERAN AQUI -->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ion-rangeslider/js/ion.rangeSlider.min.js"></script>

    <script>
        $(document).ready(function () {
            var data = <?= json_encode($data) ?>;
            var precios = data.map(function(item) {
                return parseInt(item.Precio.replace(/\D/g,''));
            });
            var min = Math.min(...precios);
            var max = Math.max(...precios);
            $("#price_range").ionRangeSlider({
                type: "double",
                grid: false,
                min: min,
                max: max,
                from: min,
                to: max,
                prefix: "$"
            });
            /////Funcion ajax para mostrar los resultados de buscar.php
            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                var priceRange = $("#price_range").data("ionRangeSlider");
                $.ajax({
                    type: 'GET',
                    url: 'buscar.php',
                    data: {
                        precio_min: priceRange.result.from,
                        precio_max: priceRange.result.to,
                        ciudad: $('#ciudad').val(),
                        tipo: $('#tipo').val()
                    },
                    success: function(response) {
                        $('#results').html(response);
                    }
                });
            });
            /////funcion ajax para mostrar todo con el boto "Todo"
            $('#showAll').on('click', function() {
                $.ajax({
                    type: 'GET',
                    url: 'buscar.php',
                    data: { mostrar_todo: true },
                    success: function(response) {
                        $('#results').html(response);
                    }
                });
            });
        });
    </script>
</body>
</html>