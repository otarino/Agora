<?php  
  require('template/top.php'); 
  require('template/navbar.php');
  ?>

<div id="form-main">
  <div id="form-div">
  <h1 class="h3 mb-3 font-weight-normal"> Créer un commentaire </h1>
    <form class="form" id="form1" action = "index.php" method = "GET">
      <input type = "hidden" name = "action" value = "addComment">

      </p>

      <p class="text">
        <textarea name="message" class="validate[required,length[6,300]] feedback-input" id="message" placeholder="Ecrivez votre commentaire"></textarea>
      </p>
 

      <div class="submit">
        <input type="submit" value="Créer" id="button-blue"/>
        <div class="ease"></div>

        

      </div>
      <input type ="hidden" name="idAns" value=1>

    </form>
  </div>


<?php require('template/bottom.php'); ?>