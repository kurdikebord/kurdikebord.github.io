<?php

if(!isset($_SESSION['username']) || $_SESSION['username'] == ''){
    redirect('../page/login');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title Page-->
    <title><?=$title?></title>

    <!-- Fontfaces CSS-->
    <link href="<?=ASSETS_PATH?>css/font-face.css" rel="stylesheet" media="all">
    <link href="<?=ASSETS_PATH?>css/my_css.css" rel="stylesheet" media="all">
    <link href="<?=ASSETS_PATH?>vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="<?=ASSETS_PATH?>vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
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

    <style type="text/css">/* Chart.js */
        @-webkit-keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}@keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}.chartjs-render-monitor{-webkit-animation:chartjs-render-animation 0.001s;animation:chartjs-render-animation 0.001s;}</style></head>

<body class="animsition" style="animation-duration: 900ms; opacity: 1;">
<div class="page-wrapper">