<header class='container'>
  <nav class="navbar navbar-expand-sm navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="index.php"><?php echo "$contestTitle";?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav ml-auto">

        <a class="nav-link dropdown-toggle ml-auto" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Signed in as: <span style='color:dodgerblue;'><?php echo $login_name;?></span></a>
        <div class="dropdown-menu dropdown-menu-right text-center" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="detailEdit.php" data-toggle="tooltip" data-placement="left" title="open your profile"><span class="text-info"><i class="fas fa-user-circle fa-2x"></i></span></a>
          <a class="dropdown-item" href="https://weblogin.umich.edu/cgi-bin/logout" data-toggle="tooltip" data-placement="left" title="sign-out"><span class="text-danger"><i class="fas fa-sign-out-alt fa-2x"></i></span></a>
        </div>
        <a class="nav-item nav-link active ml-auto bg-primary text-white rounded" href="mailComment.php" data-toggle="tooltip" data-placement="left" title="send us your questions or comments"><i class="fas fa-question fa-lg"></i></a>
      </div>
    </div>
  </nav>
</header>
