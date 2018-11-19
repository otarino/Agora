<nav class="navbar navbar-expand-lg navbar-light " style="background-color: #6C3483;">
  <img src = 'public/images/agora.png' width = 50px;>&nbsp;
  <a class="navbar-brand" href="index.php?action=home">Accueil</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
       <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Catégories
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="#">PHP</a>
          <a class="dropdown-item" href="#">HTML</a>
          <a class="dropdown-item" href="#">SQL</a>
          <a class="dropdown-item" href="#">JS</a>
        </div>
      </li>&nbsp;
      <form class="form-inline">
        <input class="form-control mr-sm-2" type="search" placeholder="Recherche" aria-label="Search">
        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Rechercher un sujet</button>
       </form>
    </ul>
  </div>
  <?php
  if (isset($_SESSION['status'])) {
    if ($_SESSION['status'] == 'connected'){
      echo "<div class=' row mr-2'><span class='navbar-text'> Bonjour </span>";
      echo '<div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
         <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            '.$_SESSION['pseudo'].'
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="index.php?action=myProfile">Profil</a>
            <a class="dropdown-item" href="index.php?action=deconnection">Se deconnecter</a>
          </div>
        </li></div>';
    }else {
      echo "
      ";
      
    }
  }else {
    echo "
    <form id='formAccueil' method='get' action='./index.php'>
    <div class='form-row'>
      <div class='col' >
        <input type='text' class='form-control' name='pseudo' placeholder='Pseudo'>
        ".$_SESSION['error']."
      </div>
      <div class='col'>
        <input type='password' class='form-control' name='pw' placeholder='Mot de passe'>
      </div>
      <button type='submit' class='btn btn-success' name='action' value='connection'>Se connecter</button>
      <a href ='#'><button type='submit' name='action' value='inscription' class='btn btn-link'>Créer un compte</button></a>
    </div>
  </form>";
  }
  
    
  ?>
</nav>