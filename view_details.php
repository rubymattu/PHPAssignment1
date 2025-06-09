<?php
require_once('database.php');

$studentID = filter_input(INPUT_POST, 'studentID', FILTER_VALIDATE_INT);

if (!$studentID) {
    echo $studentID ? "Invalid student ID." : "student ID is required.";
    exit();
}

$query = '
  SELECT students.*, studentDept.deptName
  FROM students
  JOIN studentDept ON students.deptID = studentDept.deptID
  WHERE students.studentID = :studentID
';
$statement = $db->prepare($query);
$statement->bindValue(':studentID', $studentID);
$statement->execute();
$student = $statement->fetch();
$statement->closeCursor();

if (!$student) {
    echo "Student not found.";
    exit();
}

// Get _400 image version
$imageName = $student['imageName'];
$image_400 = str_replace('_100', '_400', $imageName);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Details</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
<?php include 'header.php'; ?>
<main style="width: 60%;">
  <h2>Student Details</h2>
<div class="student-details-container">
  <div class="student-image">
    <?php
    // Convert _100 image name to _400 version
    $imageName400 = str_replace('_100', '_400', $student['imageName']);
    ?>
    <img src="images/<?php echo $imageName400; ?>" alt="Student Photo">
  </div>
  <div class="student-info">
    <h2><?php echo htmlspecialchars($student['firstName'] . ' ' . $student['lastName']); ?></h2>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($student['emailAddress']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['phoneNumber']); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($student['status']); ?></p>
    <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($student['dob']); ?></p>
    <p><strong>Department:</strong> <?php echo htmlspecialchars($student['deptName']); ?></p>
    <a href="index.php">← Back to student List</a>
  </div>
</div>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
