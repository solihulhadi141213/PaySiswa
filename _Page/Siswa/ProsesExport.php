<?php
    require_once "../../_Config/Connection.php";
    require_once "../../vendor/autoload.php";

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    // Tangkap Tipe File
    $tipe_file = !empty($_GET['tipe_file_siswa'])
        ? $_GET['tipe_file_siswa']
        : 'EXCEL';

    // =====================================================
    // EXPORT EXCEL
    // =====================================================
    if($tipe_file == "EXCEL"){

        // Buat Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul Sheet
        $sheet->setTitle('Data Siswa');

        // Header
        $header = [
            'A1' => 'No',
            'B1' => 'NIS',
            'C1' => 'NISN',
            'D1' => 'Nama',
            'E1' => 'Gender',
            'F1' => 'Tempat Lahir',
            'G1' => 'Tanggal Lahir',
            'H1' => 'Kontak',
            'I1' => 'Email',
            'J1' => 'Alamat',
            'K1' => 'Tanggal Daftar',
            'L1' => 'Kelas',
            'M1' => 'Status'
        ];

        foreach($header as $cell => $value){
            $sheet->setCellValue($cell, $value);
        }

        // Header Bold
        $sheet->getStyle('A1:M1')->getFont()->setBold(true);

        // Query Data
        $query = "
            SELECT
                student.student_nis,
                student.student_nisn,
                student.student_name,
                student.student_gender,
                student.place_of_birth,
                student.date_of_birth,
                student.student_contact,
                student.student_email,
                student.student_address,
                student.student_registered,
                student.student_status,
                organization_class.class_level,
                organization_class.class_name
            FROM student
            LEFT JOIN organization_class
                ON student.id_organization_class =
                organization_class.id_organization_class
            ORDER BY student.student_name ASC
        ";

        $result = mysqli_query($Conn, $query);

        $row = 2;
        $no  = 1;

        while($data = mysqli_fetch_assoc($result)){

            $kelas = '';

            if(!empty($data['class_level']) || !empty($data['class_name'])){
                $kelas = $data['class_level'].' '.$data['class_name'];
            }

            $sheet->setCellValue('A'.$row, $no);
            $sheet->setCellValue('B'.$row, $data['student_nis']);
            $sheet->setCellValue('C'.$row, $data['student_nisn']);
            $sheet->setCellValue('D'.$row, $data['student_name']);
            $sheet->setCellValue('E'.$row, $data['student_gender']);
            $sheet->setCellValue('F'.$row, $data['place_of_birth']);
            $sheet->setCellValue('G'.$row, $data['date_of_birth']);
            $sheet->setCellValue('H'.$row, $data['student_contact']);
            $sheet->setCellValue('I'.$row, $data['student_email']);
            $sheet->setCellValue('J'.$row, $data['student_address']);
            $sheet->setCellValue('K'.$row, $data['student_registered']);
            $sheet->setCellValue('L'.$row, $kelas);
            $sheet->setCellValue('M'.$row, $data['student_status']);

            $row++;
            $no++;
        }

        // Auto Size Semua Kolom
        foreach(range('A','M') as $column){
            $sheet->getColumnDimension($column)
                ->setAutoSize(true);
        }

        // Freeze Header
        $sheet->freezePane('A2');

        // Nama File
        $filename = 'Data_Siswa_'.date('Ymd_His').'.xlsx';

        // Header Download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // =====================================================
    // EXPORT HTML
    // =====================================================
    if($tipe_file == "HTML"){

        $query = "
            SELECT
                student.student_nis,
                student.student_nisn,
                student.student_name,
                student.student_gender,
                student.place_of_birth,
                student.date_of_birth,
                student.student_contact,
                student.student_email,
                student.student_address,
                student.student_registered,
                student.student_status,
                organization_class.class_level,
                organization_class.class_name
            FROM student
            LEFT JOIN organization_class
                ON student.id_organization_class =
                organization_class.id_organization_class
            ORDER BY student.student_name ASC
        ";

        $result = mysqli_query($Conn, $query);

        echo '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Data Siswa</title>
            <style>
                body{
                    font-family: Arial, sans-serif;
                    font-size:12px;
                    padding:20px;
                }
                table{
                    border-collapse: collapse;
                    width:100%;
                }
                table th,
                table td{
                    border:1px solid #000;
                    padding:6px;
                    vertical-align:top;
                }
                table th{
                    background:#f2f2f2;
                    font-weight:bold;
                }
            </style>
        </head>
        <body>

            <h3>Data Siswa</h3>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Gender</th>
                        <th>Tempat Lahir</th>
                        <th>Tanggal Lahir</th>
                        <th>Kontak</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th>Tanggal Daftar</th>
                        <th>Kelas</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
        ';

        $no = 1;

        while($data = mysqli_fetch_assoc($result)){

            $kelas = trim(
                $data['class_level'].' '.$data['class_name']
            );

            echo '
                <tr>
                    <td>'.$no.'</td>
                    <td>'.$data['student_nis'].'</td>
                    <td>'.$data['student_nisn'].'</td>
                    <td>'.$data['student_name'].'</td>
                    <td>'.$data['student_gender'].'</td>
                    <td>'.$data['place_of_birth'].'</td>
                    <td>'.$data['date_of_birth'].'</td>
                    <td>'.$data['student_contact'].'</td>
                    <td>'.$data['student_email'].'</td>
                    <td>'.$data['student_address'].'</td>
                    <td>'.$data['student_registered'].'</td>
                    <td>'.$kelas.'</td>
                    <td>'.$data['student_status'].'</td>
                </tr>
            ';

            $no++;
        }

        echo '
                </tbody>
            </table>

        </body>
        </html>
        ';

        exit;
    }

    // =====================================================
    // EXPORT CSV
    // =====================================================
    if($tipe_file == "CSV"){

        $filename = 'Data_Siswa_'.date('Ymd_His').'.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$filename.'"');

        $output = fopen('php://output', 'w');

        // Header
        fputcsv($output, [
            'No',
            'NIS',
            'NISN',
            'Nama',
            'Gender',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Kontak',
            'Email',
            'Alamat',
            'Tanggal Daftar',
            'Kelas',
            'Status'
        ]);

        $query = "
            SELECT
                student.student_nis,
                student.student_nisn,
                student.student_name,
                student.student_gender,
                student.place_of_birth,
                student.date_of_birth,
                student.student_contact,
                student.student_email,
                student.student_address,
                student.student_registered,
                student.student_status,
                organization_class.class_level,
                organization_class.class_name
            FROM student
            LEFT JOIN organization_class
                ON student.id_organization_class =
                organization_class.id_organization_class
            ORDER BY student.student_name ASC
        ";

        $result = mysqli_query($Conn, $query);

        $no = 1;

        while($data = mysqli_fetch_assoc($result)){

            $kelas = trim(
                $data['class_level'].' '.$data['class_name']
            );

            fputcsv($output, [
                $no,
                $data['student_nis'],
                $data['student_nisn'],
                $data['student_name'],
                $data['student_gender'],
                $data['place_of_birth'],
                $data['date_of_birth'],
                $data['student_contact'],
                $data['student_email'],
                $data['student_address'],
                $data['student_registered'],
                $kelas,
                $data['student_status']
            ]);

            $no++;
        }

        fclose($output);
        exit;
    }
?>