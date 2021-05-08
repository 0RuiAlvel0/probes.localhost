<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>NEMO</title>
    <meta name="author" content="Open Nemo">
    <link rel="stylesheet" href="/assets/css/bootstrap.css" />
    <link rel="stylesheet" href="/assets/css/glyphicon_rotate.css" />
    <script type="text/javascript" src="/assets/js/jquery-3.2.0.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/assets/js/blockui.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
      img.footerlogoimage { width: 8em; };
    </style>

    <?php $script_name = explode("/",$_SERVER['REQUEST_URI']); ?>
    <?php if($script_name[1] == 'dashboard'):?>
      <style>body { padding-top: 50px; }</style>
      <script src="/assets/js/Chart.bundle.min.js"></script>
      <script src="/assets/includes/dashboard.js"></script>
      <!-- <style>body { padding-bottom: 50px; }</style> -->
    <?php endif;?>
    <?php if($script_name[1] == 'probes'):?>
      <style>body { padding-top: 70px; }</style>
      <!-- <style>body { padding-bottom: 70px; }</style> -->
      <script type="text/javascript" src="/assets/includes/probes.js"></script>
    <?php endif;?>
	<?php if($script_name[1] == 'stats'):?>
      <style>body { padding-top: 70px; }</style>
	  <link rel="stylesheet" href="/assets/jqueryui/jquery-ui.min.css" />
	  <script type="text/javascript" src="/assets/jqueryui/jquery-ui.min.js"></script>
	  <script src="/assets/js/Chart.bundle.min.js"></script>
	  <script type="text/javascript" src="/assets/includes/stats.js"></script>
	  <script>$(function() {$("#chart_date").datepicker({dateFormat: "dd-mm-yy"}).datepicker();});</script>
    <?php endif;?>
	<?php if($script_name[1] == 'settings'):?>
      <style>body { padding-top: 50px; }</style>
	  <script type="text/javascript" src="/assets/includes/settings.js"></script>
    <?php endif;?>
  </head>
  <body>
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
		<a class="navbar-brand" href="#">
			<img src="/assets/images/nemologo_small.png"/>
		</a>
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li <?php if($script_name[1] == 'dashboard') echo 'class="active"';?>><a href="/dashboard">Dashboard</a></li>
            <li <?php if($script_name[1] == 'probes') echo 'class="active"';?>><a href="/probes">Probes</a></li>
            <li <?php if($script_name[1] == 'stats') echo 'class="active"';?>><a href="/stats">Statistics</a></li>
			<li <?php if($script_name[1] == 'settings') echo 'class="active"';?>><a href="/settings">Settings</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="/login/logout"><span class="glyphicon glyphicon-log-out"></span></a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
