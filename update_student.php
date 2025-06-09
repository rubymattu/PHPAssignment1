<?php
session_start();
require_once('database.php');
require_once('image_util.php');

// Collect POST data from your form
$studentID = filter_input(INPUT_POST, 'studentID', FILTER_VALIDATE_INT);
$firstName = filter_input(INPUT_POST, 'firstName');
$lastName = filter_input(INPUT_POST, 'lastName');
$emailAddress = filter_input(INPUT_POST, 'emailAddress');
$phoneNumber = filter_input(INPUT_POST, 'phoneNumber');
$status = filter_input(INPUT_POST, 'status');
$dob = filter_input(INPUT_POST, 'dob');
$deptID = filter_input(INPUT_POST, 'deptID', FILTER_VALIDATE_INT);
$image = $_FILES['image'] ?? null;

$image_dir = 'images/';
$image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;

// Get current contact to retrieve old image name
$query = 'SELECT * FROM students WHERE studentID = :studentID';
$statement = $db->prepare($query);
$statement->bindValue(':studentID', $studentID);
$statement->execute();
$student = $statement->fetch();
$statement->closeCursor();

$oldImageName = $student['imageName'];
$imageName = $oldImageName;

// Basic validation
if (
    $studentID === null || $firstName === null || $lastName === null || $emailAddress === null || 
    $phoneNumber === null || $dob === null || $deptID === null
) {
    $_SESSION["add_error"] = "Invalid contact data, Check all fields and try again.";
    header("Location: error.php");
    exit();
}

// Check for duplicate email except current contact
$querystudents = 'SELECT * FROM students WHERE emailAddress = :email AND studentID != :studentID';
$statement1 = $db->prepare($querystudents);
$statement1->bindValue(':email', $emailAddress);
$statement1->bindValue(':studentID', $studentID);
$statement1->execute();
$existingContact = $statement1->fetch();
$statement1->closeCursor();

if ($existingContact) {
    $_SESSION["add_error"] = "Invalid data, Duplicate Email Address. Try again.";
    header("Location: error.php");
    exit();
}

// Get current image name from database
$query = 'SELECT imageName FROM students WHERE studentID = :studentID';
$statement = $db->prepare($query);
$statement->bindValue(':studentID', $studentID);
$statement->execute();
$current = $statement->fetch();
$currentImageName = $current['imageName'] ?? null;
$statement->closeCursor();

$imageName = $currentImageName;  // Default to current image if no new uploaded



// Handle new image upload
if ($image && $image['error'] === UPLOAD_ERR_OK) {
    $filename = basename($image['name']);
    $target = $image_dir_path . $filename;
    move_uploaded_file($image['tmp_name'], $target);

    // Process image
    process_image($image_dir_path, $filename);

    // Generate _100 name
    $dot = strrpos($filename, '.');
    $imageName100 = substr($filename, 0, $dot) . '_100' . substr($filename, $dot);
    $imageName = $imageName100;

    // Delete old images if not placeholder
    if ($oldImageName !== 'placeholder_100.jpg') {
        $base = substr($oldImageName, 0, strrpos($oldImageName, '_100'));
        $ext = substr($oldImageName, strrpos($oldImageName, '.'));
        $filesToDelete = [
            $base . $ext,
            $base . '_100' . $ext,
            $base . '_400' . $ext
        ];

        foreach ($filesToDelete as $file) {
            $path = $image_dir_path . DIRECTORY_SEPARATOR . $file;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}

// Update contact info in DB
$query = 'UPDATE students
    SET firstName = :firstName,
        lastName = :lastName,
        emailAddress = :emailAddress,
        phoneNumber = :phoneNumber,
        status = :status,
        dob = :dob,
        deptID = :deptID,
        imageName = :imageName
    WHERE studentID = :studentID';

$statement = $db->prepare($query);
$statement->bindValue(':firstName', $firstName);
$statement->bindValue(':lastName', $lastName);
$statement->bindValue(':emailAddress', $emailAddress);
$statement->bindValue(':phoneNumber', $phoneNumber);
$statement->bindValue(':status', $status);
$statement->bindValue(':dob', $dob);
$statement->bindValue(':deptID', $deptID);
$statement->bindValue(':imageName', $imageName);
$statement->bindValue(':studentID', $studentID);
$statement->execute();
$statement->closeCursor();

// Save name to session and redirect
$_SESSION["fullName"] = $firstName . " " . $lastName;
header("Location: update_confirmation.php");
exit();
?>