# PaySiswa

**PaySiswa** adalah aplikasi berbasis web untuk mempermudah tata usaha sekolah dalam mengelola pembayaran SPP siswa.  Aplikasi ini dikembangkan agar administrasi keuangan lebih transparan, efisien, dan mudah diakses. Secara umum, aplikasi ini berguna untuk menyiimpan (mencatat) transaksi pembayaran siswa dan menyajikan laporan secara dinamis sehingga lebih mudah untuk diinterpertasikan.

## Fitur Utama
- **Aksesibilitas**  
  Pengguna dapat mengatur siapa saja yang bisa mengakses aplikasi secara dinamis. Fitur ini berfungsi untuk mengelola data pengguna, menetapkan fitur yang bisa diakses dan mengelola kode akses pada setiap halaman.  

- **Pengaturan**  
  Ftur ini berfungsi untuk mempermudah pengguna melakukan perubahan komponen halaman dan sistem. Dengan fitur ini, pengguna bisa mengatur nama, logo, kontak dan alamat sekolah. Selain itu, pengguna bisa mengatur parameter koneksi dengan server email gateway (SMTP) dan juga integrasi dengan payment gateway.

- **Tahun Akademik**  
  Data transaksi dikelompokan berdasdarkan periode akademik. Setiap tahun ajaran baru, pengguna bisa dengan mudah mencatat pos kelas masing-masing siswa dengan nomiinal tagihan biaya pendidikan yang juga berbeda.

- **Biaya Pendidikan**  
  Fitur ini berfungsi untuk mengelola komponen biaya pendidikan yang berlaku pada setiap periode akademik. Informasi biaya pendidikan tersebut terdiri dari nama komponen biaya pendidikan, kategori dan nominal biaya.

- **Manajemen Kelas**
  Rombongan belajar (Rombel) dikelola berdasarkan level atau jenjang tingkatan secara vertikal dan berdasarkan kelompok belajar secara horisontal. Fitur manajemen kelas berfungsi untuk mengelola profil masing-masing Rombel tersebut pada setiap periode akademik.

- **Daftar Siswa**
  Halaman daftar siswa menampilkan data siswa secara keseluruhan, baik yang masih aktif, lulus, maupun siswa yang sudah keluar. Pada halaman ini user dapat mengelola (menambah, mengubah dan menghapus) data siswa yang ada serta menetapkan status masing-masing siswa.


## Teknologi yang Digunakan
- **Bahasa Pemrograman Utama** :  
  - PHP 8.0.30  
  - JavaScript
  - HTML5
  - CSS3  

- **Database Managment System**: 
  - MySQL 9.1.0  
  - MariaDB 11.5.2  

- **Library (Pustaka)** :  
  - Bootstrap 5 (UI/UX)
  - mdb-ui-kit 8.2.0  
  - JQuery  3.7.1
  - sweetalert2
  - jspdf 3.0.0
  - phpqrcode 1.1.4
  - phpoffice/phpspreadsheet 
  - mpdf/mpdf

- **Arsitektur**: 
  - Web-based application
  - Client–Server 

## Integrasi
1. **Email Gateway**
 Memungkinkan aplikasi mengirimkan pesan melalui email (SMTP) untuk berbagai kepentingan seperti mengirikan kode Autentifikasi, mengirim tautan tagihan dan bukti pembayaran.

 2. **Payment Gateway**
 Memungkinkan pengguna menerima pembayaran melalui berbagai metode yang populer digunakan. Dengan integrasi dengan payment gateway, memungkikan sistem memperoleh informasi status pembayaran secara real time.

## Instalasi
1. Clone repository ini:
   ```bash
   git clone https://github.com/solihulhadi141213/PaySiswa.git
2. Import database dari directory <code>db/pay_siswa.sql</code> yang ada pada project ini.
3. Atur konfigurasii koneksi database pada file <code>_Config/Connection.php</code>
   ```bash
    //Ini adalah halaman untuk melakukan konfigurasi database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "pay_siswa";
    
    // Buat Koneksi
    $Conn = new mysqli($servername, $username, $password, $db);
    
    // Check connection
    if ($Conn->connect_error) {
        die("Connection failed: " . $Conn->connect_error);
    }
  
4. Atur parameter koneksi database tersebut agar sesuai dengan konfigurasi database anda.
5. Akses pada web browser anda <code>http://localhost/PaySiswa</code> kemudian login menggunakan akun standar sebagai berikut :
   - Email : <code>dhiforester@gmail.com</code>
   - Password : <code>dhiforester</code>
## Definisi Operasional
1. **Periode Akademik :**
 Rentang waktu formal yang ditetapkan oleh institusi pendidikan sebagai siklus penuh kegiatan belajar-mengajar dan administrasi. Rentang ini umumnya dibagi menjadi Tahun Ajaran dan sub-bagian Semester atau Bulan.
 
2. **Siswa :**
 Individu yang secara resmi tercatat pada lembaga pendidikan dan pada konteks aplikasi ini merupakan individu yang tercatat pada tabel 'student'.

3. **NIS :**
 Singkatan dari "Nomor Induk Siswa" yang merupakan informasi utama sebagai kode yang mewakili seluruh informasi siswa tersebut.

4. **Komponen Biaya Pendidikan :**
 Komponen dasar yang menyatakan informasi nama biaya pendidikan yang harus dibayar oleh siswa.

5. **Jenjang :**
 Jenjang adalah satuan dari tingkatan pada lembaga pendidikan tertentu. Misalnya : Kelas 1, Kelas 2, Kelas 3 Dst.


## Referensi
- Web Admin Template : https://bootstrapmade.com/bootstrap-admin-templates/
- Relation Database Design : https://drawsql.app/teams/rsu-el-syifa/diagrams/school-system
- Sequance Diagram : https://drive.google.com/file/d/1fX6SMki9oydaelz97KHcJVUzifKv_pj5/view?usp=sharing

> **Tutorial dan Dokumentasi Lengkap**<br>
> Untuk mendapatkan informasi lengkap mengenai aplikasi ini, bisa juga mengakses halaman github book berikut ini:
> https://parasilva-technology.gitbook.io/pay-siswa/referensi

