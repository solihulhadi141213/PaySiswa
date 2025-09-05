<aside id="sidebar" class="sidebar menu_background">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu==""){echo "";}else{echo "collapsed";} ?>" href="index.php">
                <i class="bi bi-grid"></i> <span>Dashboard</span>
            </a>
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
        <li class="nav-heading">Akses</li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu=="AksesFitur"){echo "";}else{echo "collapsed";} ?>" href="index.php?Page=AksesFitur">
                <i class="bi bi-app"></i> <span>Fitur Aplikasi</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu=="AksesEntitas"){echo "";}else{echo "collapsed";} ?>" href="index.php?Page=AksesEntitas">
                <i class="bi bi-layers"></i> <span>Group/Entitas</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu=="Akses"){echo "";}else{echo "collapsed";} ?>" href="index.php?Page=Akses">
                <i class="bi bi-person-circle"></i> <span>Akses Pengguna</span>
            </a>
        </li>
        <li class="nav-heading">Referensi</li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu=="Kelas"){echo "";}else{echo "collapsed";} ?>" href="index.php?Page=Kelas">
                <i class="bi bi-building"></i> <span>Kelas</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu=="KomponenBiaya"){echo "";}else{echo "collapsed";} ?>" href="index.php?Page=KomponenBiaya">
                <i class="bi bi-tags"></i> <span>Komponen Biaya</span>
            </a>
        </li>
        <li class="nav-heading">Master</li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu=="Siswa"){echo "";}else{echo "collapsed";} ?>" href="index.php?Page=Siswa">
                <i class="bi bi-people"></i> <span>Siswa</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu=="Tagihan"){echo "";}else{echo "collapsed";} ?>" href="index.php?Page=Tagihan">
                <i class="bi bi-calendar-week"></i> <span>Tagihan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu=="Pembayaran"){echo "";}else{echo "collapsed";} ?>" href="index.php?Page=Pembayaran">
                <i class="bi bi-cash-coin"></i> <span>Pembayaran</span>
            </a>
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