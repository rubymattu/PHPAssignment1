<?php
  session_start();
  require('database.php');
  $queryStudents = 'SELECT * FROM students';
  $statement1 = $db->prepare($queryStudents);
  $statement1->execute();
  $students = $statement1->fetchAll();
  $statement1->closeCursor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Manager - Add Student</title>
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
  <?php
    include 'header.php';
  ?>
  <main>
    <form action="add_student.php" method="post">
      <h2>Add New Student</h2>
      <label for="firstName">First Name:</label>
      <input type="text" id="firstName" name="firstName" required><br>
      
      <label for="lastName">Last Name:</label>
      <input type="text" id="lastName" name="lastName" required><br>
      
      <label for="dob">Date of Birth:</label>
      <input type="date" id="dob" name="dob" required><br>
      
      <label for="emailAddress">Email Address:</label>
      <input type="email" id="emailAddress" name="emailAddress" required><br>
      
      <label for="phoneNumber">Phone Number:</label>
      <input type="tel" id="phoneNumber" name="phoneNumber"><br>
      
      <label for="status">Status:</label>
      <input type="radio" name="status" value="Enrolled" />Enrolled
      <input type="radio" name="status" value="Pending" />Pending<br />
      
      <div id="buttons">
        <label for="submit">&nbsp;</label>
        <input type="submit" value="Save Contact" id="submit">
      </div>
      </form>
      <p><a href='index.php'>Back to Student List</a></p>
  </main>
  <?php
  include 'footer.php';
  ?>  
</body>
</html>