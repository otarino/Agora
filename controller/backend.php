<?php
require('model/backend.php');

function home(){
  $title = 'Agora';
  $categories = getCategories();
  $count = 0;
  $count2 = 0;
  $count3 = 0;
  $lastSubjects = printLastSubjects();

  while($data3 = $lastSubjects->fetch()){
      $idLastSujet[$count3][0] = $data3['idSujet'];
      $lastNomSujet[$count3][0] = $data3['nom'];
      $lastDateSujet[$count3][0] = getFrenchDate($data3['dateS']);
      $lastCategorie[$count3]= $data3['nom_categorie'];
      $idLastProfil[$count3][0] = $data3['profil'];
      $lastPseudo[$count3][0] = $data3['pseudo'];
      $count3++;
  }
  while($data2 = $categories->fetch()){
    $tab_categories[$count2] = $data2['nom'];
    $subjects = printSubjectbycategories($data2['id']);
    $count = 0;
    while($data = $subjects->fetch()){

      $nomSujet[$count2][$count] = $data['nom'];
      $idSujet[$count2][$count] = $data['idSujet'];
      $contenu_date[$count2][$count] = getFrenchDate($data['dateS']);
      $idProfil[$count2][$count] = $data['profil'];
      $nomCategorie[$count2][$count] = $data['nom_categorie'];
      $pseudo[$count2][$count] = $data['pseudo'];
      $count++;
    }
    $count2++;
  }
  require('view/home.php');
}

function connection($array){
  $pseudo = $_GET['pseudo'];
  $pw = $_GET['pw'];
  $isRegister = isRegister($pseudo,$pw)->fetch();
    if($isRegister){
      $_SESSION['status'] = 'connected';
      $_SESSION['statut'] = $isRegister['statut'];
      $_SESSION['pseudo'] = $pseudo;
      $_SESSION['error'] = "";
  }else{
    $_SESSION['error'] = "Erreur : Pseudo ou mot de passe inconnu !";

  }
  header('Location: index.php');
}

function addSubjectC($onlyPrint){
  if(isConnect()){
    $title = 'Créer mon sujet';
    $categories = getCategories();
    $count = 0;
    while($data = $categories->fetch()){
      $listCategories[$count] = $data['nom'];
      $id[$count] = $data['id'];
      $count++;
    }
    if(!$onlyPrint){
      $categorie = $_GET['categorie'];
      $message = $_GET['message'];
      $name = htmlspecialchars($_GET['name']);
      $rdm = uniqid();
      $address = $rdm.'.txt';
      $info = selectInfoUser($_SESSION['pseudo']);
      $id_user = $info['id'];
      $content = fopen('public/sujet/'.$address, 'w+');
      fwrite($content,$message);
      fclose($content);
      addSubject($name,$id_user,intval($categorie),$address);
      header('Location: index.php?action=home');
    }
    require('view/addSubject.php');
  }else{
    $_SESSION['error'] = "";
    header('Location: index.php');
  }

}

function deleteSubject() {
  if(isConnect()){
    $subjectId = $_GET['id'];
    delsubject($subjectId);
    header('location: index.php?action=home');
  }
}

function deleteAnswerC() {
  if(isConnect()){
    $responseId = $_GET['id'];
    $idSujet = $_GET['idSujet'];
    delCommentary($responseId);
    delresponse($responseId);
  header('location: index.php?action=printSubject&id='.$idSujet);
  }
}

function deletecommentC(){
  if(isConnect()){
    $commentId = $_GET['id'];
    $idSujet = $_GET['idSujet'];
    delCommentarywithID($commentId);
    header('location: index.php?action=printSubject&id=' . $idSujet);
  }
}


