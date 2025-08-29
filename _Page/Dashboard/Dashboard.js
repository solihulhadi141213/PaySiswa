// Fungsi Untuk Menampilkan Data Barang
function CountOfBarang() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/CountOfBarang.php',
        dataType: "json",
        success: function(response) {
            if (response.status == "Success") {
                $('#put_count_rp_barang').hide().html(response.rp_barang).fadeIn(500);
                $('#put_count_item_barang').hide().html(response.item_barang).fadeIn(500);
                CountOfAnggota();
            } else {
                $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>' + response.message + '</small></div>').fadeIn(500);
            }
        },
        error: function() {
            $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>Terjadi Kesalahan Pada Sistem Saat Menghitung Barang!</small></div>').fadeIn(500);
        },
    });
}

// Fungsi Untuk Menampilkan Data Anggota
function CountOfAnggota() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/CountOfAnggota.php',
        dataType: "json",
        success: function(response) {
            if (response.status == "Success") {
                $('#put_anggota_aktif').hide().html(response.anggota_aktif).fadeIn(500);
                $('#put_anggota_keluar').hide().html(response.anggota_keluar).fadeIn(500);
                CountOfSimpanan();
            } else {
                $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>' + response.message + '</small></div>').fadeIn(500);
            }
        },
        error: function() {
            $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>Terjadi Kesalahan Pada Sistem Saat Menghitung Anggota!</small></div>').fadeIn(500);
        },
    });
}

