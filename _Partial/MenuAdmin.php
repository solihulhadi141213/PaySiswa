<aside id="sidebar" class="sidebar menu_background">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu==""){echo "";}else{echo "collapsed";} ?>" href="index.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu=="AksesFitur"||$PageMenu=="AksesEntitas"||$PageMenu=="Akses"){echo "";}else{echo "collapsed";} ?>" data-bs-target="#akses-nav" data-bs-toggle="collapse" href="javascript:void(0);">
                <i class="bi bi-person"></i>
                <span>Akses</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="akses-nav" class="nav-content collapse <?php if($PageMenu=="AksesFitur"||$PageMenu=="AksesEntitas"||$PageMenu=="Akses"){echo "show";} ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="index.php?Page=AksesFitur" class="<?php if($PageMenu=="AksesFitur"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Fitur Aplikasi</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=AksesEntitas" class="<?php if($PageMenu=="AksesEntitas"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Group/Entitas</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=Akses" class="<?php if($PageMenu=="Akses"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Akses Pengguna</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu=="Kelas"||$PageMenu=="Siswa"){echo "";}else{echo "collapsed";} ?>" data-bs-target="#siswa-nav" data-bs-toggle="collapse" href="javascript:void(0);">
                <i class="bi bi-people"></i>
                <span>Siswa</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="siswa-nav" class="nav-content collapse <?php if($PageMenu=="Kelas"||$PageMenu=="Siswa"){echo "show";} ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="index.php?Page=Kelas" class="<?php if($PageMenu=="Kelas"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Group/Kelas</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=Siswa" class="<?php if($PageMenu=="Siswa"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Daftar Siswa</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu=="JenisSimpanan"||$PageMenu=="SimpananWajib"||$PageMenu=="Tabungan"||$PageMenu=="RekapSimpanan"){echo "";}else{echo "collapsed";} ?>" data-bs-target="#simpanan-nav" data-bs-toggle="collapse" href="javascript:void(0);">
                <i class="bi bi-wallet"></i>
                <span>Biaya</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="simpanan-nav" class="nav-content collapse <?php if($PageMenu=="JenisSimpanan"||$PageMenu=="SimpananWajib"||$PageMenu=="Tabungan"||$PageMenu=="RekapSimpanan"){echo "show";} ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="index.php?Page=JenisSimpanan" class="<?php if($PageMenu=="JenisSimpanan"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Komponen Biaya</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=SimpananWajib" class="<?php if($PageMenu=="SimpananWajib"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Pembayaran-Tagihan</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu=="SimpanPinjam"||$PageMenu=="BukuBesar"||$PageMenu=="NeracaSaldo"||$PageMenu=="LabaRugi"||$PageMenu=="RiwayatSimpanPinjam"){echo "";}else{echo "collapsed";} ?>" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="javascript:void(0);">
                <i class="bi bi-bar-chart"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="charts-nav" class="nav-content collapse <?php if($PageMenu=="SimpanPinjam"||$PageMenu=="BukuBesar"||$PageMenu=="NeracaSaldo"||$PageMenu=="LabaRugi"||$PageMenu=="RiwayatSimpanPinjam"){echo "show";} ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="index.php?Page=SimpanPinjam" class="<?php if($PageMenu=="SimpanPinjam"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Simpan-Pinjam</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=BukuBesar" class="<?php if($PageMenu=="BukuBesar"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Buku Besar</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=NeracaSaldo" class="<?php if($PageMenu=="NeracaSaldo"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Neraca saldo</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=LabaRugi" class="<?php if($PageMenu=="LabaRugi"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Laba Rugi</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=RiwayatSimpanPinjam" class="<?php if($PageMenu=="RiwayatSimpanPinjam"){echo "active";} ?>">
                    <i class="bi bi-circle"></i><span>Riwayat Anggota</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu=="SettingGeneral"||$PageMenu=="SettingEmail"||$PageMenu=="PaymentGateway"){echo "";}else{echo "collapsed";} ?>" data-bs-target="#components-nav" data-bs-toggle="collapse" href="javascript:void(0);">
                <i class="bi bi-gear"></i>
                    <span>Pengaturan</span><i class="bi bi-chevron-down ms-auto">
                </i>
            </a>
            <ul id="components-nav" class="nav-content collapse <?php if($PageMenu=="SettingGeneral"||$PageMenu=="SettingEmail"||$PageMenu=="PaymentGateway"){echo "show";} ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="index.php?Page=SettingGeneral" class="<?php if($PageMenu=="SettingGeneral"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Pengaturan Umum</span>
                    </a>
                </li> 
                <li>
                    <a href="index.php?Page=PaymentGateway" class="<?php if($PageMenu=="PaymentGateway"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Payment Gateway</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=SettingEmail" class="<?php if($PageMenu=="SettingEmail"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Email Gateway</span>
                    </a>
                </li> 
            </ul>
        </li>
        <li class="nav-heading">Fitur Lainnya</li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu!=="Aktivitas"){echo "collapsed";} ?>" href="index.php?Page=Aktivitas&Sub=AktivitasUmum">
                <i class="bi bi-circle"></i>
                <span>Log Aktivitas</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu!=="Help"){echo "collapsed";} ?>" href="index.php?Page=Help&Sub=HelpData">
                <i class="bi bi-question"></i>
                <span>Dokumentasi</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalLogout">
                <i class="bi bi-box-arrow-in-left"></i>
                <span>Keluar</span>
            </a>
        </li>
    </ul>
</aside> 