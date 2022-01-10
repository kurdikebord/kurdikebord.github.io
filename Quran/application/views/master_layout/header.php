<?php
/**
 * Created by PhpStorm.
 * User: Muhammad Shahab
 * Date: 31-Aug-18
 * Time: 12:55 AM
 */
if( !isset($title) || $title == ''){
    $title = 'Title Here';
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title><?=$title?></title>

    <!-- Fontfaces CSS-->
    <link href="<?=ASSETS_PATH?>vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="<?=ASSETS_PATH?>vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="<?=ASSETS_PATH?>css/font-face.css" rel="stylesheet" media="all">
    <link href="<?=ASSETS_PATH?>vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="<?=ASSETS_PATH?>vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="<?=ASSETS_PATH?>vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="<?=ASSETS_PATH?>vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="<?=ASSETS_PATH?>vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="<?=ASSETS_PATH?>vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="<?=ASSETS_PATH?>vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="<?=ASSETS_PATH?>vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="<?=ASSETS_PATH?>vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="<?=ASSETS_PATH?>css/theme.css" rel="stylesheet" media="all">

</head>
