<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>.:CBT:.</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?=URL ?>public/css/bootstrap.css" rel="stylesheet">
    <style>
        body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        }
        #sidebar{

            width: 70%;
            margin: 0px auto;
            padding: 35px;
            background: #F0F0F0;
            border-radius: 7px;
        }
    </style>
    <link href="<?=URL ?>public/css/bootstrap-responsive.css" rel="stylesheet">


</head>
<!--end header-->
<body>
<!--menus-->
<div class="navbar navbar-success navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="brand" href="<?= URL ?>home/index">Schooldrive CBT</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li class="<?php if($p=='home'){ echo 'active'; } ?>"><a href="index.php">Student</a></li>
                    <li class="<?php if($p=='abount'){ echo 'active'; } ?>"><a href="#about">About</a></li>
                    <li class="<?php if($p=='contact'){ echo 'active'; } ?>"><a href="#contact">Contact</a></li>
                    <li class="<?php if($p=='admin'){ echo 'active'; } ?>"><a href="adminLogin.php">admin</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>
<!--menu-->

<div class="container">

    <div class="container-fluid">
         <div class="row-fluid">
            <div class="span7">
                <!--Sidebar content-->
                <h1>Schooldrive CBT</h1>
                <img src="<?=URL ?>public/img/paathshala.jpg" width="129" height="100">
                <img src="<?=URL ?>public/img/HLPBUTT2.JPG" width="50" height="50">
                <img src="<?=URL ?>public/img/BOOKPG.JPG" width="43" height="43">

                <p>Welcome to Online exam. This Site will provide the quiz for various subject of interest. You need to login for the take the online exam.</p>

            </div>
            <div class="span5">
                <!--Body content-->
                <div   id="sidebar">
                    <?php echo date("l jS \of F Y h:i:s A"); require_once("slogin.php"); ?>
                </div>
         </div>
    </div>

</div> <!-- /container -->

<!--footer-->
<div style="text-align:center; color:green; font-weight: bold; padding-top:50px">
    &copy; <?php echo date('Y'); ?> @ Schooldrive CBT. Allrights Reserved.&nbsp;
    <span style="color: black">powered by:</span>
    <a href="#" style="text-decoration: underline; color:black">Drive Technology</a>

</div>
<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?=URL ?>public/js/jquery.js"></script>
<script src="<?=URL ?>public/js/bootstrap-transition.js"></script>
<script src="<?=URL ?>public/js/bootstrap-alert.js"></script>
<script src="<?=URL ?>public/js/bootstrap-modal.js"></script>
<script src="<?=URL ?>public/js/bootstrap-dropdown.js"></script>
<script src="<?=URL ?>public/js/bootstrap-scrollspy.js"></script>
<script src="<?=URL ?>public/js/bootstrap-tab.js"></script>
<script src="<?=URL ?>public/js/bootstrap-tooltip.js"></script>
<script src="<?=URL ?>public/js/bootstrap-popover.js"></script>
<script src="<?=URL ?>public/js/bootstrap-button.js"></script>
<script src="<?=URL ?>public/js/bootstrap-collapse.js"></script>
<script src="<?=URL ?>public/js/bootstrap-carousel.js"></script>
<script src="<?=URL ?>public/js/bootstrap-typeahead.js"></script>

</body>
</html>
