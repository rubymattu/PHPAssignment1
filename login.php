<?php
session_start();
date_default_timezone_set('America/Toronto'); 
require_once('database.php');

$user_name = $_POST['user_name'] ?? '';
$password = $_POST['password'] ?? '';

$query = "SELECT * FROM registrations WHERE userName = :userName";
$statement = $db->prepare($query);
$statement->bindValue(':userName', $user_name);
$statement->execute();
$row = $statement->fetch();
$statement->closeCursor();

if ($row) {
    $now = new DateTime();
    $last_failed = !empty($row['last_failed_login']) ? new DateTime($row['last_failed_login']) : null;
    $interval = $last_failed ? $now->getTimestamp() - $last_failed->getTimestamp() : null;

    $failed_attempts = $row['failed_attempts'];
    $lockout_duration = $row['lockout_duration'] ?? 180; // default 3 minutes

    // Check if user is currently locked
    if ($failed_attempts >= 3 && $interval !== null && $interval < $lockout_duration) {
        $remaining = $lockout_duration - $interval;
        $_SESSION['login_error'] = "Account is already locked. Try again in " . ceil($remaining / 60) . " minute(s).";
        header("Location: login_form.php");
        exit;
    }

    if (password_verify($password, $row['password'])) {
        // ✅ Success - reset counters
        $query = "UPDATE registrations 
                  SET failed_attempts = 0, last_failed_login = NULL, lockout_duration = 180 
                  WHERE userName = :userName";
        $statement = $db->prepare($query);
        $statement->bindValue(':userName', $user_name);
        $statement->execute();
        $statement->closeCursor();

        $_SESSION["isLoggedIn"] = true;
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['userName'] = $row['userName'];
        header("Location: index.php");
        exit;
    } else {
        $new_failed_attempts = $failed_attempts + 1;
        $new_lockout = $lockout_duration;

        if ($failed_attempts >= 3 && $interval !== null && $interval >= $lockout_duration) {
            // If lock expired and user fails again, increase duration by 2 minutes
            $new_lockout += 120;
            $new_failed_attempts = 3; // reset attempts at this lock level
        }

        //  Updating the registration table with failed login attempt
        $query = "UPDATE registrations 
                  SET failed_attempts = :failed_attempts, 
                      last_failed_login = NOW(), 
                      lockout_duration = :lockout_duration 
                  WHERE userName = :userName";
        $statement = $db->prepare($query);
        $statement->bindValue(':failed_attempts', $new_failed_attempts);
        $statement->bindValue(':lockout_duration', $new_lockout);
        $statement->bindValue(':userName', $user_name);
        $statement->execute();
        $statement->closeCursor();

        if ($new_failed_attempts >= 3) {
            $_SESSION['login_error'] = "Account locked due to multiple failed attempts. Try again in " . ceil($new_lockout / 60) . " minute(s).";
        } else {
            $_SESSION['login_error'] = "Incorrect password. You have " . (3 - $new_failed_attempts) . " attempt(s) left.";
        }

        header("Location: login_form.php");
        exit;
    }
} else {
    $_SESSION['login_error'] = "User not found.";
    header("Location: login_form.php");
    exit;
}
