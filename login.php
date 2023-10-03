<?php
# Initialize session
session_start();

# Check if user is already logged in, If yes then redirect him to index page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == TRUE) {
  echo "<script>" . "window.location.href='./'" . "</script>";
  exit;
}

# Include connection
require_once "./config.php";

# Define variables and initialize with empty values
$user_login_err = $user_password_err = $login_err = "";
$user_login = $user_password = "";

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (!isset($_POST["user_login"])) {
    $user_login_err = "Please enter your email id.";
  } else {
    $user_login = $_POST["user_login"];
  }

  if (!isset($_POST["user_password"])) {
    $user_password_err = "Please enter your password.";
  } else {
    $user_password = $_POST["user_password"];
  }

  # Validate credentials 
  if (empty($user_login_err) && empty($user_password_err)) {
    # Prepare a select statement
    $sql = "SELECT id, username, password FROM users WHERE email = ?";

    $loginUser = $link->prepare($sql);
    $loginUser->bind_param("s", $user_login);
    if ($loginUser->execute()) {
      $loginUserResult = $loginUser->get_result()->fetch_assoc();
      if (isset($loginUserResult)) {
        if (password_verify($user_password, $loginUserResult['password'])) {
          $_SESSION["id"] =  $loginUserResult['id'];
          $_SESSION["username"] = $loginUserResult['username'];
          $_SESSION["loggedin"] = TRUE;

          # Redirect user to index page
          echo "<script>" . "window.location.href='./'" . "</script>";
          exit;
        } else {
          $login_err = "The email or password you entered is incorrect.";
        }
      } else {
        $login_err = "This email is not registered";
      }
    } else {
      echo "<script>" . "alert('Oops! Something went wrong. Please try again later.');" . "</script>";
      echo "<script>" . "window.location.href='./login.php'" . "</script>";
      exit;
    }
  }

  # Close connection
  mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User login system</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <div class="row min-vh-100 justify-content-center align-items-center">
      <div class="col-lg-5">
        <?php
        if (!empty($login_err)) {
          echo "<div class='alert alert-danger'>" . $login_err . "</div>";
        }
        ?>
        <div class="form-wrap border rounded p-4">
          <h1>Log In</h1>
          <p>Please login to continue</p>
           <!-- form starts here -->
          <form method="post">
            <div class="mb-3">
              <label for="user_login" class="form-label">Email</label>
              <input type="text" class="form-control" name="user_login" id="user_login" value="<?= $user_login; ?>">
              <small class="text-danger"><?= $user_login_err; ?></small>
            </div>
            <div class="mb-2">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" name="user_password" id="password">
              <small class="text-danger"><?= $user_password_err; ?></small>
            </div>
            <div class="mb-3">
              <input type="submit" class="btn btn-primary form-control" name="submit" value="Log In">
            </div>
            <p class="mb-0">Don't have an account ? <a href="./register.php">Sign Up</a></p>
          </form>
           <!-- form ends here -->
        </div>
      </div>
    </div>
  </div>
</body>

</html>