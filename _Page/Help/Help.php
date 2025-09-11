
<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAccess,'Dnd2UZLzazCqJ9WfuzQKlIOpYueb2fXxNHXA');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
        echo '
            <div class="pagetitle">
                <h1>
                    <a href="">
                        <i class="bi bi-question-circle"></i> Dokumentasi</a>
                    </a>
                </h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Dokumentasi</li>
                    </ol>
                </nav>
            </div>
        ';
        if(empty($_GET['Sub'])){
            include "_Page/Help/HelpHome.php";
        }else{
            $Sub=$_GET['Sub'];
            if($Sub=="HelpData"){
                include "_Page/Help/HelpData.php";
            }else{
                if($Sub=="HelpHome"){
                    include "_Page/Help/HelpHome.php";
                }else{
                    if($Sub=="TambahHelp"){
                        include "_Page/Help/FormTambahHelp.php";
                    }else{
                        if($Sub=="EditHelp"){
                            include "_Page/Help/FormEditHelp.php";
                        }else{
                            if($Sub=="DetailHelp"){
                                include "_Page/Help/DetailHelp.php";
                            }
                        }
                    }
                }
            }
        }
    }
?>