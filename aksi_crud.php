<?php
include "koneksi.php"; // Koneksi database
include 'encryption/index.php'; // Enkripsi Blowfish dan AES
$sharedKey = hash('sha256', $sharedKeyAlice, true); // Kunci untuk enkripsi

// Fungsi untuk menambahkan data
function tambahData($koneksi, $sharedKey, $nama, $jenis_kelamin, $ttl, $nim, $ipk, $jurusan, $univ, $tahun_masuk, $nomor_rekening, $nomor_hp, $ket)
{
    // Enkripsi data menggunakan fungsi enkripsi gabungan
    $encryptedTtl = encryptData($ttl, $sharedKey);
    $encryptedNim = encryptData($nim, $sharedKey);
    $encryptedNomorRekening = encryptData($nomor_rekening, $sharedKey);
    $encryptedNomorHp = encryptData($nomor_hp, $sharedKey);

    // Query untuk menyimpan data terenkripsi ke dalam database
    $query = "INSERT INTO tuser (nama, jenis_kelamin, ttl, nim, ipk, jurusan, univ, tahun_masuk, nomor_rekening, nomor_hp, ket) 
              VALUES ('$nama', '$jenis_kelamin', '$encryptedTtl', '$encryptedNim', '$ipk', '$jurusan', '$univ', '$tahun_masuk', '$encryptedNomorRekening', '$encryptedNomorHp', '$ket')";

    return mysqli_query($koneksi, $query);
}

// Fungsi untuk mengedit data
function editData($koneksi, $sharedKey, $id, $nama, $jenis_kelamin, $ttl, $nim, $ipk, $jurusan, $univ, $tahun_masuk, $nomor_rekening, $nomor_hp, $ket)
{
    // Enkripsi data menggunakan fungsi enkripsi gabungan
    $encryptedTtl = encryptData($ttl, $sharedKey);
    $encryptedNim = encryptData($nim, $sharedKey);
    $encryptedNomorRekening = encryptData($nomor_rekening, $sharedKey);
    $encryptedNomorHp = encryptData($nomor_hp, $sharedKey);

    // Query untuk mengupdate data terenkripsi ke dalam database
    $query = "UPDATE tuser SET 
              nama='$nama', 
              jenis_kelamin='$jenis_kelamin', 
              ttl='$encryptedTtl', 
              nim='$encryptedNim', 
              ipk='$ipk', 
              jurusan='$jurusan', 
              univ='$univ', 
              tahun_masuk='$tahun_masuk', 
              nomor_rekening='$encryptedNomorRekening', 
              nomor_hp='$encryptedNomorHp', 
              ket='$ket' 
              WHERE id=$id";

    // Eksekusi query
    return mysqli_query($koneksi, $query);
}

// Fungsi untuk menghapus data
function hapusData($koneksi, $id)
{
    $query = "DELETE FROM tuser WHERE id = $id";
    return mysqli_query($koneksi, $query);
}

// Fungsi untuk mendapatkan data user
function getDataUser($koneksi, $sharedKey)
{
    $query = mysqli_query($koneksi, "SELECT * FROM tuser");
    $dataUser = [];
    while ($data = mysqli_fetch_array($query)) {
        // Dekripsi data menggunakan fungsi dekripsi gabungan
        $decryptedTtl = decryptData($data['ttl'], $sharedKey);
        $decryptedNim = decryptData($data['nim'], $sharedKey);
        $decryptedNomorRekening = decryptData($data['nomor_rekening'], $sharedKey);
        $decryptedNomorHp = decryptData($data['nomor_hp'], $sharedKey);

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
