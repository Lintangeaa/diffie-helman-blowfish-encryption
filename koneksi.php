<?php

//Koneksi Database
$server = "localhost";
$user = "root";
$password = "";
$database = "db_diffiehelman_blowfish";

//Buat Koneksi
$koneksi = mysqli_connect($server, $user, $password, $database) or die(mysqli_error($koneksi));