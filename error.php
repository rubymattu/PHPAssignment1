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
  <main>
    <h2>Error!!!</h2>
    <p><?php echo $_SESSION['error'] ?></p>
    <p><a href='add_student_form.php'>Add New Student</p>
    <p><a href='index.php'>View Student list</a></p>
  </main>
  <?php
  include 'footer.php';
  ?>  
</body>
</html>