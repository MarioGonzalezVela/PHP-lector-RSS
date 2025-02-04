<?php

function download($ruta)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ruta);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Permite seguir redirecciones

    $salida = curl_exec($ch);

    if ($salida === false) {
        echo "Error en cURL: " . curl_error($ch);
    }

    curl_close($ch);
    return $salida;
}

// Prueba con una URL válida
$url = "https://www.example.com"; // Cambia por una URL válida
$resultado = download($url);

if ($resultado) {
    echo "Descarga exitosa";
} else {
    echo "Error al descargar";
}
