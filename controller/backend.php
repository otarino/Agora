<?php require('model/backend.php');

function home(){
  $title = 'Agora';
  if(isset($_SESSION['error'])){
    print_r($_SESSION['error']);
  }else{
    $_SESSION['error'] = "";
  }
  require('view/home.php');
}

function connection($array){
  $pseudo = $_GET['pseudo'];
  $pw = $_GET['pw'];
  $infos = selectInfoUser($pseudo);
  if (isset($infos)){
    if ($infos['password'] == $pw){
      $_SESSION['status'] = 'connected';
      $_SESSION['pseudo'] = $pseudo;
    }
  }else {
    $_SESSION['error'] = "Erreur : Compte inconnu.";
  }
  home();
}

function addSubjectC($onlyPrint){
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
    $name = $_GET['name'];

    $rdm = uniqid();
    $address = $rdm.'.txt';
    $info = selectInfoUser($_SESSION['pseudo']);
    $id_user = $info['id'];

    $content = fopen('public/sujet/'.$address, 'w+');
    fwrite($content,$message);
    fclose($content);
    addSubject($name,$id_user,intval($categorie),$address);

    header('Location: index.php?action=home');
    echo 'test';

  }
  require('view/addSubject.php');
}