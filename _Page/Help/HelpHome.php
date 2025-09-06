<?php
    //Get keyword
    if(!empty($_GET['keyword'])){
        $keyword=$_GET['keyword'];
    }else{
        $keyword="";
    }
    if(!empty($_GET['keyword_by'])){
        $keyword_by=$_GET['keyword_by'];
    }else{
        $keyword_by="";
    }
    //Menghitung Semua Data Bantuan Yang Publish
    $JumlahSemuaKonten = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM help WHERE status='Publish'"));
?>
<section class="section dashboard">
    <div class="row">
        <div class="col-md-12">
            <?php
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                echo '  <small class="mobile-text">';
                echo '      Berikut ini adalah halaman bantuan yang bisa digunakan oleh pengguna untuk memahami bagaimana cara kerja dan cara penggunaan aplikasi secara terperinci.';
                echo '      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '  </small>';
                echo '</div>';
            ?>
        </div>
    </div>
    <form action="javascript:void(0);" id="ProsesFilterBantuan">
        <input type="hidden" name="page" id="PutPageBantuan" value="1">
        <input type="hidden" name="keyword" id="keyword" value="<?php echo "$keyword"; ?>">
        <input type="hidden" name="keyword_by" id="keyword_by" value="<?php echo "$keyword_by"; ?>">
    </form>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <b class="card-title">
                        <i class="bi bi-tag"></i> Kategori Bantuan
                    </b>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="index.php?Page=Help&Sub=HelpHome" class="text <?php if(empty($keyword_by)){echo "text-primary";}else{echo "text-grayish";} ?>">
                                Semua Konten
                            </a>
                            <span class="badge <?php if(empty($keyword_by)){echo "bg-primary";}else{echo "bg-grayish";} ?> rounded-pill"><?php echo ''.$JumlahSemuaKonten.''; ?></span>
                        </li>
                        <?php
                            $QryKategori = mysqli_query($Conn, "SELECT DISTINCT kategori FROM help ORDER BY kategori ASC");
                            while ($DataKategoriHelp = mysqli_fetch_array($QryKategori)) {
                                $CategoryHelp= $DataKategoriHelp['kategori'];
                                $JumlahKonten = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM help WHERE kategori='$CategoryHelp' AND status='Publish'"));
                                if(empty($keyword_by)){
                                    echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                                    echo '  <a href="index.php?Page=Help&Sub=HelpHome&keyword_by=kategori&keyword='.$CategoryHelp.'" class="text-grayish">';
                                    echo '      '.$CategoryHelp.'';
                                    echo '  </a>';
                                    echo '  <span class="badge bg-grayish rounded-pill">'.$JumlahKonten.'</span>';
                                    echo '</li>';
                                }else{
                                    if($keyword==$CategoryHelp&&$keyword_by=="kategori"){
                                        echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                                        echo '  <a href="index.php?Page=Help&Sub=HelpHome&keyword_by=kategori&keyword='.$CategoryHelp.'" class="text-primary">';
                                        echo '      '.$CategoryHelp.'';
                                        echo '  </a>';
                                        echo '  <span class="badge bg-primary rounded-pill">'.$JumlahKonten.'</span>';
                                        echo '</li>';
                                    }else{
                                        echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                                        echo '  <a href="index.php?Page=Help&Sub=HelpHome&keyword_by=kategori&keyword='.$CategoryHelp.'" class="text-grayish">';
                                        echo '      '.$CategoryHelp.'';
                                        echo '  </a>';
                                        echo '  <span class="badge bg-grayish rounded-pill">'.$JumlahKonten.'</span>';
                                        echo '</li>';
                                    }
                                }
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <b class="card-title">
                        <i class="bi bi-table"></i> List Bantuan
                    </b>
                </div>
                <div class="card-body" id="MenampilkanListHelp">
                    <!-- Menampilkan List Bantuan Disini -->
                </div>
            </div>
        </div>
    </div>
</section>