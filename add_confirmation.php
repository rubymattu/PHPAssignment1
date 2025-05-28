<?php
  session_start();
?>
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
  <title>Student Manager - Confirmation</title>
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
  <?php
    include 'header.php';
  ?>
  <main>
    <h2>Confirmation</h2>
    <p>Thank you, <?php echo $_SESSION['fullName']; ?> for saving your student information.</p>
    <p><a href='index.php'>View Student list</a></p>
  </main>
  <?php
  include 'footer.php';
  ?>  
</body>
</html>