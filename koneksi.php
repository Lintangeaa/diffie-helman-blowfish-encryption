<?php

//Koneksi Database
$server = "103.175.221.170";
$user = "myuser";
$password = "myuser";
$database = "db_diffiehelman_blowfish";

//Buat Koneksi
$koneksi = mysqli_connect($server, $user, $password, $database) or die(mysqli_error($koneksi));