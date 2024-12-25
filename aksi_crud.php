<?php
include "koneksi.php"; // Koneksi database
include 'encryption/index.php'; // Enkripsi Blowfish dan modExp()

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
        // Dekripsi data dengan Blowfish
        $decryptedTtl = blowfishDecrypt($sharedKey, $data['ttl']);
        $decryptedNim = blowfishDecrypt($sharedKey, $data['nim']);
        $decryptedNomorRekening = blowfishDecrypt($sharedKey, $data['nomor_rekening']);
        $decryptedNomorHp = blowfishDecrypt($sharedKey, $data['nomor_hp']);

        // Menambahkan data yang telah didekripsi ke dalam array
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

// === Proses Diffie-Hellman untuk mendapatkan shared key === //
$p = 23; // Bilangan prima
$g = 5;  // Basis
$privateAlice = 6; // Kunci privat Alice
$privateBob = 15;  // Kunci privat Bob

$publicAlice = modExp($g, $privateAlice, $p);
$publicBob = modExp($g, $privateBob, $p);

// Kunci rahasia bersama
$sharedKeyAlice = modExp($publicBob, $privateAlice, $p);
$sharedKeyBob = modExp($publicAlice, $privateBob, $p);

if ($sharedKeyAlice !== $sharedKeyBob) {
  echo "Kunci rahasia tidak cocok.\n";
  exit;
}

// Perkuat kunci menggunakan OpenSSL HMAC (SHA-256)
$sharedKeyStrong = hash_hmac('sha256', $sharedKeyAlice, 'secret_salt', true);

// Ambil 16 byte pertama untuk digunakan dengan Blowfish
$sharedKey = substr($sharedKeyStrong, 0, 16);

?>
