<header class='container'>
  <nav class="navbar navbar-expand-sm navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="index.php"><?php echo "$contestTitle";?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav ml-auto">
        <a class="nav-link dropdown-toggle ml-auto" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Signed in as <?php echo $login_name;?></a>
        <div class="dropdown-menu dropdown-menu-right text-left" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="detailEdit.php" data-toggle="tooltip" data-placement="left" title="open your profile"><span class="text-info"><i class="fas fa-user-circle"></i></span></a>
          <a class="dropdown-item" href="https://weblogin.umich.edu/cgi-bin/logout" data-toggle="tooltip" data-placement="left" title="sign-out"><span class="text-danger"><i class="fas fa-sign-out-alt"></i></span></a>
        </div>
      </div>
    </div>
  </nav>
</header>