function printSubjectC($id){

  $subjectInfo = printSubject($id);
  $data = $subjectInfo->fetch();
  $infoConnected = selectInfoUser($_SESSION['pseudo']);
  $idCreator = $data['idProfil'];
  $nomSujet = $data['nomSujet'];
  $pseudoCreator = $data['pseudo'];
  $scoreProfilCreator = $data['scoreProfil'];
  $dateInscriptionCreator = getFrenchDate($data['dateInscription']);
  $idSujet = $data['idSujet'];

  if($infoConnected['statut'] == "admin") {
    $optionsAdminSujet = '&nbsp;&nbsp;&nbsp;<a href ="index.php?action=deleteSubject&id='. $idSujet . '">Supprimer ce sujet</a>';
  }else if($infoConnected['id'] == $idCreator) {
    $optionsCreatorSujet = '&nbsp;&nbsp;&nbsp;<a href ="index.php?action=modifSubject&id='.$idSujet. '">Modifier mon sujet</a>&nbsp;&nbsp;&nbsp;<a href ="index.php?action=deleteSubject&id='.$idSujet.'">Supprimer mon sujet</a>';
  }

  $dateCreationSujet = getFrenchDate($data['dateCreationSujet']);
  $statutSujet = $data['statutSujet'];
  $categorieSujet = $data['statutSujet'];
  $nomCategorie = $data['nomCategorie'];
  $avatar = 'public/images/avatar/'.$data['avatar'];
  if(!file_exists($avatar) || $data['avatar'] == ""){
    $avatar = 'public/images/avatar/default.png';
  }
  $data = fopen('public/sujet/'.$data['adresseSujet'],'r');
  $content = "";
  while(false !== ($line = fgets($data))){
    $content .= htmlspecialchars($line);
  }
  if($infoConnected['statut'] == "admin"){
    $printCloseSubject = '<a href = "./index.php?action=closeSubject&id='.$idSujet.'">Fermer un sujet</a>';
  }else{
    $printCloseSubject = '';
  }

  if(getReponse($id)){
    $reponses = getReponse($id);
    $count = 0;
    while($data2 = $reponses->fetch()){
      $idProfilReponse[$count] = $data2['idProfilReponse'];
      $avatarProfil[$count] = getAvatarPath($data2['pseudoProfil']);
      if(!file_exists($avatarProfil[$count])){
        $avatarProfil[$count] = 'public/images/avatar/default.png';
      }
      $pseudoProfil[$count] = $data2['pseudoProfil'];
      $idReponse[$count] = $data2['idReponse'];
      if($infoConnected['statut'] == "admin") {
        $optionsAdminReponse[$count] = '&nbsp;&nbsp;&nbsp;<a href ="index.php?action=deleteAnswer&id='.$idReponse[$count]. '&idSujet='. $idSujet .'">Supprimer cette réponse</a>';
      } else if ($infoConnected['id'] == $idProfilReponse[$count]) {
        $optionsCreatorReponse[$count] = '&nbsp;&nbsp;&nbsp;<a href ="index.php?action=modifAnswer&id='.$idReponse[$count].'">Modifier ma réponse</a>&nbsp;&nbsp;&nbsp;<a href ="index.php?action=deleteAnswer&id='.$idReponse[$count]. '&idSujet=' . $idSujet . '">Supprimer ma réponse</a>';
      }

      $pointsProfil[$count] = $data2['profilPoints'];
      $dateReponse[$count] = getFrenchDate($data2['dateReponse']);
      $dateInscription[$count] = getFrenchDate($data2['dateInscription']);
      $dataReponse[$count] = fopen('public/reponse/'.$data2['adresseReponse'],'r');
      while(false !== ($line = fgets($dataReponse[$count]))){
        $contentReponse[$count] .= htmlspecialchars($line);
      }
      $count2 = 0;
      if(getComment($idReponse[$count])){
        $comments = getComment($idReponse[$count]);
        while($data3 = $comments->fetch()){
          $idComment[$count][$count2] = $data3['idCommentaire'];
          $pseudoProfilComment[$count][$count2] = $data3['pseudoCommentaire'];
          $idReponseComment[$count][$count2] = $data3['idCommentaire'];
          $pointsProfilComment[$count][$count2] = $data3['profilPointsCommentaire'];
          $idProfilComment[$count][$count2] = $data3['idProfil'];
          $dateReponseComment[$count][$count2] = getFrenchDate($data3['dateCommentaire']);
          $dateInscriptionComment[$count][$count2] = getFrenchDate($data3['dateInscriptionCommentaire']);
          $dataReponseComment[$count][$count2] = fopen('public/comment/'.$data3['adresseCommentaire'],'r');

          if ($infoConnected['statut'] == "admin") {
            $optionsAdminComment[$count][$count2] = '&nbsp;&nbsp;&nbsp;<a href ="index.php?action=deleteComment&id=' . $idComment[$count][$count2] . '&idSujet=' . $idSujet . '">Supprimer ce commentaire</a>';
          } else if ($infoConnected['id'] == $idProfilComment[$count][$count2]) {
            $optionsCreatorComment[$count][$count2] = '&nbsp;&nbsp;&nbsp;<a href ="index.php?action=modifAnswer&id=' . $idComment[$count][$count2] . '">Modifier ma réponse</a>&nbsp;&nbsp;&nbsp;<a href ="index.php?action=deleteComment&id=' . $idComment[$count][$count2] . '&idSujet=' . $idSujet . '">Supprimer mon commentaire</a>';
          }

          while(false !== ($lineC = fgets($dataReponseComment[$count][$count2]))){
            $contentComment[$count][$count2] .= htmlspecialchars($lineC);
          }
          $count2++;
        }
      }
    $count++;
    }
  }



  require('view/printSubject.php');
}

