<?php

function config_sidebar()
{
    $session = $_SESSION['data'];
    $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri_segments = explode('/', $uri_path);
    $data = array(
        array(
            'title-group' => '',
            'title' => 'Dashboard',
            'icon' => 'fas fa-chart-bar',
            'link' => 'Dashboard', //Jika tidak menggunakan submenu Isi dengan Link , Jika memakai submenu isi dengan #
            'sub_menu' => '', // Jika tidak ada sub menu dikosongkan saja  , Jika pakai submenu isi dengan function 
            'id_collapse' => '',
            'condition' =>  $uri_segments[2] == "Dashboard"  ? 'true' : 'false'
        ),
        array(
            'title-group' => 'Admin',
            'title' => 'Admin',
            'icon' => 'fas fa-users-cog',
            'link' => '#admin', //Jika tidak menggunakan submenu Isi dengan Link , Jika memakai submenu isi dengan #
            'sub_menu' => admin(), // Jika tidak ada sub menu dikosongkan saja  , Jika pakai submenu isi dengan function 
            'id_collapse' => 'admin', // Jika Memakai submenu isi samakan dengan link tanpa #
            'condition' =>  $uri_segments[2] == "User" || $uri_segments[2] == "Karyawan"  ? 'true' : 'false'
        ),
        array(
            'title-group' => 'Marketing',
            'title' => 'Marketing',
            'icon' => 'fas fa-file-signature',
            'link' => '#marketing', //Jika tidak menggunakan submenu Isi dengan Link , Jika memakai submenu isi dengan #
            'sub_menu' => marketing(), // Jika tidak ada sub menu dikosongkan saja  , Jika pakai submenu isi dengan function 
            'id_collapse' => 'marketing', // Jika Memakai submenu isi samakan dengan link tanpa #
            'condition' =>   $uri_segments[2] == "Customer" || $uri_segments[2] == "Suplier" || $uri_segments[2] == "Penjualan"  ? 'true' : 'false'
        ),
        array(
            'title-group' => 'Product',
            'title' => 'Product',
            'icon' => 'fas fa-clipboard-list',
            'link' => '#product', //Jika tidak menggunakan submenu Isi dengan Link , Jika memakai submenu isi dengan #
            'sub_menu' => product(), // Jika tidak ada sub menu dikosongkan saja  , Jika pakai submenu isi dengan function 
            'id_collapse' => 'product', // Jika Memakai submenu isi samakan dengan link tanpa #
            'condition' =>    $uri_segments[2] == "Product" || $uri_segments[2] == "Stock"  ? 'true' : 'false'
        ),
    );
    if ($session['divisi'] == 1) {
        unset($data[1]);
    } elseif ($session['divisi'] == 3) {
        unset($data[0]);
        unset($data[1]);
        unset($data[2]);
    }
    return $data;
}

function admin()
{
    $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri_segments = explode('/', $uri_path);
    $data = array(
        array(
            'title'    => 'Data Karyawan',
            'link'     => 'Karyawan',
            'condition' =>  $uri_segments[2] == "Karyawan"  ? 'true' : 'false'
        ),
        array(
            'title'    => 'Data User',
            'link'     => 'User',
            'condition' =>  $uri_segments[2] == "User"  ? 'true' : 'false'
        ),
        array(
            'title'    => 'Data Dpartement',
            'link'     => 'Dpartement',
            'condition' =>  $uri_segments[2] == "Dpartement"  ? 'true' : 'false'
        ),
    );
    return $data;
}

function marketing()
{
    $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri_segments = explode('/', $uri_path);
    $data = array(
        array(
            'title'    => 'Data Perusahaan',
            'link'     => 'Customer',
            'condition' =>   $uri_segments[2] == "Customer" || $uri_segments[2] == "Suplier"  ? 'true' : 'false'
        ),
        array(
            'title'    => 'Data Penjualan',
            'link'     => 'Penjualan',
            'condition' =>   $uri_segments[2] == "Penjualan"  ? 'true' : 'false'
        ),
    );
    return $data;
}
function product()
{
    $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri_segments = explode('/', $uri_path);
    $data = array(
        array(
            'title'    => 'Data Product',
            'link'     => 'Product',
            'condition' =>   $uri_segments[2] == "Product" ? 'true' : 'false'
        ),
        array(
            'title'    => 'Data Stock Product',
            'link'     => 'Stock',
            'condition' =>   $uri_segments[2] == "Stock" ? 'true' : 'false'
        ),
    );
    return $data;
}