// Fungsi Untuk Menampilkan Data Simpanan Anggota
function CountOfSimpanan() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/CountOfSimpanan.php',
        dataType: "json",
        success: function(response) {
            if (response.status == "Success") {
                $('#put_simpanan_anggota').hide().html('<i class="bi bi-plus"></i> '+response.put_simpanan_anggota+'').fadeIn(500);
                $('#put_penarikan_dana').hide().html('- '+response.put_penarikan_dana+'').fadeIn(500);
                CountOfPinjaman();
            } else {
                $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>' + response.message + '</small></div>').fadeIn(500);
            }
        },
        error: function() {
            $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>Terjadi Kesalahan Pada Sistem Saat Menghitung Simpanan!</small></div>').fadeIn(500);
        },
    });
}
// Fungsi Untuk Menampilkan Data Pinjaman Anggota
function CountOfPinjaman() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/CountOfPinjaman.php',
        dataType: "json",
        success: function(response) {
            if (response.status == "Success") {
                $('#put_pinjaman_anggota').hide().html('<i class="bi bi-bank"></i> '+response.put_pinjaman_anggota+'').fadeIn(500);
                $('#put_sesi_pinjaman').hide().html('<i class="bi bi-person-circle"></i> '+response.put_sesi_pinjaman+'').fadeIn(500);

                CountOfAngsuran();
            } else {
                $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>' + response.message + '</small></div>').fadeIn(500);
            }
        },
        error: function() {
            $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>Terjadi Kesalahan Pada Sistem Saat Menghitung Pinjaman!</small></div>').fadeIn(500);
        },
    });
}
// Fungsi Untuk Menampilkan Data Angsuran
function CountOfAngsuran() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/CountOfAngsuran.php',
        dataType: "json",
        success: function(response) {
            if (response.status == "Success") {
                $('#put_nominal_angsuran').hide().html('<i class="bi bi-coin"></i> '+response.put_nominal_angsuran+'').fadeIn(500);
                $('#put_record_angsuran').hide().html('<i class="bi bi-table"></i> '+response.put_record_angsuran+'').fadeIn(500);
                CountOfPenjualan();
            } else {
                $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>' + response.message + '</small></div>').fadeIn(500);
            }
        },
        error: function() {
            $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>Terjadi Kesalahan Pada Sistem Saat Menghitung Angsuran!</small></div>').fadeIn(500);
        },
    });
}
// Fungsi Untuk Menampilkan Data Penjualan
function CountOfPenjualan() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/CountOfPenjualan.php',
        dataType: "json",
        success: function(response) {
            if (response.status == "Success") {
                $('#put_nominal_penjualan').hide().html('<i class="bi bi-coin"></i> '+response.put_nominal_penjualan+'').fadeIn(500);
                $('#put_record_penjualan').hide().html('<i class="bi bi-table"></i> ('+response.put_record_penjualan+')').fadeIn(500);
                CountOfPembelian();
            } else {
                $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>' + response.message + '</small></div>').fadeIn(500);
            }
        },
        error: function() {
            $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>Terjadi Kesalahan Pada Sistem Saat Menghitung Penjualan!</small></div>').fadeIn(500);
        },
    });
}
// Fungsi Untuk Menampilkan Data Pembelan
function CountOfPembelian() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/CountOfPembelian.php',
        dataType: "json",
        success: function(response) {
            if (response.status == "Success") {
                $('#put_nominal_pembelian').hide().html('<i class="bi bi-coin"></i> '+response.put_nominal_pembelian+'').fadeIn(500);
                $('#put_record_pembelian').hide().html('<i class="bi bi-table"></i> ('+response.put_record_pembelian+')').fadeIn(500);
                CountOfBagiHasil();
            } else {
                $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>' + response.message + '</small></div>').fadeIn(500);
            }
        },
        error: function() {
            $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>Terjadi Kesalahan Pada Sistem Saat Menghitung Penjualan!</small></div>').fadeIn(500);
        },
    });
}
// Fungsi Untuk Menampilkan Data Bagi Hasil
function CountOfBagiHasil() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/CountOfBagiHasil.php',
        dataType: "json",
        success: function(response) {
            if (response.status == "Success") {
                $('#put_nominal_bagi_hasil').hide().html('<i class="bi bi-coin"></i> '+response.put_nominal_bagi_hasil+'').fadeIn(500);
                $('#put_record_bagii_hasil').hide().html('<i class="bi bi-table"></i> ('+response.put_record_bagii_hasil+')').fadeIn(500);
                CountOfTransaksiOperasional();
            } else {
                $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>' + response.message + '</small></div>').fadeIn(500);
            }
        },
        error: function() {
            $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>Terjadi Kesalahan Pada Sistem Saat Menghitung Penjualan!</small></div>').fadeIn(500);
        },
    });
}
// Fungsi Untuk Menampilkan Data Transaksi Operasional
function CountOfTransaksiOperasional() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/CountOfTransaksiOperasional.php',
        dataType: "json",
        success: function(response) {
            if (response.status == "Success") {
                $('#put_nominal_transaksi').hide().html('<i class="bi bi-coin"></i> '+response.put_nominal_transaksi+'').fadeIn(500);
                $('#put_record_transaksi').hide().html('<i class="bi bi-table"></i> ('+response.put_record_transaksi+')').fadeIn(500);
                ShowPemberitahuanSistem();
            } else {
                $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>' + response.message + '</small></div>').fadeIn(500);
            }
        },
        error: function() {
            $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>Terjadi Kesalahan Pada Sistem Saat Menghitung Penjualan!</small></div>').fadeIn(500);
        },
    });
}
// Fungsi Untuk Menampilkan Pemberitahuan Sistem
function ShowPemberitahuanSistem() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/ShowPemberitahuanSistem.php',
        success: function(response) {
            $('#ShowPemberitahuanSistem').hide().html(response).fadeIn(500);
            ShowAnggotaTerbaru();
        }
    });
}
// Fungsi Untuk Menampilkan Anggota Terbaru
function ShowAnggotaTerbaru() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/ShowAnggotaTerbaru.php',
        success: function(response) {
            $('#ShowAnggotaTerbaru').hide().html(response).fadeIn(500);
            ShowSimpananTerbaru();
        }
    });
}
// Fungsi Untuk Menampilkan Simpanan Terbaru
function ShowSimpananTerbaru() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/ShowSimpananTerbaru.php',
        success: function(response) {
            $('#ShowSimpananTerbaru').hide().html(response).fadeIn(500);
            ShowPinjamanTerbaru();
        }
    });
}
// Fungsi Untuk Menampilkan Pinjaman Terbaru
function ShowPinjamanTerbaru() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/ShowPinjamanTerbaru.php',
        success: function(response) {
            $('#ShowPinjamanTerbaru').hide().html(response).fadeIn(500);
        }
    });
}
// Fungsi Untuk Menampilkan Grafik
function ShowGrafikSiimpanPinjam() {
    // Fungsi untuk mengambil data dari file JSON
    $.getJSON("_Page/Dashboard/GrafikTransaksi.json", function (data) {
        // Mengolah data untuk ApexCharts
        const categories = data.map(item => item.x);
        const simpananSeries = data.map(item => parseFloat(item.ySimpanan));
        const pinjamanSeries = data.map(item => parseFloat(item.yPinjaman));

        // Konfigurasi grafik
        var options = {
            chart: {
                type: 'bar',
                height: 400
            },
            series: [
                {
                    name: 'Simpanan',
                    data: simpananSeries
                },
                {
                    name: 'Pinjaman',
                    data: pinjamanSeries
                }
            ],
            xaxis: {
                categories: categories
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
                    }
                }
            },
            dataLabels: {
                enabled: false // Menonaktifkan label nilai pada bar
            }
        };

        // Inisialisasi grafik
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    });
}

// Fungsi untuk menampilkan jam digital
function tampilkanJam() {
    const waktu = new Date();
    let jam = waktu.getHours().toString().padStart(2, '0');
    let menit = waktu.getMinutes().toString().padStart(2, '0');
    let detik = waktu.getSeconds().toString().padStart(2, '0');

    $('#jam_menarik').text(`${jam}:${menit}:${detik}`);
}

// Fungsi untuk menampilkan tanggal
function tampilkanTanggal() {
    const waktu = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const tanggal = waktu.toLocaleDateString('id-ID', options);
    
    $('#tanggal_menarik').text(tanggal);
}
// Fungsi Untuk Menampilkan Kalendaer
function show_calendar() {
    var calendarEl = document.getElementById('show_calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth'
    });
    calendar.render();
}
$(document).ready(function () {
    //Menampilkan Data Pertama Kali
    CountOfBarang();
    show_calendar();
    ShowGrafikSiimpanPinjam();
    //Jam Menarik
    tampilkanTanggal(); // Tampilkan tanggal saat halaman dimuat
    tampilkanJam();     // Tampilkan jam pertama kali
    setInterval(tampilkanJam, 1000); // Perbarui jam setiap detik
});