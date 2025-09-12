<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");
    if (empty($SessionIdAccess)) {
        echo '
           <td colspan="7" class="text-center">
                <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang.</small>
            </td>
        ';
        exit;
    }
    //Keyword_by
    if(!empty($_POST['keyword_by'])){
        $keyword_by=$_POST['keyword_by'];
    }else{
        $keyword_by="";
    }
    //keyword
    if(!empty($_POST['keyword'])){
        $keyword=$_POST['keyword'];
    }else{
        $keyword="";
    }
    //batas
    if(!empty($_POST['batas'])){
        $batas=$_POST['batas'];
    }else{
        $batas="10";
    }
    //ShortBy
    if(!empty($_POST['ShortBy'])){
        $ShortBy=$_POST['ShortBy'];
    }else{
        $ShortBy="DESC";
    }
    //OrderBy
    if(!empty($_POST['OrderBy'])){
        $OrderBy=$_POST['OrderBy'];
    }else{
        $OrderBy="id_help";
    }
    //Atur Page dan posisi
    if(!empty($_POST['page'])){
        $page=$_POST['page'];
        $posisi = ( $page - 1 ) * $batas;
    }else{
        $page="1";
        $posisi = 0;
    }
    // Kolom yang digunakan untuk pencarian
    $columns = ['id_help', 'author', 'judul', 'kategori', 'deskripsi', 'datetime_creat', 'datetime_update', 'status'];
    $keyword_qry = mysqli_real_escape_string($Conn, $keyword);
    $conditions = [];
    foreach ($columns as $column) {
        $conditions[] = "$column LIKE '%$keyword_qry%'";
    }
    $whereClause = implode(' OR ', $conditions);

    // Mencari jumlah data
    $query = "SELECT COUNT(*) as jml_data FROM help";
    if (!empty($keyword)) {
        if (empty($keyword_by)) {
            $query .= " WHERE $whereClause";
        } else {
            $keyword_by_qry = mysqli_real_escape_string($Conn, $keyword_by);
            $query .= " WHERE $keyword_by_qry LIKE '%$keyword_qry%'";
        }
    }

    $result = $Conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        $jml_data = $row['jml_data'];
    } else {
        $jml_data=0;
    }
    //Mengatur Halaman
    $JmlHalaman = ceil($jml_data/$batas); 
    if(empty($jml_data)){
        echo '
           <td colspan="7" class="text-center">
                <small class="text-danger">Tidak Ada Data Dokumentasi Yang Ditampilkan!</small>
            </td>
        ';
        exit;
    }
    $no = 1+$posisi;
    //KONDISI PENGATURAN MASING FILTER
    if(empty($keyword_by)){
        if(empty($keyword)){
            $query = mysqli_query($Conn, "SELECT*FROM help ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
        }else{
            $query = mysqli_query($Conn, "SELECT*FROM help WHERE $whereClause  ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
        }
    }else{
        if(empty($keyword)){
            $query = mysqli_query($Conn, "SELECT*FROM help  ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
        }else{
            $query = mysqli_query($Conn, "SELECT*FROM help WHERE $keyword_by like '%$keyword%' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
        }
    }
    while ($data = mysqli_fetch_array($query)) {
        $id_help= $data['id_help'];
        $author= $data['author'];
        $judul= $data['judul'];
        $kategori= $data['kategori'];
        $deskripsi= $data['deskripsi'];
        $datetime_creat= $data['datetime_creat'];
        $datetime_update= $data['datetime_update'];
        $status= $data['status'];
        //Format Tangga
        $strtotime1=strtotime($datetime_creat);
        $strtotime2=strtotime($datetime_update);
        $TanggalCreatFormat=date('d/m/Y H:i T',$strtotime1);
        $TanggalUpdateFormat=date('d/m/Y H:i T',$strtotime2);

        if($status=="Publish"){
            $label_status='<badge class="badge badge-success">Publish</badge>';
        }else{
            $label_status='<badge class="badge badge-warning">Draft</badge>';
        }
        echo '
            <tr>
                <td><small>'.$no.'</small></td>
                <td>
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalDetail" data-id="'.$id_help.'">
                        <small class="text text-decoration-underline">'.$judul.'</small>
                    </a>
                </td>
                <td><small>'.$kategori.'</small></td>
                <td><small>'.$author.'</small></td>
                <td><small>'.$TanggalCreatFormat.'</small></td>
                <td class="text-center"><small>'.$label_status.'</small></td>
                <td class="text-center">
                    <a class="btn btn-sm btn-outline-dark btn-floating" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <small><i class="bi bi-three-dots"></i></small>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalDetail" data-id="'.$id_help.'">
                                <i class="bi bi-info-circle"></i> Detail
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalEdit" data-id="'.$id_help.'">
                                <i class="bi bi-pencil-square"></i> Ubah/Edit
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalHapus" data-id="'.$id_help.'">
                                <i class="bi bi-x"></i> Hapus
                            </a>
                        </li>
                    </ul>
                </td>
            </tr>
        ';
        $no++;
    }
?> 
    <script>
        //Creat Javascript Variabel
        var page_count=<?php echo $JmlHalaman; ?>;
        var curent_page=<?php echo $page; ?>;
        
        //Put Into Pagging Element
        $('#page_info').html('Page '+curent_page+' Of '+page_count+'');
        
        //Set Pagging Button
        if(curent_page==1){
            $('#prev_button').prop('disabled', true);
        }else{
            $('#prev_button').prop('disabled', false);
        }
        if(page_count<=curent_page){
            $('#next_button').prop('disabled', true);
        }else{
            $('#next_button').prop('disabled', false);
        }
    </script>   