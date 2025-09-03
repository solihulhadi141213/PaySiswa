<?php
    // Persiapkan query dengan prepared statement
    $sql = "SELECT * FROM app_configuration  WHERE id_configuration = ?";
    $stmt = $Conn->prepare($sql);

    // Bind parameter (tipe data integer "i")
    $id = 1;
    $stmt->bind_param("i", $id);

    // Eksekusi statement
    $stmt->execute();

    // Ambil hasil query
    $result = $stmt->get_result();
    $DataSettingGeneral = $result->fetch_assoc();

    // Simpan hasil ke variabel
    $id_configuration = $DataSettingGeneral['id_configuration'] ?? null;
    $app_title = $DataSettingGeneral['app_title'] ?? null;
    $app_keyword = $DataSettingGeneral['app_keyword'] ?? null;
    $app_description = $DataSettingGeneral['app_description'] ?? null;
    $app_favicon = $DataSettingGeneral['app_favicon'] ?? null;
    $app_logo = $DataSettingGeneral['app_logo'] ?? null;
    $app_base_url = $DataSettingGeneral['app_base_url'] ?? null;
    $app_author = $DataSettingGeneral['app_author'] ?? null;
    $app_year = $DataSettingGeneral['app_year'] ?? null;
    $app_company = $DataSettingGeneral['app_company'] ?? null;

    //Ubah keyword menjadi arry
    $app_keyword = json_decode($app_keyword, true);
    $app_keyword_show = strtolower(implode(", ", $app_keyword));

    //Buka Uraian app_company
    $app_company_arry=json_decode($app_company,true);
    $company_name=$app_company_arry['company_name'];
    $company_contact=$app_company_arry['company_contact'];
    $company_email=$app_company_arry['company_email'];
    $company_address=$app_company_arry['company_address'];

    // Tutup statement
    $stmt->close();

    
?>