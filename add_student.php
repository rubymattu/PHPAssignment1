<?php
session_start();

require_once 'image_util.php'; 
require_once 'database.php';

$image_dir = 'images';
$image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;

// Handle image upload or use placeholder
$filename = $_FILES['file1']['name'] ?? '';
if (!empty($filename)) {
    $source = $_FILES['file1']['tmp_name'];
    $target = $image_dir_path . DIRECTORY_SEPARATOR . $filename;
    move_uploaded_file($source, $target);
    process_image($image_dir_path, $filename);

    $i = strrpos($filename, '.');
    $name = substr($filename, 0, $i);
    $ext = substr($filename, $i);
    $image_name_100 = $name . '_100' . $ext;

    $imageName = $image_name_100; // Store resized image name in DB
} else {
    // Use placeholder
    $placeholder = 'placeholder.jpg';
    $placeholder_100 = 'placeholder_100.jpg';
    $placeholder_400 = 'placeholder_400.jpg';

    if (!file_exists($image_dir_path . DIRECTORY_SEPARATOR . $placeholder_100) ||
        !file_exists($image_dir_path . DIRECTORY_SEPARATOR . $placeholder_400)) {
        process_image($image_dir_path, $placeholder);
    }

    $image_name_100 = $placeholder_100;
    $imageName = $placeholder_100; // Store resized placeholder in DB
}

// Get form inputs
$firstName = filter_input(INPUT_POST, 'firstName');
$lastName = filter_input(INPUT_POST, 'lastName');
$deptID = filter_input(INPUT_POST, 'deptID');
$dob = filter_input(INPUT_POST, 'dob');
$emailAddress = filter_input(INPUT_POST, 'emailAddress');
$phoneNumber = filter_input(INPUT_POST, 'phoneNumber');
$status = filter_input(INPUT_POST, 'status');

// Check for duplicate email
$queryStudents = 'SELECT * FROM students';
$statement1 = $db->prepare($queryStudents);
$statement1->execute();
$students = $statement1->fetchAll();
$statement1->closeCursor();

foreach ($students as $student) {
    if ($student['emailAddress'] == $emailAddress) {
        $_SESSION['error'] = 'This email address already exists!!';
        header('Location: error.php');
        die();
    }
}

// Validate inputs
if ($firstName == null || $lastName == null || !$deptID || $dob == null || 
    $emailAddress == null || $phoneNumber == null || $status == null) {
    $_SESSION['error'] = 'Fill all the required fields.';
    header('Location: error.php');
    exit();
}

// Insert into DB
$data = [
    ':imageName' => $imageName,
    ':firstName' => $firstName,
    ':lastName' => $lastName,
    ':deptID' => $deptID,
    ':dob' => $dob,
    ':emailAddress' => $emailAddress,
    ':phoneNumber' => $phoneNumber,
    ':status' => $status
];

$query = 'INSERT INTO students
            (imageName, firstName, lastName, deptID, dob, emailAddress, phoneNumber, status)
          VALUES
            (:imageName, :firstName, :lastName, :deptID, :dob, :emailAddress, :phoneNumber, :status)';

$statement = $db->prepare($query);
$statement->execute($data);
$statement->closeCursor();

// Store name in session and redirect
$_SESSION['fullName'] = $firstName . ' ' . $lastName;
header('Location: add_confirmation.php');
exit();
?>
