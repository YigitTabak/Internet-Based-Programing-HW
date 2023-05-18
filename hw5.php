<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Registration Form</title>
</head>
<body>
  <?php
  
  function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  
  $fullname = $email = $gender = "";
  $errors = [];

  if ($_SERVER["REQUEST"] == "POST") {
    
    $fullname = validateInput($_POST['fullname']);
    $email = validateInput($_POST['email']);
    $gender = validateInput($_POST['gender']);

    
    if (empty($fullname)) {
      $errors[] = "Full Name is required.";
    }

    if (empty($email)) {
      $errors[] = "Email Address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = "Invalid Email Address.";
    }

    if (empty($gender)) {
      $errors[] = "Gender is required.";
    } elseif (!in_array($gender, ['Male', 'Female'])) {
      $errors[] = "Invalid Gender.";
    }

   
    if (empty($errors)) {
      
      $host = 'localhost';
      $db = 'database';
      $user = 'root';
      $pass = '';

      $dsn = "mysql:host=$host;dbname=$db";
      $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
      ];

      try {
        $pdo = new PDO($dsn, $user, $pass, $options);
      } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit;
      }

      
      $sql = "INSERT INTO students (full_name, email, gender) VALUES (?, ?, ?)";

      $stmt = $pdo->prepare($sql);
      $stmt->execute([$fullname, $email, $gender]);

      
      echo "Student information inserted successfully!";
    }
  }
  ?>

  <h2>Student Registration Form</h2>
  <?php if (!empty($errors)) : ?>
    <ul>
      <?php foreach ($errors as $error) : ?>
        <li><?php echo $error; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="fullname">Full Name:</label>
    <input type="text" id="fullname" name="fullname" value="<?php echo $fullname; ?>" required><br><br>

    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" value="<?php echo $email; ?>" required><br><br>

    <label>Gender:</label>
    <input type="radio" id="male" name="gender" value="Male" <?php if ($gender === 'Male') echo 'checked'; ?> required>
    <label for="male">Male</label>
    <input type="radio" id="female" name="gender" value="Female" <?php if ($gender === 'Female') echo 'checked'; ?> required>
    <label for="female">Female</label><br><br>

    <input type="submit" value="Submit">
  </form>
</body>
</html>
