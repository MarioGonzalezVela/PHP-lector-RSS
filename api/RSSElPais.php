<?php

require_once "conexionRSS.php";

$sXML = download("http://ep00.epimg.net/rss/elpais/portada.xml");
$oXML = new SimpleXMLElement($sXML);

require_once "conexionBBDD.php";

if (!$link) {
    die("Conexión a la base de datos PostgreSQL ha fallado");
} else {
    $contador = 0;
    $categoria = ["Política", "Deportes", "Ciencia", "España", "Economía", "Música", "Cine", "Europa", "Justicia"];
    $categoriaFiltro = "";

    foreach ($oXML->channel->item as $item) {
        foreach ($item->category as $cat) {
            if (in_array($cat, $categoria)) {
                $categoriaFiltro .= "[" . $cat . "]";
            }
        }

        $fPubli = strtotime($item->pubDate);
        $new_fPubli = date('Y-m-d', $fPubli);

        $content = $item->children("content", true);
        $encoded = $content->encoded;

        $sql = "SELECT link FROM elpais WHERE link = $1";
        $result = pg_query_params($link, $sql, [$item->link]);

        if (pg_num_rows($result) == 0 && !empty($categoriaFiltro)) {
            $sql = "INSERT INTO elpais (title, link, description, category, pub_date, content) VALUES ($1, $2, $3, $4, $5, $6)";
            pg_query_params($link, $sql, [$item->title, $item->link, $item->description, $categoriaFiltro, $new_fPubli, $encoded]);
        }

        $categoriaFiltro = "";
    }
}

pg_close($link);
