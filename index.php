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
          <th>First Name</th>
          <th>Last Name</th>
          <th>Date of Birth</th>
          <th>Email Address</th>
          <th>Phone Number</th>
          <th>Status</th> 
          <th>Update</th>
          <th>Delete</th>         
        </tr>
        <?php foreach ($students as $student) : ?>
          <tr>
            <td><?php echo $student['firstName']; ?></td>
            <td><?php echo $student['lastName']; ?></td>
            <td><?php echo $student['dob']; ?></td>
            <td><?php echo $student['emailAddress']; ?></td>
            <td><?php echo $student['phoneNumber']; ?></td>
            <td><?php echo $student['status']; ?></td>
             <td>
              <form action='update_student_form.php' method='post'>
                <input type='hidden' name='contactID' value='<?php echo $student['studentID']; ?>'/>
                <input type='submit' value='Update'/>
              </form>
            </td><!-- Edit button -->
            <td>
              <form action='delete_student.php' method='post'>
                <input type='hidden' name='contactID' value='<?php echo $student['studentID']; ?>'/>
                <input type='submit' value='Delete'/>
              </form>
            </td><!-- Delete button -->
          </tr>
          <?php endforeach; ?>
      </table>
      <p><a href='add_student_form.php'>Add New Student</a></p>
  </main>
  <?php
  include 'footer.php';
  ?>  
</body>
</html>