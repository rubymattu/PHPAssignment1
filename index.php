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
  <title>Student Manager - Home</title>
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
  <?php
    include 'header.php';
  ?>
  <main>
    <h2>Student List</h2>
      <table>
        <tr>
          <th>Student ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Date of Birth</th>
          <th>Email Address</th>
          <th>Phone Number</th>
          <th>Status</th>          
        </tr>
        <?php foreach ($students as $student) : ?>
          <tr>
            <td><?php echo $student['studentID']; ?></td>
            <td><?php echo $student['firstName']; ?></td>
            <td><?php echo $student['lastName']; ?></td>
            <td><?php echo $student['dob']; ?></td>
            <td><?php echo $student['emailAddress']; ?></td>
            <td><?php echo $student['phoneNumber']; ?></td>
            <td><?php echo $student['status']; ?></td>
          </tr>
          <?php endforeach; ?>
      </table>
  </main>
  <?php
  include 'footer.php';
  ?>  
</body>
</html>