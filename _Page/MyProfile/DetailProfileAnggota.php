<?php
    $tanggal_masuk=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'tanggal_masuk');
    $tanggal_keluar=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'tanggal_keluar');
    $email=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'email');
    $nip=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'nip');
    $nama=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'nama');
    $password=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'password');
    $kontak=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'kontak');
    $lembaga=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'lembaga');
    $ranking=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'ranking');
    $foto=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'foto');
    $akses_anggota=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'akses_anggota');
    $status=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'status');
    $alasan_keluar=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'alasan_keluar');
    if(empty($foto)){
        $foto="No-Image.PNG";
    }else{
        $foto=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'foto');
    }
    if($akses_anggota==1){
        $password=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'password');
        $password="*****";
    }else{
        $password="-";
    }
    if($status=="Keluar"){
        $strtotime2=strtotime($tanggal_keluar);
        $TanggalKeluar=date('d/m/Y', $strtotime2);
        $LabelStatus='<span class="text-danger">Keluar</span>';
    }else{
        $TanggalKeluar="-";
        $LabelStatus='<span class="text-success">Aktif</span>';
    }
    //Format Tanggal
    $strtotime1=strtotime($tanggal_masuk);
    //Menampilkan Tanggal
    $TanggalMasuk=date('d/m/Y', $strtotime1);
?>
<div class="pagetitle">
    <h1>
        <a href="">
            <i class="bi bi-person-circle"></i> Profil Saya</a>
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active"> Profil Saya</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row mb-3">
        <div class="col-md-12">
        <?php
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                echo '  <small>';
                echo '      Berikut ini adalah halaman profil yang digunakan untuk menampilkan informasi identitas keanggotaan anda.<br>';
                echo '      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '  </small>';
                echo '</div>';
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-10 mb-3">
                            <b class="card-title">
                                <i class="bi bi-info-circle"></i> Profil Anggota
                            </b>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a class="btn btn-md btn-outline-grayish btn-rounded btn-block" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots"></i> Option
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                                <li class="dropdown-header text-start">
                                    <h6>Option</h6>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalUbahEmailAnggota">
                                        <i class="bi bi-envelope"></i> Ubah Email
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalUbahPasswordAnggota">
                                        <i class="bi bi-key"></i> Ubah Password
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalUbahFotoAnggota">
                                        <i class="bi bi-image"></i> Ubah Foto
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col col-md-3 text-center mb-4">
                            <img src="assets/img/Anggota/<?php echo "$foto"; ?>" alt="" width="70%" class="rounded-circle">
                        </div>
                        <div class="col col-md-9 mb-4">
                            <div class="row mb-3">
                                <div class="col col-md-4">Nama Lengkap</div>
                                <div class="col col-md-8">
                                    <small class="credit">
                                        <code class="text text-grayish"><?php echo "$nama"; ?></code>
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-md-4">NIP</div>
                                <div class="col col-md-8">
                                    <small class="credit">
                                        <code class="text text-grayish"><?php echo "$nip"; ?></code>
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-md-4">Kontak</div>
                                <div class="col col-md-8">
                                    <small class="credit">
                                        <code class="text text-grayish"><?php echo "$kontak"; ?></code>
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-md-4">Email</div>
                                <div class="col col-md-8">
                                    <small class="credit">
                                        <code class="text text-grayish"><?php echo "$email"; ?></code>
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col col-md-4">Status</div>
                                <div class="col col-md-8">
                                    <small class="credit">
                                        <code class="text text-grayish"><?php echo "$status"; ?></code>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
