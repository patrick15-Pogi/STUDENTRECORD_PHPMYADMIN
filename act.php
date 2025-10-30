<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$user = "root";
$pass = "";
$db   = "student_grades";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $first = floatval($_POST['first']);
    $second = floatval($_POST['second']);
    $third = floatval($_POST['third']);
    $fourth = floatval($_POST['fourth']);

    $first_sem = ($first + $second) / 2;
    $second_sem = ($third + $fourth) / 2;
    $final_avg = ($first_sem + $second_sem) / 2;

    if ($final_avg < 75) $remarks = "5.0";
    elseif ($final_avg < 78) $remarks = "3.0";
    elseif ($final_avg < 81) $remarks = "2.75";
    elseif ($final_avg < 84) $remarks = "2.5";
    elseif ($final_avg < 87) $remarks = "2.25";
    elseif ($final_avg < 90) $remarks = "2.0";
    elseif ($final_avg < 93) $remarks = "1.75";
    elseif ($final_avg < 96) $remarks = "1.5";
    elseif ($final_avg < 99) $remarks = "1.25";
    else $remarks = "1.0";

    $insert_sql = "INSERT INTO students
      (id, lastname, firstname, middlename, course, section,
       first_grading, second_grading, third_grading, fourth_grading,
       first_sem_avg, second_sem_avg, final_avg, remarks)
      VALUES
      ('$id','$lastname','$firstname','$middlename','$course','$section',
       $first, $second, $third, $fourth, $first_sem, $second_sem, $final_avg, '$remarks')";
    mysqli_query($conn, $insert_sql);
}

if (isset($_GET['delete'])) {
    $del_id = mysqli_real_escape_string($conn, $_GET['delete']);
    $del_sql = "DELETE FROM students WHERE id = '$del_id'";
    mysqli_query($conn, $del_sql);
}

$select_sql = "SELECT * FROM students ORDER BY lastname ASC";
$result = mysqli_query($conn, $select_sql);
?>




<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Student Grading System</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 20px;
      text-align: center; 
    }

    .container {
      display: inline-block; 
      text-align: left; 
      border: 2px solid black;
      padding: 20px;
      border-radius: 10px;
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }

    h2 {
      text-align: center;
      color: black;
    }

    form {
      border: 2px solid black;
      padding: 18px;
      width: 420px;
      margin: 0 auto; 
      border-radius: 8px;
    }

    label {
      display: block;
      margin-top: 8px;
      font-weight: bold;
    }

    input[type="text"], input[type="number"] {
      width: 100%;
      padding: 6px;
      margin-top: 4px;
    }

    .submit-btn {
      background: black;
      color: white;
      border: none;
      padding: 10px;
      margin-top: 10px;
      width: 100%;
      cursor: pointer;
    }

    h3 {
      color: black;
      text-align: center;
      margin-top: 30px;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      margin-top: 20px;
    }

    th, td {
      border: 2px solid black;
      padding: 8px;
      text-align: center;
    }

    th {
      background: black;
      color: white;
    }

    .delete-btn {
      background: red;
      color: white;
      padding: 6px 8px;
      border: none;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Student Grading System</h2>

    <form method="post" action="">
      <label>Enter ID:</label>
      <input type="text" name="id" required>

      <label>Last Name:</label>
      <input type="text" name="lastname" required>

      <label>First Name:</label>
      <input type="text" name="firstname" required>

      <label>Middle Name:</label>
      <input type="text" name="middlename">

      <label>Course:</label>
      <input type="text" name="course" required>

      <label>Section:</label>
      <input type="text" name="section" required>

      <label>First Grading:</label>
      <input type="number" step="0.01" name="first" required>

      <label>Second Grading:</label>
      <input type="number" step="0.01" name="second" required>

      <label>Third Grading:</label>
      <input type="number" step="0.01" name="third" required>

      <label>Fourth Grading:</label>
      <input type="number" step="0.01" name="fourth" required>

      <input type="submit" name="submit" value="Submit" class="submit-btn">
    </form>

    <h3>Student Records</h3>

    <table>
      <tr>
        <th>ID</th><th>Last Name</th><th>First</th><th>Middle</th><th>Course</th><th>Sec</th>
        <th>1st</th><th>2nd</th><th>1st Sem Avg</th><th>3rd</th><th>4th</th><th>2nd Sem Avg</th>
        <th>Final Avg</th><th>Remarks</th><th>Action</th>
      </tr>

      <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?=htmlspecialchars($row['id'])?></td>
        <td><?=htmlspecialchars($row['lastname'])?></td>
        <td><?=htmlspecialchars($row['firstname'])?></td>
        <td><?=htmlspecialchars($row['middlename'])?></td>
        <td><?=htmlspecialchars($row['course'])?></td>
        <td><?=htmlspecialchars($row['section'])?></td>
        <td><?=number_format($row['first_grading'],2)?></td>
        <td><?=number_format($row['second_grading'],2)?></td>
        <td><?=number_format($row['first_sem_avg'],2)?></td>
        <td><?=number_format($row['third_grading'],2)?></td>
        <td><?=number_format($row['fourth_grading'],2)?></td>
        <td><?=number_format($row['second_sem_avg'],2)?></td>
        <td><?=number_format($row['final_avg'],2)?></td>
        <td><?=htmlspecialchars($row['remarks'])?></td>
        <td>
          <a href="?delete=<?=urlencode($row['id'])?>" onclick="return confirm('Delete this record?')">
            <button class="delete-btn">DELETE</button>
          </a>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>

</body>
</html>
