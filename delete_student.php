<?php
  require_once('database.php');

  $studentID = filter_input(INPUT_POST, 'studentID', FILTER_VALIDATE_INT);
  echo $studentID;

  if($studentID != false) {
    $query = 'DELETE from students WHERE studentID = :studentID';
    $statement = $db->prepare($query);
    $statement->bindValue( ':studentID', $studentID);
    $statement->execute();
    $statement->closeCursor();
    header('Location: index.php');
  }

  header('Location: index.php');
  exit();
  
?>