<?php
  session_start();

  if (!isset($_SESSION['isLoggedIn'])) {
  header('Location: login_form.php');
  die();
}

  require('database.php');
  $queryStudents = 'SELECT students.*, studentDept.deptName
                    FROM students
                    JOIN studentDept ON students.deptID = studentDept.deptID';
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
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
  <div id="overlay"></div>

  <!-- Delete Confirmation Popup -->
  <div id="deletePopup">
    <p id="deleteConfirm">Are you sure you want to delete this student?</p>
    <form id="popupDeleteForm" action="delete_student.php" method="post">
      <input type="hidden" name="studentID" id="popupStudentID" />
      <input type="submit" value="Yes, Delete" id="delete" />
      <button type="button" onclick="closePopup()" id="button">No, Cancel</button>
    </form>
  </div>

  <div id="top">
      <h2>Student List</h2><br>
      <p id="greeting">Welcome <span id="name"><?php echo $_SESSION['userName']; ?> !</span></p>
      <div id="topRight">       
        <a href="logout.php" id="logout">Log Out</a>  
      </div>   
    </div>
  <table>
    <tr>
      <th>Photo</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Department</th>
      <th>Date of Birth</th>
      <th>Email Address</th>
      <th>Phone Number</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($students as $student): ?>
      <tr>
        <td><img src="<?php echo htmlspecialchars('./images/' . $student['imageName']); ?>" alt="<?php echo htmlspecialchars('./images/' . $student['imageName']); ?>" style="width:auto; height:50px;" /></td>
        <td><?php echo $student['firstName']; ?></td>
        <td><?php echo $student['lastName']; ?></td>
        <td><?php echo $student['deptName']; ?></td>
        <td><?php echo $student['dob']; ?></td>
        <td><?php echo $student['emailAddress']; ?></td>
        <td><?php echo $student['phoneNumber']; ?></td>
        <td><?php echo $student['status']; ?></td>
        <td>

          <form action="view_details.php" method="post">
            <input type="hidden" name="studentID" value="<?php echo $student['studentID']; ?>"/>
            <input type="submit" value="View" id="viewButton"/>
          </form>

          <form action="update_student_form.php" method="post">
            <input type="hidden" name="studentID" value="<?php echo $student['studentID']; ?>" />
            <input type="submit" value="Update" />
          </form>
        
          <form class="deleteForm" method="post">
            <input type="hidden" name="studentID" value="<?php echo $student['studentID']; ?>" />
            <input type="submit" class="deleteBtn" value="Delete" id="delete"/>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

  <p><a href="add_student_form.php">Add New Student</a></p>
</main>

<?php include 'footer.php'; ?>

<script>

  const overlay = document.querySelector('#overlay');
  const deletePopup = document.querySelector('#deletePopup');
  const popupStudentID = document.querySelector('#popupStudentID');
  const deleteForm = document.querySelectorAll('.deleteForm');

  function showDeletePopup(event) {
    event.preventDefault();
    const form = event.currentTarget;
    const studentID = form.querySelector('input[name="studentID"]').value;
    popupStudentID.value = studentID;
    overlay.style.display = 'block';
    deletePopup.style.display = 'block';
  }

  document.querySelectorAll('.deleteForm').forEach(form => {
    form.addEventListener('submit', showDeletePopup);
  });

  function closePopup() {
    deletePopup.style.display = 'none';
    overlay.style.display = 'none';
    popupStudentID.value = '';
  }
</script>

</body>
</html>
