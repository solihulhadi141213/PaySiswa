<?php
    // Koneksi dan session
    include "_Config/Connection.php";

    // Koneksi Redis
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);

    // =========================================
    // 1. CACHE STUDENT
    // =========================================
    $students_cache = $redis->get('cache:students');

    if ($students_cache) {

        // Load dari Redis
        $students = json_decode($students_cache, true);

    } else {

        // Query asli (TIDAK DIUBAH)
        $query1 = mysqli_query($Conn, "SELECT * FROM student");
        $students = [];
        while ($row = mysqli_fetch_array($query1)) {
            $students[] = $row;
        }

        // Simpan ke Redis (cache 600 detik = 10 menit)
        $redis->setex('cache:students', 600, json_encode($students));
    }

    // =========================================
    // 2. CACHE ACCESS LOG
    // =========================================
    $logs_cache = $redis->get('cache:accesslog');

    if ($logs_cache) {

        $access_logs = json_decode($logs_cache, true);

    } else {

        // Query asli (TIDAK DIUBAH)
        $query3 = mysqli_query($Conn, "SELECT * FROM access_log");
        $access_logs = [];
        while ($row = mysqli_fetch_array($query3)) {
            $access_logs[] = $row;
        }

        // Cache 10 menit
        $redis->setex('cache:accesslog', 600, json_encode($access_logs));
    }

    // =========================================
    // 3. LOOP DATA (Fee per student → Access Log)
    // =========================================

    $no = 1;

    foreach ($students as $data1) {

        $id_student   = $data1['id_student'];
        $student_name = $data1['student_name'];

        // -----------------------------
        // CACHE FEE PER STUDENT
        // -----------------------------
        $fee_key = "cache:fee_by_student:" . $id_student;
        $fee_cache = $redis->get($fee_key);

        if ($fee_cache) {

            $fee_list = json_decode($fee_cache, true);

        } else {

            // Query asli (TIDAK DIUBAH)
            $query2 = mysqli_query($Conn, "SELECT * FROM fee_by_student WHERE id_student='$id_student'");
            $fee_list = [];
            while ($row = mysqli_fetch_array($query2)) {
                $fee_list[] = $row;
            }

            // Cache 10 menit
            $redis->setex($fee_key, 600, json_encode($fee_list));
        }

        // -----------------------------
        // LOOP FEE
        // -----------------------------
        foreach ($fee_list as $data2) {

            $fee_nominal = empty($data2['fee_nominal']) ? 0 : $data2['fee_nominal'];

            // -----------------------------
            // LOOP ACCESS LOG DARI CACHE
            // -----------------------------
            foreach ($access_logs as $data3) {

                $log_category = empty($data3['log_category']) ? "-" : $data3['log_category'];

                echo "{$no}. {$student_name} ({$fee_nominal})<br>";
                $no++;
            }

            // Baris terakhir
            echo "{$no}. {$student_name} ({$fee_nominal}) | {$log_category}<br>";
            $no++;
        }
    }
?>
