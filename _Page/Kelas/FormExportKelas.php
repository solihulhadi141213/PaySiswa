<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    
    //Zona Waktu
    date_default_timezone_set("Asia/Jakarta");

    //Session Akses
    if(empty($SessionIdAccess)){
        echo '
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <div class="alert alert-danger"><small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang.</small></div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-md btn-secondary btn-rounded btn-block" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </div>
        ';
        exit;
    }

    //id_academic_period wajib terisi
    if(empty($_POST['id_academic_period'])){
        echo '
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <div class="alert alert-danger"><small>ID Tagihan Tiidak Boleh Kosong!</small></div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-md btn-secondary btn-rounded btn-block" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </div>
        ';
        exit;
    }

    //Buat variabel 'id_academic_period'
    $id_academic_period      = $_POST['id_academic_period'];

    //Buka Detail periode akademik
    $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');
    $academic_period_start  = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_start');
    $academic_period_end    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_end');

    echo '
        <div class="row mb-2">
            <div class="col-5"><small>Periode Akademik</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small>'.$academic_period.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Periode Dimulai</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small>'.$academic_period_start.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Periode Berakhir</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small>'.$academic_period_end.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-12 text-center">
                <div class="alert alert-info"><small>Semakin banyak data pada periode tersebut maka semakin lama data diproses.</small></div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12">
                <a href="_page/Exporter/ExportKelas.php?id_academic_period='.$id_academic_period.'" class="btn btn-md btn-primary btn-rounded btn-block" target="_blank">
                    <i class="bi bi-download"></i> Export
                </a>
            </div>
        </div>
    ';
?>