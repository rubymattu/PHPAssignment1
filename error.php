<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Manager - Error</title>
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
  <?php
    include 'header.php';
  ?>
  <main style="width: 40%;">
    <h2 style="padding-bottom: 5px;">Error!!!</h2>
    <p style="color: #C21807;"><?php echo $_SESSION['error'] ?></p>
    <a href='add_student_form.php'>Add New Student</a><br><br>
    <a href='index.php'>View Student list</a>
  </main>
  <?php
  include 'footer.php';
  ?>  
</body>
</html>