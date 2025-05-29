<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Manager - Update Confirmation</title>
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
  <?php
    include 'header.php';
  ?>
  <main style="width: 40%;">
    <h2 style="padding-bottom: 5px;">Update Confirmation</h2>
    <p style="color: #50C878;">Thank you <?php echo $_SESSION['fullName']; ?>!! for updating your student information.</p>
    <a href='index.php'>View Student list</a>
  </main>
  <?php
  include 'footer.php';
  ?>  
</body>
</html>