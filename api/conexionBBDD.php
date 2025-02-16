<?php

$Repit = false;
$conn_string = "postgres://neondb_owner:npg_5hXTxH1EuvpK@ep-calm-wildflower-a2d4l9gj-pooler.eu-central-1.aws.neon.tech/neondb?sslmode=require";
$link = pg_connect($conn_string);

if (!$link) {
    die('Error: ' . pg_last_error());
}

pg_set_client_encoding($link, "UTF8");
echo "Conexión exitosa";
