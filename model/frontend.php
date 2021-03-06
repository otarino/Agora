<?php

function addAnswer($address,$idSujet,$idUser){
  $db= dbConnect();
  $query= $db->prepare('INSERT INTO reponse(points,dateM,adresse,sujet,profil) VALUES(1,:dateM,:adresse,:sujet,:profil)');
  $query->execute(array(
    'sujet' => $idSujet,
    'adresse' => $address,
    'dateM' => date('Y-m-d H:i:s'),
    'profil' => $idUser
  ));
}


function getSujet($idSujet){
  $db= dbConnect();
  $query= $db->query('SELECT * FROM sujet where id =:idSujet');
  $query->execute(array(
      'idSujet'=>$idSujet
  ));
  return $query;
}

function addComment($address,$idAns,$idUser){
  $db= dbConnect();
  $query= $db->prepare('INSERT INTO commentaire(points,datecom,adresse,reponse,profil) VALUES(1,:datecom,:adresse,:reponse,:profil)');
  $query->execute(array(
    'reponse'=> $idAns,
    'adresse'=> $address,
    'datecom'=> date('Y-m-d H:i:s'),
    'profil'=> $idUser
  ));
}

function getAnswer($idAns){
  $db= dbConnect();
  $query= $db->query('SELECT * FROM reponse where id =:idAns');
  $query->execute(array(
      'idAns'=>$idAns
  ));
  return $query;
}

// function getComment($idComment){
//   $db= dbConnect();
//   $query= $db->query('SELECT * FROM commentaire where id =:idComment');
//   $query->execute(array(
//       'idComment'=>$idComment
//   ));
//   return $query;
// }

function editAnswer($adresse,$profil){
  $db =dbConnect();
  $query =$db->prepare('UPDATE reponse SET adresse = :adresse WHERE id =:idAnswer');
  $query -> execute(array(
    'idAnswer'=> $idAnswer,
    'adresse'=> $adresse
  ));
}

function deleteAnswer($address,$idAnswer,$idUser){
  $db= dbConnect();
  $query= $db->prepare('DELETE from reponse where id=:idAnswer');
  $query-> execute(array(
    'idAnswer'=> $idAnswer
  ));
}

function deleteComment($address,$idComment,$idUser){
  $db= dbConnect();
  $query= $db->prepare('DELETE from commentaire where id=:idComment');
  $query-> execute(array(
    'idComment'=> $idComment
  ));
}
function addCourrier($address,$idConv){
  $db= dbConnect();
  $query= $db->prepare('INSERT INTO courrier(datecou,adresse,conversation) VALUES(:datecou,:adresse,:conv)');
  $query->execute(array(
    'conv'=> $idConv,
    'adresse'=> $address,
    'datecou'=> date('Y-m-d H:i:s')
  ));
}

function getConv($idConv){
  $db= dbConnect();
  $query= $db->query('SELECT * FROM courrier where id =:idConv');
  $query->execute(array(
      'idConv'=>$idConv
  ));
  return $query;
}

?>