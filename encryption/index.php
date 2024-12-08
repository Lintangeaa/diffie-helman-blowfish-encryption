<?php

// ==== Fungsi Eksponensial Modulo (Diffie-Hellman) ==== //
function modExp($base, $exp, $mod)
{
  $result = 1;
  $base = $base % $mod;
  while ($exp > 0) {
    if ($exp % 2 == 1) {
      $result = ($result * $base) % $mod;
    }
    $exp = floor($exp / 2);
    $base = ($base * $base) % $mod;
  }
  return $result;
}

// ==== Blowfish Implementation ==== //

// Feistel Function (sederhana)
function feistelFunction($block, $key, $round)
{
  $sum = 0;
  for ($i = 0; $i < strlen($block); $i++) {
    $sum += ord($block[$i]) * ord($key[$round % strlen($key)]);
  }
  return $sum % 256; // Kembalikan nilai di bawah 256 (1 byte)
}

// Fungsi XOR string dengan integer
function stringXOR($string, $intValue)
{
  $result = '';
  for ($i = 0; $i < strlen($string); $i++) {
    $result .= chr(ord($string[$i]) ^ $intValue); // XOR per karakter
  }
  return $result;
}

// Fungsi untuk padding data ke panjang genap
function addPadding($data, $blockSize = 8)
{
  $padLength = $blockSize - (strlen($data) % $blockSize);
  return $data . str_repeat(chr($padLength), $padLength);
}

// Fungsi untuk menghapus padding
function removePadding($data)
{
  $padLength = ord(substr($data, -1));
  return substr($data, 0, -$padLength);
}

// Enkripsi menggunakan Blowfish Feistel Network
function blowfishEncrypt($key, $data)
{
  $blockSize = 8;
  $data = addPadding($data, $blockSize); // Tambahkan padding
  $n = strlen($data);
  $encrypted = '';

  for ($i = 0; $i < $n; $i += $blockSize) {
    $block = substr($data, $i, $blockSize);
    $left = substr($block, 0, $blockSize / 2);
    $right = substr($block, $blockSize / 2);

    // 16 Putaran Feistel
    for ($round = 0; $round < 16; $round++) {
      $temp = $right;
      $right = stringXOR($left, feistelFunction($right, $key, $round));
      $left = $temp;
    }

    $encrypted .= $right . $left;
  }

  return base64_encode($encrypted); // Encode dalam Base64
}

// Dekripsi menggunakan Blowfish Feistel Network
function blowfishDecrypt($key, $data)
{
  $blockSize = 8;
  $data = base64_decode($data); // Decode dari Base64
  $n = strlen($data);
  $decrypted = '';

  for ($i = 0; $i < $n; $i += $blockSize) {
    $block = substr($data, $i, $blockSize);
    $right = substr($block, 0, $blockSize / 2);
    $left = substr($block, $blockSize / 2);

    // 16 Putaran Feistel (dibalik)
    for ($round = 15; $round >= 0; $round--) {
      $temp = $left;
      $left = stringXOR($right, feistelFunction($left, $key, $round));
      $right = $temp;
    }

    $decrypted .= $left . $right;
  }

  return removePadding($decrypted); // Hapus padding
}

// ==== Diffie-Hellman Key Exchange ==== //
$p = 23; // Bilangan prima
$g = 5;  // Basis
$privateAlice = 6; // Kunci privat Alice
$privateBob = 15;  // Kunci privat Bob

// Kunci publik masing-masing pihak
$publicAlice = modExp($g, $privateAlice, $p);
$publicBob = modExp($g, $privateBob, $p);

// Kunci rahasia bersama
$sharedKeyAlice = modExp($publicBob, $privateAlice, $p);
$sharedKeyBob = modExp($publicAlice, $privateBob, $p);

if ($sharedKeyAlice !== $sharedKeyBob) {
  echo "Kunci rahasia tidak cocok.\n";
  exit;
}

$sharedKey = hash('sha256', $sharedKeyAlice, true); // Hash ke panjang tetap
?>