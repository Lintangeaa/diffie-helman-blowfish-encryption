<?php
include "koneksi.php"; // Koneksi database
include 'encryption/index.php'; // Enkripsi Blowfish
$sharedKey = hash('sha256', $sharedKeyAlice, true); // Kunci untuk enkripsi

// Fungsi untuk menambahkan data
function tambahData($koneksi, $sharedKey, $nama, $jenis_kelamin, $ttl, $nim, $ipk, $jurusan, $univ, $tahun_masuk, $nomor_rekening, $nomor_hp, $ket)
{
    $encryptedTtl = blowfishEncrypt($sharedKey, $ttl);
    $encryptedNim = blowfishEncrypt($sharedKey, $nim);
    $encryptedNomorRekening = blowfishEncrypt($sharedKey, $nomor_rekening);
    $encryptedNomorHp = blowfishEncrypt($sharedKey, $nomor_hp);

    $query = "INSERT INTO tuser (nama, jenis_kelamin, ttl, nim, ipk, jurusan, univ, tahun_masuk, nomor_rekening, nomor_hp, ket) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($koneksi, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssssssss", $nama, $jenis_kelamin, $encryptedTtl, $encryptedNim, $ipk, $jurusan, $univ, $tahun_masuk, $encryptedNomorRekening, $encryptedNomorHp, $ket);
        return mysqli_stmt_execute($stmt);
    }
    return false;
}

// Fungsi untuk mengedit data
function editData($koneksi, $sharedKey, $id, $nama, $jenis_kelamin, $ttl, $nim, $ipk, $jurusan, $univ, $tahun_masuk, $nomor_rekening, $nomor_hp, $ket)
{
    $encryptedTtl = blowfishEncrypt($sharedKey, $ttl);
    $encryptedNim = blowfishEncrypt($sharedKey, $nim);
    $encryptedNomorRekening = blowfishEncrypt($sharedKey, $nomor_rekening);
    $encryptedNomorHp = blowfishEncrypt($sharedKey, $nomor_hp);

    $query = "UPDATE tuser SET 
              nama=?, 
              jenis_kelamin=?, 
              ttl=?, 
              nim=?, 
              ipk=?, 
              jurusan=?, 
              univ=?, 
              tahun_masuk=?, 
              nomor_rekening=?, 
              nomor_hp=?, 
              ket=? 
              WHERE id=?";

    $stmt = mysqli_prepare($koneksi, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssssssssi", $nama, $jenis_kelamin, $encryptedTtl, $encryptedNim, $ipk, $jurusan, $univ, $tahun_masuk, $encryptedNomorRekening, $encryptedNomorHp, $ket, $id);
        return mysqli_stmt_execute($stmt);
    }
    return false;
}

// Fungsi untuk menghapus data
function hapusData($koneksi, $id)
{
    $query = "DELETE FROM tuser WHERE id=?";
    $stmt = mysqli_prepare($koneksi, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt);
    }
    return false;
}

// Fungsi untuk mendapatkan data user
function getDataUser($koneksi, $sharedKey)
{
    $query = mysqli_query($koneksi, "SELECT * FROM tuser");
    $dataUser = [];
    while ($data = mysqli_fetch_array($query)) {
        $decryptedTtl = blowfishDecrypt($sharedKey, $data['ttl']);
        $decryptedNim = blowfishDecrypt($sharedKey, $data['nim']);
        $decryptedNomorRekening = blowfishDecrypt($sharedKey, $data['nomor_rekening']);
        $decryptedNomorHp = blowfishDecrypt($sharedKey, $data['nomor_hp']);

        $dataUser[] = [
            'id' => $data['id'],
            'nama' => $data['nama'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'ttl' => $decryptedTtl,
            'nim' => $decryptedNim,
            'ipk' => $data['ipk'],
            'jurusan' => $data['jurusan'],
            'univ' => $data['univ'],
            'tahun_masuk' => $data['tahun_masuk'],
            'nomor_rekening' => $decryptedNomorRekening,
            'nomor_hp' => $decryptedNomorHp,
            'ket' => $data['ket']
        ];
    }
    return $dataUser;
}
?>