function getMonth($integer){
    switch($integer){
      case 1 :
        return 'Janvier';
      break;
      case 2 :
        return 'Février';
      break;
      case 3 :
        return 'Mars';
      break;
      case 4 :
        return 'Avril';
      break;
      case 5 :
        return 'Mai';
      break;
      case 6 :
        return 'Juin';
      break;
      case 7 :
        return 'Juillet';
      break;
      case 8 :
        return 'Aôut';
      break;
      case 9 :
        return 'Septembre';
      break;
      case 10 :
        return 'Octobre';
      break;
      case 11 :
        return 'Novembre';
      break;
      case 12 :
        return 'Décembre';
      break;
    }
  }

function getFrenchDate($dateFormatSQL) {

  if(strlen($dateFormatSQL) <= 10){
    $date = explode('-',$dateFormatSQL);
    $date= $date[2].' '.getMonth($date[1]).' '.$date[0];
    $result = $date;
  }else{
    $dateAndHeure = explode(' ',$dateFormatSQL);
    $date = $dateAndHeure[0];
    $date = explode('-',$date);
    $date = $date[2]." ".getMonth($date[1])." ".$date[0];
    $heure = $dateAndHeure[1];
    $result = $date.' à '.$heure;
  }
  return $result;
}

function isConnect(){
  if(isset($_SESSION['status'])){
    return true;
  }else{
    home();
  }
}

function isAdmin(){
  if ($_SESSION['statut'] == "admin") {
    return true;
  } else {
    home();
  }
}

