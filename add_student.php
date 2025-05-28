<?php
  session_start();
  require('database.php');

  $firstName = filter_input(INPUT_POST, 'firstName');
  $lastName = filter_input(INPUT_POST, 'lastName');
  $dob = filter_input(INPUT_POST, 'dob');
  $emailAddress = filter_input(INPUT_POST, 'emailAddress');
  $phoneNumber = filter_input(INPUT_POST, 'phoneNumber');
  $status = filter_input(INPUT_POST,'status');

  $queryStudents = 'SELECT * from students';
  $statement1 = $db->prepare($queryStudents);
  $statement1->execute();
  $students = $statement1->fetchAll();
  $statement1->closeCursor();

  foreach ($students as $student) {
    if($student['emailAddress']== $emailAddress) {
      $_SESSION['error'] = 'This email address already exists!!';
      header('Location: error.php');
      die();
    }
  }

  if ($firstName == null || $lastName == null || $dob == null || $emailAddress == null || 
      $phoneNumber == null || $status == null) {
    $_SESSION['error'] = 'Fill all the required fields.';
    header('Location: error.php');
    exit();
  } else {
    $data = [':firstName' => $firstName,
              ':lastName' => $lastName,
              ':dob' => $dob,
              ':emailAddress' => $emailAddress,
              ':phoneNumber' => $phoneNumber,
              ':status' => $status];
    $query = 'INSERT into students
                (firstName, lastName, dob, emailAddress, phoneNumber, status)
                VALUES (:firstName, :lastName, :dob, :emailAddress, :phoneNumber, :status)';
    $statement = $db->prepare($query);
    $statement->execute($data);
    $statement->closeCursor();
    
  }

  $_SESSION['fullName'] = $firstName . ' ' . $lastName;
  //redirect to the confirmation page
  header('Location: add_confirmation.php');
?>