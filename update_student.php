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

if ($image && $image['error'] === UPLOAD_ERR_OK) {
    // Delete old image files if they exist
    $baseDir = 'images/';
    if ($currentImageName) {
        // Assuming the format is name_100.ext
        $dotPos = strrpos($currentImageName, '_100.');
        if ($dotPos !== false) {
            $originalName = substr($currentImageName, 0, $dotPos) . substr($currentImageName, $dotPos + 4);
            $original = $baseDir . $originalName;
            $img100 = $baseDir . $currentImageName;
            $img400 = $baseDir . substr($currentImageName, 0, $dotPos) . '_400' . substr($currentImageName, $dotPos + 4);

            if (file_exists($original)) unlink($original);
            if (file_exists($img100)) unlink($img100);
            if (file_exists($img400)) unlink($img400);
        }
    }

    // Upload and process new image
    $originalFilename = basename($image['name']);
    $uploadPath = $baseDir . $originalFilename;
    move_uploaded_file($image['tmp_name'], $uploadPath);
    process_image($baseDir, $originalFilename);

    // Save new _100 filename for database
    $dotPosition = strrpos($originalFilename, '.');
    $nameWithoutExt = substr($originalFilename, 0, $dotPosition);
    $extension = substr($originalFilename, $dotPosition);
    $imageName = $nameWithoutExt . '_100' . $extension;
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