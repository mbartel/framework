<!DOCTYPE html>
<html lang="en">
  <head>
    <title>{$pagetitle}</title>

    <!-- Mobile support -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Include CSS Style Sheets -->
    {foreach from=$stylesheets item=stylesheet}
      <link rel="stylesheet" href="{$BASE}css/{$stylesheet}">
    {/foreach}


    <!-- Include JavaScript files -->
    {foreach from=$javascripts item=javascript}
      <script src="{$BASE}js/{$javascript}"></script>
    {/foreach}

  </head>
  <tbody>
    <nav class="navbar navbar-default" role="navigation">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-navbar-collapse">
          <span class="sr-only"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{$BASE}">{$pagetitle}</a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-navbar-collapse">
        <ul class="nav navbar-nav">
          <li class="active"><a href="#">Link</a></li>
          <li><a href="#">Link</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="#">Action</a></li>
              <li><a href="#">Another action</a></li>
              <li><a href="#">Something else here</a></li>
              <li class="divider"></li>
              <li><a href="#">Separated link</a></li>
              <li class="divider"></li>
              <li><a href="#">One more separated link</a></li>
            </ul>
          </li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <p class="navbar-text">Signed in as {$user.name}</p>
          <li class="navbar-right"><a href="{$BASE}logout">Logout</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </nav>
  
  <div class="container">
    