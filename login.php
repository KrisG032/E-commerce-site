<?php
session_start();
// Set secure session parameters
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);

if (isset($_SESSION["user"])) {
   header("Location: index.php");
   exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["login"])) {
           $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
           $password = $_POST["password"];
           
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
               echo "<div class='alert alert-danger'>Invalid email format</div>";
           } else {
               require_once "database.php";
               
               // Use prepared statement to prevent SQL injection
               $sql = "SELECT * FROM users WHERE email = ?";
               $stmt = mysqli_prepare($conn, $sql);
               mysqli_stmt_bind_param($stmt, "s", $email);
               mysqli_stmt_execute($stmt);
               $result = mysqli_stmt_get_result($stmt);
               $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
               
               if ($user) {
                   if (password_verify($password, $user["password"])) {
                       // Regenerate session ID to prevent session fixation
                       session_regenerate_id(true);
                       $_SESSION["user_id"] = $user["id"];
                       $_SESSION["user"] = "yes";
                       
                       // Set last login time
                       $update_sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
                       $update_stmt = mysqli_prepare($conn, $update_sql);
                       mysqli_stmt_bind_param($update_stmt, "i", $user["id"]);
                       mysqli_stmt_execute($update_stmt);
                       
                       header("Location: index.php");
                       exit();
                   } else {
                       echo "<div class='alert alert-danger'>Invalid email or password</div>";
                   }
               } else {
                   echo "<div class='alert alert-danger'>Invalid email or password</div>";
               }
               mysqli_stmt_close($stmt);
           }
        }
        ?>
      <form action="login.php" method="post">
        <div class="form-group">
            <input type="email" placeholder="Enter Email:" name="email" class="form-control">
        </div>
        <div class="form-group">
            <input type="password" placeholder="Enter Password:" name="password" class="form-control">
        </div>
        <div class="form-btn">
            <input type="submit" value="Login" name="login" class="btn btn-primary">
        </div>
      </form>
     <div><p>Not registered yet <a href="registration.php">Register Here</a></p></div>
    </div>
</body>
</html>