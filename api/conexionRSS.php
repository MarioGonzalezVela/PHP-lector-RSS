<?php

function download($ruta)
{
    // Inicializa cURL
    $ch = curl_init();

    // Configura la URL
    curl_setopt($ch, CURLOPT_URL, $ruta);

    // Devuelve el resultado como string en lugar de imprimirlo directamente
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Sigue redirecciones (opcional, descomenta si lo necesitas)
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    // No incluir el header en la salida
    curl_setopt($ch, CURLOPT_HEADER, false);

    // Establece un timeout de 30 segundos
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    // Ejecuta la solicitud
    $salida = curl_exec($ch);

    // Verifica si hubo un error
    if ($salida === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("Error al descargar el contenido: $error");
    }

    // Cierra la conexión cURL
    curl_close($ch);

    // Devuelve el contenido descargado
    return $salida;
}
