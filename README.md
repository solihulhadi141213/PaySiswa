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


## Teknologi yang Digunakan
- **Bahasa Pemrograman** :  
  - PHP 8.0.30  
  - JavaScript
  - HTML5
  - CSS3  
- **Database**: MySQL 9.1.0  
- **Framework & Library** :  
  - Bootstrap 5 (UI/UX)
  - mdb-ui-kit 8.2.0  
  - JQuery  3.7.1
  - sweetalert2
  - jspdf 3.0.0
  - phpqrcode 1.1.4
  - phpoffice/phpspreadsheet 
  - mpdf/mpdf
- **Arsitektur**: Web-based application (Client–Server)  

## Integrasi
1. **Email Gateway**
 Memungkinkan aplikasi mengirimkan pesan melalui email (SMTP) untuk berbagai kepentingan seperti mengirikan kode Autentifikasi, mengirim tautan tagihan dan bukti pembayaran.

 2. **Payment Gateway**
 Memungkinkan pengguna menerima pembayaran melalui berbagai metode yang populer digunakan. Dengan integrasi dengan payment gateway, memungkikan sistem memperoleh informasi status pembayaran secara real time.

## Instalasi
1. Clone repository ini:
   ```bash
   git clone https://github.com/solihulhadi141213/PaySiswa.git
2. Import database dari directory <code>db/pay_siswa.sql</code>
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
## Referensi
- Web Admin Template : https://bootstrapmade.com/bootstrap-admin-templates/
- Relation Database Design : https://drawsql.app/teams/rsu-el-syifa/diagrams/school-system
