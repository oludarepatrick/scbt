<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Schooldrive CBT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?= URL?>public/css/bootstrap.css" rel="stylesheet">
    <style>
        body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        }
        #sidebar{

            max-width: 100%;
            margin: 0px auto;
            padding: 5px;
            background: lightblue;
            border-radius: 7px;
            height:450px;
        }
        .slabel{
            font-family:Times New Roman;background-color:darkblue; padding:10px; color:#fff; margin-top:10px;
        }
        input[type="radio"]{
            -ms-transform: scale(1.7);
            -webkit-transform: scale(1.7);
            transform: scale(1.7);
            border: 2px solid blue;
            border-width: 0 3px 3px 0;
            gap:5em

        }
        .divScroll {
            overflow:auto;
            max-height:450px;
        }
        #bb{
            -moz-box-shadow: 0 0 5px #888;
            -webkit-box-shadow: 0 0 5px#888;
            box-shadow: 0 0 5px #000000;
            font-weight:bolder;
            color:brown !important;
            background-color:azure;
        }
    </style>
    <link href="<?= URL?>public/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?= URL?>public/css/pagin/pagination.css" rel="stylesheet" type="text/css" />
	<link href="<?= URL?>public/css/pagin/B_blue.css" rel="stylesheet" type="text/css" />

    
    <script src="<?=URL ?>public/js/jquery.js" type="text/javascript"></script>
    <script src="<?=URL ?>public/js/jquery.form.js" type="text/javascript"></script>




</head>

<body>
<div class="navbar navbar-fixed-top" style="background-color:darkblue1 !important;">
    <div class="navbar-inner1" style="background-color:darkblue !important;">
        <div class="container" style="background-color:darkblue1 !important;">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="brand active" href="#" id="bb" style="color:#fff">Schooldrives CBT</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li><a href="#"><span class="badge" style="color:#fff">Welcome <i class="icon-user icon-white"></i><?php echo $_SESSION['logged_id']['email'];?></span></a></li>
                    <li class="dropdown"> <a class="dropdown-toggle"data-toggle="dropdown" href="javascript:;"><i class="icon-lock icon-white"></i> Logout <b class="caret"></b> </a>
                        <ul class="dropdown-menu">
                        <li><a href="<?= rtrim(URL, '/') ?>/logout"><i class="icon-ban-circle icon-white"></i> Logout</a></li>

                        </ul>
                    </li>
                </ul>
            </div><!--/.nav-collapse -->
            <span style="float:right; color:#fff"><br/><?php echo date('D M Y H:i:s'); ?>
        </div>
    </div>
</div>

