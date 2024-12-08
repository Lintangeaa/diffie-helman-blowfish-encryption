<?php
include "koneksi.php";
include 'aksi_crud.php';
$sharedKey = hash('sha256', $sharedKeyAlice, true);

// Proses Hapus Data
if (isset($_GET['hapus_id'])) {
    $id = $_GET['hapus_id'];
    if (hapusData($koneksi, $id)) {
        echo "<script>alert('Data berhasil dihapus');</script>";
        echo "<script>window.location='index.php';</script>";
    } else {
        echo "<script>alert('Data gagal dihapus');</script>";
    }
}

if (isset($_POST['tambah'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $ttl = $_POST['ttl'];
    $nim = $_POST['nim'];
    $ipk = $_POST['ipk'];
    $jurusan = $_POST['jurusan'];
    $univ = $_POST['univ'];
    $tahun_masuk = $_POST['tahun_masuk'];
    $nomor_rekening = $_POST['nomor_rekening'];
    $nomor_hp = $_POST['nomor_hp'];
    $ket = $_POST['ket'];

    if (tambahData($koneksi, $sharedKey, $nama, $jenis_kelamin, $ttl, $nim, $ipk, $jurusan, $univ, $tahun_masuk, $nomor_rekening, $nomor_hp, $ket)) {
        echo "<script>alert('Data berhasil Ditambahkan');</script>";
        echo "<script>window.location='index.php';</script>";
    } else {
        echo "<script>alert('Data gagal ditambahkan');</script>";
    }
}

// Proses Edit Data
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $ttl = $_POST['ttl'];
    $nim = $_POST['nim'];
    $ipk = $_POST['ipk'];
    $jurusan = $_POST['jurusan'];
    $univ = $_POST['univ'];
    $tahun_masuk = $_POST['tahun_masuk'];
    $nomor_rekening = $_POST['nomor_rekening'];
    $nomor_hp = $_POST['nomor_hp'];
    $ket = $_POST['ket'];

    if (editData($koneksi, $sharedKey, $id, $nama, $jenis_kelamin, $ttl, $nim, $ipk, $jurusan, $univ, $tahun_masuk, $nomor_rekening, $nomor_hp, $ket)) {
        echo "<script>alert('Data berhasil diperbarui');</script>";
        echo "<script>window.location='index.php';</script>";
    } else {
        echo "<script>alert('Data gagal diperbarui');</script>";
    }
}

// Mendapatkan data user
$dataUser = getDataUser($koneksi, $sharedKey);
?>

<!doctype html>
<html l ang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beasiswa Pemda Gunung Mas 2023</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .table-container {
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table th,
        .table td {
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="mt-3">
            <h3 class="text-center">DATA PENERIMA BEASISWA BAGI MAHASISWA BERPRESTASI</h3>
            <h3 class="text-center">KABUPATEN GUNUNG MAS TAHUN ANGGARAN 2023</h3>
        </div>
        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                Data User
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modaltambah">
                    Tambah Data Penerima
                </button>

                <!-- Table Container with overflow-x -->
                <div class="table-container">
                    <table class="table table-bordered table-striped table-hover">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Tempat, Tanggal Lahir</th>
                            <th>Nomor Induk Mahasiswa</th>
                            <th>IPK</th>
                            <th>Jurusan/Prodi</th>
                            <th>Nama Universitas</th>
                            <th>Tahun Masuk</th>
                            <th>Nomor Rekening</th>
                            <th>Nomor HP</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>

                        <?php
                        $no = 1;
                        foreach ($dataUser as $data) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . $data['nama'] . "</td>";
                            echo "<td>" . $data['jenis_kelamin'] . "</td>";
                            echo "<td>" . $data['ttl'] . "</td>";
                            echo "<td>" . $data['nim'] . "</td>";
                            echo "<td>" . $data['ipk'] . "</td>";
                            echo "<td>" . $data['jurusan'] . "</td>";
                            echo "<td>" . $data['univ'] . "</td>";
                            echo "<td>" . $data['tahun_masuk'] . "</td>";
                            echo "<td>" . $data['nomor_rekening'] . "</td>";
                            echo "<td>" . $data['nomor_hp'] . "</td>";
                            echo "<td>" . $data['ket'] . "</td>";
                            echo "<td style='display:flex; gap:5px;'> 
                                    <a href='?hapus_id=" . $data['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus data?\")'>Hapus</a>
                                    <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#modalEdit' 
                                        data-id='" . $data['id'] . "' data-nama='" . $data['nama'] . "' 
                                        data-jenis_kelamin='" . $data['jenis_kelamin'] . "' data-ttl='" . $data['ttl'] . "' 
                                        data-nim='" . $data['nim'] . "' data-ipk='" . $data['ipk'] . "' 
                                        data-jurusan='" . $data['jurusan'] . "' data-univ='" . $data['univ'] . "' 
                                        data-tahun_masuk='" . $data['tahun_masuk'] . "' data-nomor_rekening='" . $data['nomor_rekening'] . "' 
                                        data-nomor_hp='" . $data['nomor_hp'] . "' data-ket='" . $data['ket'] . "'>Edit</button>
                                </td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modaltambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="staticBackdropLabel">Data Penerima Beasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="index.php">
                    <div class="modal-body">
                        <div class="mb-1">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama Lengkap">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-select" name="jenis_kelamin">
                                <option></option>
                                <option value="Laki-Laki">Laki-Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tempat, Tanggal Lahir</label>
                            <input type="text" class="form-control" name="ttl"
                                placeholder="cth: Jakarta, 17 Agustus 1945">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Induk Mahasiswa</label>
                            <input type="text" class="form-control" name="nim">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Indeks Prestasi Kumulatif</label>
                            <input type="text" class="form-control" name="ipk" placeholder="cth: 4,0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jurusan/Program Studi</label>
                            <input type="text" class="form-control" name="jurusan">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Universitas/Instansi</label>
                            <input type="text" class="form-control" name="univ">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tahun Masuk</label>
                            <input type="text" class="form-control" name="tahun_masuk">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Rekening Aktif</label>
                            <input type="text" class="form-control" name="nomor_rekening">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Handphone Aktif</label>
                            <input type="text" class="form-control" name="nomor_hp" placeholder="Cth : 081234xxxxxx">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <select class="form-select" name="ket">
                                <option></option>
                                <option value="Lanjutan">Lanjutan</option>
                                <option value="Baru">Baru</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success" name="tambah">Tambah</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Modal for Editing -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditLabel">Edit Data Penerima Beasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">

                        <!-- Nama -->
                        <div class="mb-3">
                            <label for="edit-nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="edit-nama" name="nama" required>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="mb-3">
                            <label for="edit-jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <input type="text" class="form-control" id="edit-jenis_kelamin" name="jenis_kelamin"
                                required>
                        </div>

                        <!-- Tempat, Tanggal Lahir -->
                        <div class="mb-3">
                            <label for="edit-ttl" class="form-label">Tempat, Tanggal Lahir</label>
                            <input type="text" class="form-control" id="edit-ttl" name="ttl" required>
                        </div>

                        <!-- Nomor Induk Mahasiswa -->
                        <div class="mb-3">
                            <label for="edit-nim" class="form-label">Nomor Induk Mahasiswa</label>
                            <input type="text" class="form-control" id="edit-nim" name="nim" required>
                        </div>

                        <!-- IPK -->
                        <div class="mb-3">
                            <label for="edit-ipk" class="form-label">IPK</label>
                            <input type="text" class="form-control" id="edit-ipk" name="ipk" required>
                        </div>

                        <!-- Jurusan/Prodi -->
                        <div class="mb-3">
                            <label for="edit-jurusan" class="form-label">Jurusan/Prodi</label>
                            <input type="text" class="form-control" id="edit-jurusan" name="jurusan" required>
                        </div>

                        <!-- Nama Universitas -->
                        <div class="mb-3">
                            <label for="edit-univ" class="form-label">Nama Universitas</label>
                            <input type="text" class="form-control" id="edit-univ" name="univ" required>
                        </div>

                        <!-- Tahun Masuk -->
                        <div class="mb-3">
                            <label for="edit-tahun_masuk" class="form-label">Tahun Masuk</label>
                            <input type="text" class="form-control" id="edit-tahun_masuk" name="tahun_masuk" required>
                        </div>

                        <!-- Nomor Rekening -->
                        <div class="mb-3">
                            <label for="edit-nomor_rekening" class="form-label">Nomor Rekening</label>
                            <input type="text" class="form-control" id="edit-nomor_rekening" name="nomor_rekening"
                                required>
                        </div>

                        <!-- Nomor HP -->
                        <div class="mb-3">
                            <label for="edit-nomor_hp" class="form-label">Nomor HP</label>
                            <input type="text" class="form-control" id="edit-nomor_hp" name="nomor_hp" required>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-3">
                            <label for="edit-ket" class="form-label">Keterangan</label>
                            <select class="form-select" id="edit-ket" name="ket" required>
                                <option value="Lanjutan" <?php echo ($data['ket'] == 'Lanjutan') ? 'selected' : ''; ?>>
                                    Lanjutan</option>
                                <option value="Baru" <?php echo ($data['ket'] == 'Baru') ? 'selected' : ''; ?>>Baru
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const editButtons = document.querySelectorAll('[data-bs-target="#modalEdit"]');
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const jenisKelamin = this.getAttribute('data-jenis_kelamin');
                const ttl = this.getAttribute('data-ttl');
                const nim = this.getAttribute('data-nim');
                const ipk = this.getAttribute('data-ipk');
                const jurusan = this.getAttribute('data-jurusan');
                const univ = this.getAttribute('data-univ');
                const tahunMasuk = this.getAttribute('data-tahun_masuk');
                const nomorRekening = this.getAttribute('data-nomor_rekening');
                const nomorHp = this.getAttribute('data-nomor_hp');
                const ket = this.getAttribute('data-ket');

                document.getElementById('edit-id').value = id;
                document.getElementById('edit-nama').value = nama;
                document.getElementById('edit-jenis_kelamin').value = jenisKelamin;
                document.getElementById('edit-ttl').value = ttl;
                document.getElementById('edit-nim').value = nim;
                document.getElementById('edit-ipk').value = ipk;
                document.getElementById('edit-jurusan').value = jurusan;
                document.getElementById('edit-univ').value = univ;
                document.getElementById('edit-tahun_masuk').value = tahunMasuk;
                document.getElementById('edit-nomor_rekening').value = nomorRekening;
                document.getElementById('edit-nomor_hp').value = nomorHp;
                document.getElementById('edit-ket').value = ket;
            });
        });
    </script>
</body>

</html>