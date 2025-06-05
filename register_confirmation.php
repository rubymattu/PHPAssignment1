<?php
  session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>  
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Manager - Registration Confirmation</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
  <?php
  // Include the header file
  include 'header.php';
  ?>
  <main id='login'>
    <h2>Registration Confirmation</h2>
    <p>Thank you, <?php echo $_SESSION['userName']; ?> for registering student information.</p>
    <p>You are Logged in may proceed to the student list.</p>
    <p><a href='index.php'>Back to home</a></p>

  </main>
  <?php
  // Include the footer file
  include 'footer.php';
  ?>
</body>
</html>