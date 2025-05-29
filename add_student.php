<?php
  session_start();
     require_once 'image_util.php'; // the process_image function

    $image_dir = 'images';
    $image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;

    if (isset($_FILES['file1']))
    {
        $filename = $_FILES['file1']['name'];

        if (!empty($filename))
        {
            $source = $_FILES['file1']['tmp_name'];

            $target = $image_dir_path . DIRECTORY_SEPARATOR . $filename;

            move_uploaded_file($source, $target);

            // create the '400' and '100' versions of the image
            process_image($image_dir_path, $filename);
        }
    }

  $imageName = $_FILES['file1']['name'];
  $firstName = filter_input(INPUT_POST, 'firstName');
  $lastName = filter_input(INPUT_POST, 'lastName');
  $dob = filter_input(INPUT_POST, 'dob');
  $emailAddress = filter_input(INPUT_POST, 'emailAddress');
  $phoneNumber = filter_input(INPUT_POST, 'phoneNumber');
  $status = filter_input(INPUT_POST,'status');

  require_once('database.php');

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
    $data = [':imageName' => $imageName,
              ':firstName' => $firstName,
              ':lastName' => $lastName,
              ':dob' => $dob,
              ':emailAddress' => $emailAddress,
              ':phoneNumber' => $phoneNumber,
              ':status' => $status];
    $query = 'INSERT into students
                (imageName, firstName, lastName, dob, emailAddress, phoneNumber, status)
                VALUES (:imageName, :firstName, :lastName, :dob, :emailAddress, :phoneNumber, :status)';
    $statement = $db->prepare($query);
    $statement->execute($data);
    $statement->closeCursor();
    
  }
  $_SESSION['fullName'] = $firstName . ' ' . $lastName;
  //redirect to the confirmation page
  header('Location: add_confirmation.php');
?>