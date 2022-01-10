<?php
/**
 * Created by PhpStorm.
 * User: Muhammad Shahab
 * Date: 31-Aug-18
 * Time: 2:14 AM
 */
?>

<body class="animsition">
<div class="page-wrapper">
    <div class="page-content--bge5">
        <div class="container">
            <div class="login-wrap">
                <div class="login-content">
                    <div class="login-logo">
                        <a href="#">
                            <img src="<?=ASSETS_PATH?>/images/tajweedquran_64.png" alt="TajweedQuran">
                        </a>
                    </div>
                    <div class="login-form">
                        <form action="" method="post">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input class="au-input au-input--full" type="text" name="fullname" placeholder="Full Name" required>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input class="au-input au-input--full" type="text" name="address" placeholder="Address" required>
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input class="au-input au-input--full" type="email" name="email" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input class="au-input au-input--full" type="password" name="password" placeholder="Password" required>
                            </div>
                            <div class="login-checkbox">
                                <label>
                                    <input type="checkbox" name="aggree" required>Agree the terms and policy
                                </label>
                            </div>
                            <button class="au-btn au-btn--block au-btn--blue m-b-20" type="submit">register</button>

                        </form>
                        <div class="register-link">
                            <p>
                                Already have account?
                                <a href="#">Sign In</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

