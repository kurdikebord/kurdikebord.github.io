<!-- HEADER MOBILE-->
<header class="header-mobile d-block d-lg-none">
    <div class="header-mobile__bar">
        <div class="container-fluid">
            <div class="header-mobile-inner">
                <a class="logo" href="index.html">
                    <img src="<?=ASSETS_PATH?>images/tajweedquran_64.png" alt="TajweedQuran">
                </a>
                <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                </button>
            </div>
        </div>
    </div>
    <nav class="navbar-mobile">
        <div class="container-fluid">
            <ul class="navbar-mobile__list list-unstyled">

                <?php

                foreach ($main_menu as $menu_name => $submenu){
                    if(isset($submenu['link'])){
                        echo '<li>
                                <a href="'.$submenu['link'].'">
                                    <i class="fas fa-'.$submenu['icon'].'"></i>'.$menu_name.'</a>
                              </li>';
                    } else {
                        echo '<li class="has-sub">
                                <a class="js-arrow" href="#">
                                    <i class="fas fa-'.$submenu['icon'].'"></i>'.$menu_name.'</a>
                                <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">';

                                foreach ($submenu as $submenu_heading => $submenu_link){
                                    if($submenu_heading != 'icon') {
                                        echo '<li>
                                            <a href="' . $submenu_link . '">' . $submenu_heading . '</a>
                                        </li>';
                                    }
                                }
                        echo '</ul>
                              </li>';
                    }
                }

                ?>

            </ul>
        </div>
    </nav>
</header>

<!-- END HEADER MOBILE-->


<!-- MENU SIDEBAR-->
<aside class="menu-sidebar d-none d-lg-block">
    <div class="logo">
        <a href="#">
            <img src="<?=ASSETS_PATH?>images/tajweedquran_64.png" alt="Tajweed Quran">
        </a>
    </div>
    <div class="menu-sidebar__content js-scrollbar1 ps">
        <nav class="navbar-sidebar">
            <ul class="list-unstyled navbar__list">

                <?php

                foreach ($main_menu as $menu_name => $submenu){
                    if(isset($submenu['link'])){
                        echo '<li>
                                <a href="'.$submenu['link'].'">
                                    <i class="fas fa-'.$submenu['icon'].'"></i>'.$menu_name.'</a>
                              </li>';
                    } else {
                        echo '<li class="has-sub">
                                <a class="js-arrow" href="#">
                                    <i class="fas fa-'.$submenu['icon'].'"></i>'.$menu_name.'</a>
                                <ul class="list-unstyled navbar__sub-list js-sub-list">';

                        foreach ($submenu as $submenu_heading => $submenu_link){
                            if($submenu_heading != 'icon') {
                                echo '<li>
                                            <a href="' . $submenu_link . '">' . $submenu_heading . '</a>
                                        </li>';
                            }
                        }
                        echo '</ul>
                              </li>';
                    }
                }

                ?>

            </ul>
        </nav>
        <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
</aside>
<!-- END MENU SIDEBAR-->

<!-- PAGE CONTAINER-->
<div class="page-container">
    <!-- HEADER DESKTOP-->
    <header class="header-desktop">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="header-wrap">

                    <div class="header-button mg_lft_auto" >

                        <div class="account-wrap mg_lft_auto">
                            <div class="account-item clearfix js-item-menu">
                                <div class="image">
                                    <img src="<?=ASSETS_PATH?>images/profile_pics/<?=$profile_pic?>" alt="<?=$display_name?>">
                                </div>
                                <div class="content">
                                    <a class="js-acc-btn" href="#"><?=$display_name?></a>
                                </div>
                                <div class="account-dropdown js-dropdown">
                                    <div class="info clearfix">
                                        <div class="image">
                                            <a href="#">
                                                <img src="<?=ASSETS_PATH?>images/profile_pics/<?=$profile_pic?>" alt="<?=$display_name?>">
                                            </a>
                                        </div>
                                        <div class="content">
                                            <h5 class="name">
                                                <a href="#"><?=$display_name?></a>
                                            </h5>
                                            <span class="email"><?=$email?></span>
                                        </div>
                                    </div>
                                    <div class="account-dropdown__body">
                                        <div class="account-dropdown__item">
                                            <a href="#">
                                                <i class="zmdi zmdi-account"></i>Account</a>
                                        </div>
                                        <div class="account-dropdown__item">
                                            <a href="#">
                                                <i class="zmdi zmdi-settings"></i>Setting</a>
                                        </div>
                                        <div class="account-dropdown__item">
                                            <a href="#">
                                                <i class="zmdi zmdi-money-box"></i>Billing</a>
                                        </div>
                                    </div>
                                    <div class="account-dropdown__footer">
                                        <a href="logout">
                                            <i class="zmdi zmdi-power"></i>Logout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- HEADER DESKTOP-->

    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">