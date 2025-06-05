<?php
  require('database.php');
  require_once('image_util.php');

  $studentID = filter_input(INPUT_POST, 'studentID', FILTER_VALIDATE_INT);

  $queryStudents = 'SELECT * FROM students WHERE studentID = :studentID';
  $statement = $db->prepare($queryStudents);
  $statement->bindValue(':studentID', $studentID);
  $statement->execute();
  $student = $statement->fetch();
  $statement->closeCursor();

  // Get contact types for the dropdown
  $queryDept = 'SELECT * FROM studentDept';
  $deptStatement = $db->prepare($queryDept);
  $deptStatement->execute();
  $depts = $deptStatement->fetchAll();
  $deptStatement->closeCursor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Manager - Update Student Info</title>
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
  <?php
    include 'header.php';
  ?>
  <main style="width: 60%;">
    <h2>Update Student Information</h2>
    <form action="update_student.php" method="post" enctype="multipart/form-data">

      <input type="hidden" name="studentID" value="<?php echo $student['studentID']; ?>">

      <label>Photo:</label>
      <input type="file" name="image"><br>

      <label for="firstName">First Name:</label>
      <input type="text" id="firstName" name="firstName" required value="<?php echo $student['firstName']; ?>"><br>
      
      <label for="lastName">Last Name:</label>
      <input type="text" id="lastName" name="lastName" required value="<?php echo $student['lastName']; ?>"><br>
      
      <label>Department:</label>
      <select name="deptID" required>
        <?php foreach ($depts as $dept): ?>
          <option value="<?php echo $dept['deptID']; ?>" <?php if ($dept['deptID'] == $student['deptID']) echo 'selected'; ?>>
            <?php echo htmlspecialchars($dept['deptName']); ?>
          </option>
        <?php endforeach; ?>
      </select><br>
      
      <label for="dob">Date of Birth:</label>
      <input type="date" id="dob" name="dob" required value="<?php echo $student['dob']; ?>"><br>
      
      <label for="emailAddress">Email Address:</label>
      <input type="email" id="emailAddress" name="emailAddress" required value="<?php echo $student['emailAddress']; ?>"><br>
      
      <label for="phoneNumber">Phone Number:</label>
      <input type="tel" id="phoneNumber" name="phoneNumber" value="<?php echo $student['phoneNumber']; ?>"><br>
      
      <label for="status">Status:</label>
      <input type="radio" name="status" value="Enrolled" <?php echo ($student['status'] == 'Enrolled') ? 'checked' : ''; ?>/>Enrolled
      <input type="radio" name="status" value="Pending" <?php echo ($student['status'] == 'Pending') ? 'checked' : ''; ?>/>Pending<br />
      
      <div id="buttons">
        <input type="submit" value="Update Info" id="submit">
      </div>
      </form>
      <p><a href='index.php'>Back to Student List</a></p>
  </main>
  <?php
  include 'footer.php';
  ?>  
</body>
</html>