function isPseudoValid($text){
  $pattern = "#^[a-z0-9]+$#i";
  if (preg_match($pattern, $text)) {
    return true;
  } else {
    return false;
  }
}
//Disconnect the user
function deconnection(){
  session_destroy();
  $_SESSION['error'] = "";
  header('Location: index.php');
}
//Check if the user is registering and register him in the database or asking for the register form
function register(){
  $title = "Inscription";

  if (isset($_GET['pseudo'])){
    foreach ($_GET as $key => $value){
      $value = htmlspecialchars($value);
    }



    if($_GET['pseudo'] != ""){
      $test = selectInfoUser($_GET['pseudo']);
      if (!$test){
        if (isset($_GET['pw']) && isset($_GET['pwV']) && $_GET['pwV'] == $_GET['pw'] && $_GET['pw'] != "" ){
          addUser($_GET['pseudo'],$_GET['pw']);
          $_SESSION['error'] = "";
          connection($_GET);
        }else{
          $_SESSION['error'] = "Erreur : les mots de passe ne correspondent pas !";
          header('Location: index.php?action=register');
        }
      }else{
        $_SESSION['error'] = "Erreur : Compte déjà existant !";
        header('Location: ./index.php?action=register');
      }
    }else {
      $_SESSION['error'] = "Erreur : Un des champs est mal rempli.";
      header('Location: ./index.php?action=register');
    }
  }else{
    require('view/template/navbar.php');
    require('view/template/top.php');
    require('view/formRegister.php');
    require('view/template/bottom.php');
  }
}
//Display the profile page of a given user via $_GET['pseudo'] as a string
function displayProfile(){
  if(isConnect()){
    if (!isset($_GET['pseudo'])){
      $perso_data_arr = selectInfoUser($_SESSION['pseudo']);
      $delete = "<a href='./index.php?action=deleteMyProfile'>Supprimer mon compte</a>";
    }else{
      $perso_data_arr = selectInfoUser($_GET['pseudo']);
      if (isConnect() && $_GET['pseudo'] == $_SESSION['pseudo']){
        $delete = "<a href='./index.php?action=deleteMyProfile'>Supprimer mon compte</a>";
      }
    }
    $title = 'Profil';
    unset($perso_data_arr['avatar'],$perso_data_arr['id'], $perso_data_arr['statut'], $perso_data_arr['password'], $perso_data_arr['datep'], $perso_data_arr['pseudo'], $perso_data_arr['score']);
    require('./view/profilePage.php');
  }
}
//Return avatar path as a string for a given $pseudo as a string
function getAvatarPath($pseudo){
  $path ="./public/images/avatar/";
  if (isset($_GET['pseudo'])){
    $infoUser = selectInfoUser($_GET['pseudo']);
  }elseif (isset($pseudo) && $pseudo != "") {
    $infoUser = selectInfoUser($pseudo);
  }else{
    $infoUser = selectInfoUser($_SESSION['pseudo']);
  }

  if (isset($infoUser['avatar'])){
    $name = $infoUser['avatar'];
  }else{
    $name = 'default.png';
  }
  $path .= $name;

  return $path;
}
function deleteMyProfile(){
  if(isConnect()){
    $profile = $_SESSION['pseudo'];
    deleteTuppleUser($profile);
    deconnection();
    header('Location: ./index.php');
  }
}
function displayCategory(){
  $cat = $_GET['cat'];
  $title = strtoupper($cat);
  $subjects = getSubjectsByCategory($cat);
  require('./view/subjectsFromCategories.php');

}
function displayAdminPage(){
  if(isAdmin()){
    $title = "Administration";
    if (isset($_GET['admin'])) {
      require('./view/template/top.php');
      require('./view/template/navbar.php');
      if ($_GET['admin'] == "addAdmin") {
        require('./view/formAddAdmin.php');

      }
    }else {
      require('./view/adminPage.php');
    }
  }
}

function isSubjectExistC(){
  $id = $_GET['id'];
  $isSubjectexists = isSubjectExist($id)->fetch();
  if($isSubjectexists){
    return true;
  }else{
    home();
  }
}

function addAdminC(){
  if (isset($_GET['pseudo'])) {
    foreach ($_GET as $key => $value) {
      $value = htmlspecialchars($value);
    }
    if ($_GET['pseudo'] != "") {
      $test = selectInfoUser($_GET['pseudo']);
      if (!$test) {
        if (isset($_GET['pw']) && isset($_GET['pwV']) && $_GET['pwV'] == $_GET['pw'] && $_GET['pw'] != "") {
          addAdmin($_GET['pseudo'], $_GET['pw']);
          $_SESSION['error'] = "";
          header("Location: index.php?action=administation");
        } else {
          $_SESSION['error'] = "Erreur : les mots de passe ne correspondent pas !";
          header('Location: index.php?action=administation&admin=addAdmin');
        }
      } else {
        $_SESSION['error'] = "Erreur : Compte déjà existant !";
        header('Location: action=administation&admin=addAdmin');
      }
    } else {
      $_SESSION['error'] = "Erreur : Un des champs est mal rempli.";
      header('Location: action=administation&admin=addAdmin');
    }
  } else {
    require('view/template/navbar.php');
    require('view/template/top.php');
    require('view/formaddAdmin.php');
    require('view/template/bottom.php');
  }
}

function closeSubjectC(){
  if(isConnect() && isAdmin()){
    closeSubject($_GET['id']);
    header('Location: index.php');
  }
}
