<?php 

include 'config/koneksi.php';


// INSERT/TAMBAH DATA KE DATABASE
if ( isset($_POST['simpan']) ) {
  $total = (40/100 * $_POST['tugas']) + (60/100 * $_POST['uts']);
  if ( $total >= 90 ) {
    $mutu = "A";
  }else if ( $total >= 80 ) {
    $mutu = "B";
  }else if ( $total >= 70 ) {
    $mutu = "C";
  }else if ( $total >= 60 ) {
    $mutu = "D";
  }else{
    $mutu = "E";
  }

  $sql = mysqli_query($con, "INSERT INTO mahasiswa VALUES($_POST[nim], '$_POST[nama]', '$_POST[jurusan]', '$_POST[matkul]', $_POST[tugas], $_POST[uts], $total, '$mutu')");

  echo "<script>alert('Berhasil Tersimpan');document.location.href='?menu=nilai';</script>";
}


if ( isset($_GET['edit']) ) {
  $sql = mysqli_query($con, "SELECT * FROM mahasiswa WHERE nim = '$_GET[nim]'");
  $row_edit = mysqli_fetch_array($sql);
}else{
  $sql = null;
}


// UPDATE/UBAH DATA DARI DATABASE
if ( isset($_POST['update']) ) {
  $total = (40/100 * $_POST['tugas']) + (60/100 * $_POST['uts']);
  if ( $total >= 90 ) {
    $mutu = "A";
  }else if ( $total >= 80 ) {
    $mutu = "B";
  }else if ( $total >= 70 ) {
    $mutu = "C";
  }else if ( $total >= 60 ) {
    $mutu = "D";
  }else{
    $mutu = "E";
  }

  $sql = mysqli_query($con, "UPDATE mahasiswa SET nim = $_POST[nim], nama = '$_POST[nama]', jurusan = '$_POST[jurusan]', mata_kuliah = '$_POST[matkul]', nilai_tugas = $_POST[tugas], nilai_uts = $_POST[uts], total = $total, huruf_mutu = '$mutu' WHERE nim = $_GET[nim]");
  echo "<script>
  alert('Berhasil diubah');
  document.location.href='?menu=nilai';
  </script>";
}


// DELETE/HAPUS DATA DARI DATABASE
if ( isset($_GET['delete']) ) {
  $sql = mysqli_query($con, "DELETE FROM mahasiswa WHERE nim = $_GET[nim]");
  echo "<script>alert('Berhasil Dihapus');document.location.href='?menu=nilai';</script>";
}

?>

<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Form Nilai</h6>
  </div>
  <div class="card-body">
    <form method="post">
      <div class="form-group">
        <div class="row">
          <div class="col">
            <input type="number" name="nim" class="form-control" placeholder="NIM" value="<?= $row_edit['nim']; ?>" autocomplete="off" required>
          </div>
          <div class="col">
            <input type="text" name="nama" class="form-control" placeholder="Nama" value="<?= isset($_GET['edit'])? $row_edit['nama'] :''; ?>" autocomplete="off" required>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col">
            <select name="jurusan" class="form-control" required>
              <?php if ( isset($_GET['edit']) ): ?>
                <option disabled>--Jurusan--</option>
                <?php if( $row_edit['jurusan'] == "Teknik Informatika") { ?>
                  <option value="Sistem Informatika">Sistem Informatika</option>
                <?php } else { ?>
                  <option value="Teknik Informatika">Teknik Informatika</option>
                <?php } ?>
                <option value="<?= $row_edit['jurusan']; ?>" selected><?= $row_edit['jurusan']; ?></option>
                <?php else : ?>
                  <option disabled selected>--Jurusan--</option>
                  <option value="Teknik Informatika">Teknik Informatika</option>
                  <option value="Sistem Informatika">Sistem Informatika</option>
                <?php endif ?>
              </select>
            </div>
            <div class="col">
              <input type="text" name="matkul" class="form-control" placeholder="Mata Kuliah" value="<?= isset($_GET['edit'])? $row_edit['matkul']: ''; ?>" autocomplete="off" required>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col">
              <input type="number" name="tugas" class="form-control" placeholder="Nilai Tugas" value="<?= $row_edit['tugas']; ?>" autocomplete="off" required>
            </div>
            <div class="col">
              <input type="number" name="uts" class="form-control" placeholder="Nlai UTS" value="<?= $row_edit['uts']; ?>" autocomplete="off" required>
            </div>
          </div>
        </div>
        <?php if( !isset($_GET['edit']) ) : ?>
          <button type="submit" name="simpan" class="btn btn-primary">SIMPAN</button>
          <?php else : ?>
            <button type="submit" name="update" class="btn btn-primary">UPDATE</button>
            <a href="?menu=nilai" class="btn btn-danger">BATAL</a>
          <?php endif; ?>
        </form>


        <br><br>


        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead align="center">
              <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Mata Kuliah</th>
                <th>Nilai Tugas</th>
                <th>Nilai UTS</th>
                <th>Total</th>
                <th>Huruf Mutu</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody align ="center">
              <?php 
              $sql = mysqli_query($con, "SELECT * FROM mahasiswa");
              while ( $r = mysqli_fetch_array($sql) ) : ?>
                <tr>
                  <td><?= $r['nim']; ?></td>
                  <td><?= $r['nama']; ?></td>
                  <td><?= $r['jurusan']; ?></td>
                  <td><?= $r['matkul']; ?></td>
                  <td><?= $r['tugas']; ?></td>
                  <td><?= $r['uts']; ?></td>
                  <td><?= $r['total']; ?></td>
                  <td><?= $r['huruf_mutu']; ?></td>
                  <td>

                    <a href="?menu=nilai&delete&nim=<?= $r['nim']; ?>" 
                    onclick="return confirm('Are you sure, you want to delete?')"><i  class="btn btn-danger">Hapus</i></a>

                    <a href="?menu=nilai&edit&nim=<?= $r['nim']; ?>"><i class="btn btn-warning">Edit</i></a>
                    
                    
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

