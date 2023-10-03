<?php
require_once "./config.php";
# Initialize the session
session_start();

# If user is not logged in then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    echo "<script>" . "window.location.href='./login.php';" . "</script>";
    exit;
}

# Define variables and initialize with empty values
$tenderID = $tenderIDerror = $departmentNameError = "";
$formError = $departmentName = "";


$query = "SELECT * FROM department ORDER BY name ASC;";
$result = $link->query($query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST["departmentID"])) {
        $departmentNameError = "select department Name";
    } else {
        $departmentID = $_POST["departmentID"];
    }

    if (!isset($_POST["tenderID"])) {
        $tenderIDerror = "Enter valid TenderID";
    } else {
        $tenderID = $_POST["tenderID"];
    }

    # Validate credentials 
    if (!empty($tenderID) && !empty($departmentID)) {

        $validtenderID = "SELECT * FROM tenders WHERE tenderID = ? and department_id=?";
        $validtenderIDstmt = $link->prepare($validtenderID);
        $validtenderIDstmt->bind_param("si", $tenderID, $departmentID);
        if ($validtenderIDstmt->execute()) {
            $tenderIDRes = $validtenderIDstmt->get_result()->fetch_assoc();
            if ($tenderIDRes == null) {
                $formError = "Enter valid TenderID/Select valid department";
            } else {
                $userID = $_SESSION["id"];
                $sql = "INSERT INTO user_tender_requests (user_id, tender_id,status) VALUES (?, ?,'Requested')";
                $stmt = $link->prepare($sql);
                $stmt->bind_param("ss", $userID, $tenderIDRes['id']);
                if ($stmt->execute()) {
                    echo "<script>" . "window.location.href='./tender-requests/';" . "</script>";
                }
                echo "<script>" . "alert('Oops! Something went wrong. Please try again later.');" . "</script>";
            }
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
    <title>Tender Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="alert alert-success my-5">
            Welcome ! You are now logged in to your account.
        </div>
        <!-- User profile -->
        <div class="row justify-content-center">
            <div class="col-lg-5 text-center">
                <h4 class="my-4">Hello, <?= htmlspecialchars($_SESSION["username"]); ?></h4>
                <a href="./logout.php" class="btn btn-primary">Log Out</a>
                <a href="./tender-requests/" class="btn btn-primary">View All Requests</a>
            </div>
            <div class="col-lg-5">
                <div class="form-wrap border rounded p-4">
                    <h1>Tender Request</h1>
                    <?php
                    if (!empty($formError)) {
                        echo "<div class='alert alert-danger'>" . $formError . "</div>";
                    }
                    ?>
                    <!-- form starts here -->
                    <form method="post"  accept-charset="UTF-8" role="form" enctype="multipart/form-data" novalidate>
                        <div class="mb-3">
                            <label for="departmentName" class="form-label">departmentName</label>
                            <select class="form-select" aria-label="Default select example" name="departmentID" required>
                                <option disabled selected value="">Select Department</option>
                                <?php if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) { ?>
                                        <option value="<?= $row["id"] ?>"><?= $row["name"] ?></option>
                                <?php }
                                } ?>
                            </select>
                            <small class="text-danger"><?= $departmentNameError; ?></small>
                        </div>
                        <div class="mb-3">
                            <label for="tenderID" class="form-label">tenderID</label>
                            <input type="tenderID" class="form-control" name="tenderID" id="tenderID" value="<?= $tenderID; ?>" required>
                            <small class="text-danger"><?= $tenderIDerror; ?></small>
                        </div>
                        <div class="mb-3">
                            <input type="submit" class="btn btn-primary form-control" name="submit" value="Tender request">
                        </div>

                    </form>
                    <!-- form ends here -->
                </div>
            </div>
        </div>
    </div>
</body>

</html>