<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) <year> <copyright holders>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
/**
 * @author Rizart Dokollari & George Skarlatos
 * @since 6/29/14.
 */

require 'app/init.php';
// do not allow access to user that have not logged in.
$general->logged_out_protect();
$page_title = "Log Out";

session_destroy();
?>

<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>

    <title>Logged out</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="">
    <meta name="author" content=""/>
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>app/assets/img/logos/logo.ico">

    <link rel="stylesheet"
          href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,800italic,400,600,800"
          type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>app/assets/css/font-awesome.min.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>app/assets/css/bootstrap.min.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>app/assets/js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.css"
          type="text/css"/>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>app/assets/css/App.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>app/assets/css/Login.css" type="text/css"/>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>app/assets/css/custom.css" type="text/css"/>

</head>

<body>
<div id="login-container">

    <div id="logo">
        <a href="<?php echo BASE_URL; ?>login">
            <img src="<?php echo BASE_URL; ?>app/assets/img/logos/logo-login.png" alt="Logo"/>
        </a>
    </div>


    <!-- /#forgot -->
    <div id="login">
        <h4>You've logged out.</h4>
        <h5></h5>

	    <hr/>
        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary" role="button">Forgot something&#63;</a>

    </div>

    <?php
    if (empty($errors) === false) {
        ?>
        <div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
            <strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
        </div>
    <?php
    }
    ?>
</div>
<!-- /#forgot -->


<script src="<?php echo BASE_URL; ?>app/assets/js/libs/jquery-1.9.1.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/libs/jquery-ui-1.9.2.custom.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/libs/bootstrap.min.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/App.js"></script>

<script src=".<?php echo BASE_URL; ?>app/assets/js/Login.js"></script>
</body>
</html>