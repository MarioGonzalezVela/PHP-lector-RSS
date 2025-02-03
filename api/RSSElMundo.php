<?php

require_once "conexionRSS.php";

$sXML = download("https://e00-elmundo.uecdn.es/elmundo/rss/espana.xml");

$oXML = new SimpleXMLElement($sXML);

require_once "conexionBBDD.php";

if (pg_connection_status($link) !== PGSQL_CONNECTION_OK) {
    printf("Conexión a el periódico El Mundo ha fallado");
} else {

    $contador = 0;
    $categoria = ["Política", "Deportes", "Ciencia", "España", "Economía", "Música", "Cine", "Europa", "Justicia"];
    $categoriaFiltro = "";

    foreach ($oXML->channel->item as $item) {

        $media = $item->children("media", true);
        $description = $media->description;

        for ($i = 0; $i < count($item->category); $i++) {
            for ($j = 0; $j < count($categoria); $j++) {
                if ($item->category[$i] == $categoria[$j]) {
                    $categoriaFiltro = "[" . $categoria[$j] . "]" . $categoriaFiltro;
                }
            }
        }

        $fPubli = strtotime($item->pubDate);
        $new_fPubli = date('Y-m-d', $fPubli);

        $query = "SELECT link FROM elmundo WHERE link = $1";
        $result = pg_query_params($link, $query, [$item->link]);

        if (pg_num_rows($result) == 0 && $categoriaFiltro !== "") {
            $insertQuery = "INSERT INTO elmundo (titulo, link, descripcion, categoria, fecha_publicacion, guid) 
                            VALUES ($1, $2, $3, $4, $5, $6)";
            $insertParams = [$item->title, $item->link, $description, $categoriaFiltro, $new_fPubli, $item->guid];

            pg_query_params($link, $insertQuery, $insertParams);
        }

        $categoriaFiltro = "";
    }
}
