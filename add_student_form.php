<?php
  session_start();
  require('database.php');

  $queryDept = 'SELECT * FROM studentDept';
  $statement = $db->prepare($queryDept);
  $statement->execute();
  $depts = $statement->fetchAll();
  $statement->closeCursor();
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
  <main style="width: 60%;">
    <h2>Add New Student</h2>
    <form action="add_student.php" method="post" enctype="multipart/form-data">

      <label for="image">Upload Image:</label>
      <input type="file" name="file1"><br>

      <label for="firstName">First Name:</label>
      <input type="text" id="firstName" name="firstName" required><br>
      
      <label for="lastName">Last Name:</label>
      <input type="text" id="lastName" name="lastName" required><br>

      <label for="deptID">Department:</label>
        <select name="deptID" required>
          <option value="">--Select Department--</option>
          <?php foreach ($depts as $dept): ?>
            <option value="<?php echo $dept['deptID']; ?>">
              <?php echo htmlspecialchars($dept['deptName']); ?>
            </option>
          <?php endforeach; ?>
        </select><br>
      
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
        <input type="submit" value="Add Student" id="submit">
      </div>
      </form>
      <p><a href='index.php'>Back to Student List</a></p>
  </main>
  <?php
  include 'footer.php';
  ?>  
</body>
</html>