<?php
$data = json_decode(file_get_contents('data-1.json'), true);

if (isset($_GET['mostrar_todo']) && $_GET['mostrar_todo'] == true) {
    $resultados = $data;
} else {
    $precio_min = isset($_GET['precio_min']) ? intval($_GET['precio_min']) : 0;
    $precio_max = isset($_GET['precio_max']) ? intval($_GET['precio_max']) : PHP_INT_MAX;
    $ciudad = isset($_GET['ciudad']) ? $_GET['ciudad'] : '';
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

    $resultados = [];
    foreach ($data as $item) {
        $precio = intval(str_replace(['$', ','], '', $item['Precio']));
        if ($precio >= $precio_min && $precio <= $precio_max) {
            if (($ciudad == '' || $item['Ciudad'] == $ciudad) && ($tipo == '' || $item['Tipo'] == $tipo)) {
                $resultados[] = $item;
            }
        }
    }
}

// Imagen fija para todas las propiedades
$imagen = 'https://st3.depositphotos.com/8846918/32726/i/450/depositphotos_327262348-stock-photo-house-for-sale-wooden-placard.jpg';

echo "<div style='display: flex; flex-wrap: wrap; justify-content: center;'>";
foreach ($resultados as $resultado) {
    echo "<div style='width: 45%; margin: 10px; background: white; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); overflow: hidden;'>";
    echo "<img src='$imagen' style='width: 100%; height: auto;' alt='Imagen de la propiedad'>";
    echo "<div style='padding: 15px;'>";
    echo "<p><strong>Dirección:</strong> <span style='color: blue;'>{$resultado['Direccion']}</span><br>";
    echo "<strong>Ciudad:</strong> <span style='color: blue;'>{$resultado['Ciudad']}</span><br>";
    echo "<strong>Teléfono:</strong> <span style='color: blue;'>{$resultado['Telefono']}</span><br>";
    echo "<strong>Código Postal:</strong> <span style='color: blue;'>{$resultado['Codigo_Postal']}</span><br>";
    echo "<strong>Tipo:</strong> <span style='color: blue;'>{$resultado['Tipo']}</span><br>";
    echo "<strong>Precio:</strong> <span style='color: red;'>{$resultado['Precio']}</span><br>";
    echo "</div>";
    echo "</div>";
}
echo "</div>";
