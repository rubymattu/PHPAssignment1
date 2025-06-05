<?php
  session_start();
  require('database.php');
  $studentID = filter_input(INPUT_POST, 'studentID', FILTER_VALIDATE_INT);
  $firstName = filter_input(INPUT_POST, 'firstName');
  $lastName = filter_input(INPUT_POST, 'lastName');
  $deptID = filter_input(INPUT_POST, 'deptID', FILTER_VALIDATE_INT);
  $dob = filter_input(INPUT_POST, 'dob');
  $emailAddress = filter_input(INPUT_POST, 'emailAddress');
  $phoneNumber = filter_input(INPUT_POST, 'phoneNumber');
  $status = filter_input(INPUT_POST, 'status');

   foreach ($students as $student) {
    if($student['emailAddress']== $emailAddress) {
      $_SESSION['error'] = 'This email address already exists!!';
      header('Location: error.php');
      die();
    }
  }

  if ($firstName == null || $lastName == null || $dob == null || $deptID == null || $emailAddress == null || 
    $phoneNumber == null || $status == null) {
    $_SESSION['error'] = 'Please fill all fields';
    header('Location: error.php');
    exit();
  } else {
    $query = 'UPDATE students 
              SET firstName = :firstName, lastName = :lastName, deptID = :deptID, dob = :dob, 
              emailAddress = :emailAddress, phoneNumber = :phoneNumber, status = :status
              WHERE studentID = :studentID';
    $data = [':studentID' => $studentID,
             ':firstName' => $firstName,
             ':lastName' => $lastName,
             ':deptID' => $deptID,
             ':dob' => $dob,
             ':emailAddress' => $emailAddress,
             ':phoneNumber' => $phoneNumber,
             ':status' => $status];
    $statement = $db->prepare($query);
    $statement->execute($data);
    $statement->closeCursor();
  }
  $_SESSION['fullName'] = $firstName . ' ' . $lastName;
  header('Location: update_confirmation.php');
  exit();

?>