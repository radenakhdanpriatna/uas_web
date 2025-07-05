<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <title>Sistem Informasi Sekolah</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    #map { height: 500px; border-radius: 10px; }
    .jumbotron {
      background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://source.unsplash.com/1200x400/?school') center/cover no-repeat;
      color: white;
      padding: 100px 30px;
      text-align: center;
      border-radius: 0 0 20px 20px;
    }
    .navbar-brand { font-weight: bold; }
    .info-box {
      background: #f8f9fa;
      padding: 20px;
      border-left: 5px solid #0d6efd;
      border-radius: 8px;
    }
  </style>
</head>
<body>

<!-- ✅ NAVBAR LENGKAP -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="#">SIG Sekolah</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a href="#" class="nav-link active">Beranda</a>
        </li>
        <li class="nav-item">
          <a href="#map" class="nav-link">Peta</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="showTambahForm(); new bootstrap.Modal(document.getElementById('modalSekolah')).show();">Tambah Sekolah</a>
        </li>
        <li class="nav-item">
          <a href="#tentang" class="nav-link">Tentang</a>
        </li>
      </ul>
    </div>
  </div>
</nav>


<!-- ✅ JUMBOTRON -->
<div class="jumbotron">
  <h1 class="display-5">Sistem Informasi Geografis Sekolah</h1>
  <p class="lead">Lihat dan kelola data lokasi sekolah secara interaktif</p>
</div>

<!-- ✅ KONTEN UTAMA -->
<div class="container my-4">
  <div class="info-box mb-4">
    <h4>Informasi</h4>
    <p>Berikut ini adalah peta interaktif yang menampilkan lokasi sekolah. Anda dapat menambah, mengedit, atau menghapus data sekolah langsung melalui tampilan di bawah.</p>
  </div>

  <!-- ✅ SEKSYEN TENTANG -->
<div class="container my-5" id="tentang">
  <h4 class="mb-3">Tentang Aplikasi</h4>
  <p>Aplikasi <strong>SIG Sekolah</strong> adalah sistem informasi geografis berbasis web yang membantu pengguna melihat, menambah, mengedit, dan mengelola data sekolah di wilayah tertentu. Sistem ini dibangun menggunakan Leaflet, PHP, dan Bootstrap.</p>
</div>


  <h3 class="mb-3">Peta Lokasi Sekolah</h3>
  <div id="map"></div>

  <div class="d-flex justify-content-between align-items-center mt-4">
    <h4>Daftar Sekolah</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSekolah" onclick="showTambahForm()">+ Tambah Sekolah</button>
  </div>
  
  <ul class="list-group mt-2" id="list-sekolah"></ul>
</div>



<!-- ✅ MODAL -->
<div class="modal fade" id="modalSekolah" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formSekolah" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Tambah Sekolah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="sekolahId">
        <div class="mb-2">
          <label>Nama Sekolah</label>
          <input type="text" class="form-control" name="nama" id="nama" required>
        </div>
        <div class="mb-2">
          <label>Alamat</label>
          <textarea class="form-control" name="alamat" id="alamat" required></textarea>
        </div>
        <div class="mb-2">
          <label>Latitude</label>
          <input type="text" class="form-control" name="latitude" id="latitude" required>
        </div>
        <div class="mb-2">
          <label>Longitude</label>
          <input type="text" class="form-control" name="longitude" id="longitude" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Simpan</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      </div>
    </form>
  </div>
</div>

<!-- ✅ SCRIPT -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
let map = L.map('map').setView([-6.595, 106.816], 12);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: 'Peta © OpenStreetMap'
}).addTo(map);

let markers = [];

function tampilkanSekolah() {
  fetch('get_sekolah.php')
    .then(res => res.json())
    .then(data => {
      document.getElementById('list-sekolah').innerHTML = "";
      markers.forEach(m => map.removeLayer(m));
      markers = [];

      data.forEach(s => {
        const marker = L.marker([s.latitude, s.longitude])
          .addTo(map)
          .bindPopup(`<b>${s.nama}</b><br>${s.alamat}`);
        markers.push(marker);

        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-start';
        li.innerHTML = `
          <div>
            <strong>${s.nama}</strong><br>${s.alamat}
          </div>
          <div>
            <button class="btn btn-sm btn-warning me-1" onclick='editSekolah(${JSON.stringify(s)})'>Edit</button>
            <button class="btn btn-sm btn-danger" onclick='hapusSekolah(${s.id})'>Hapus</button>
          </div>
        `;
        document.getElementById('list-sekolah').appendChild(li);
      });
    });
}

tampilkanSekolah();

function showTambahForm() {
  document.getElementById('modalTitle').innerText = 'Tambah Sekolah';
  document.getElementById('formSekolah').reset();
  document.getElementById('sekolahId').value = '';
}

function editSekolah(data) {
  document.getElementById('modalTitle').innerText = 'Edit Sekolah';
  document.getElementById('sekolahId').value = data.id;
  document.getElementById('nama').value = data.nama;
  document.getElementById('alamat').value = data.alamat;
  document.getElementById('latitude').value = data.latitude;
  document.getElementById('longitude').value = data.longitude;
  new bootstrap.Modal(document.getElementById('modalSekolah')).show();
}

document.getElementById('formSekolah').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  fetch('simpan_sekolah.php', {
    method: 'POST',
    body: formData
  }).then(res => res.text()).then(res => {
    if (res === 'OK') {
      tampilkanSekolah();
      bootstrap.Modal.getInstance(document.getElementById('modalSekolah')).hide();
    } else {
      alert('Gagal menyimpan data.');
    }
  });
});

function hapusSekolah(id) {
  if (confirm('Yakin ingin menghapus sekolah ini?')) {
    fetch('hapus_sekolah.php?id=' + id)
      .then(res => res.text())
      .then(res => {
        if (res === 'OK') {
          tampilkanSekolah();
        } else {
          alert('Gagal menghapus data.');
        }
      });
  }
}
</script>
</body>
</html>
