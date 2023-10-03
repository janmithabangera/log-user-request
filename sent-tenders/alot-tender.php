<?php
require_once "../config.php";
# Initialize the session
session_start();

# If user is not logged in then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    echo "<script>" . "window.location.href='./login.php';" . "</script>";
    exit;
}

$editUserError = $reminderError = "";

$usersQuery = "SELECT * FROM users ORDER BY username ASC;";
$users = $link->query($usersQuery);
// print

$query = "SELECT department.name as departmentName, tenders.tenderID, user_tender_requests.id, 
user_tender_requests.tender_No, user_tender_requests.name_of_work, 
user_tender_requests.reference_code, sections.name as section_name FROM `user_tender_requests`
inner join `tenders` on user_tender_requests.tender_id = tenders.id 
inner join `sections` on user_tender_requests.section_id= sections.id
inner join `department` on tenders.department_id = department.id where user_tender_requests.id=?;";


$updateTenderRequest = $link->prepare($query);
$updateTenderRequest->bind_param("i", $_GET['id']);
if ($updateTenderRequest->execute()) {
    $tenderRequest = $updateTenderRequest->get_result()->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST["editUser"])) {
        $editUserError = "select user";
    } else {
        $editUserID = $_POST["editUser"];
    }

    if (!isset($_POST["reminder"])) {
        $reminderError = "select reminder days";
    } else {
        $reminderDays = $_POST["reminder"];
    }

    # Validate credentials 
    if (isset($editUserID) && isset($reminderDays)) {
        $requestID = $tenderRequest['id'];
        $date = date('Y-m-d H:i:s');

        $sql = "UPDATE `user_tender_requests` SET `status`='Allotted',`edit_user_id`=?,`reminder_days`=?, `allotted_at`=? WHERE id=?";
        $stmt = $link->prepare($sql);

        $stmt->bind_param("sssi", $editUserID, $reminderDays, $date, $requestID);
        if ($stmt->execute()) {
            echo "<script>" . "window.location.href='../alot-tenders/';" . "</script>";
        }
        echo "<script>" . "alert('Oops! Something went wrong. Please try again later.');" . "</script>";
    }
}
# Close connection
mysqli_close($link);

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
        <div class="col-lg-5 text-left">
            <h4 class="my-4"><?= htmlspecialchars($_SESSION["username"]); ?></h4>
            <a href="./logout.php" class="btn btn-primary">Log Out</a>
            <a href="../tender-requests/" class="btn btn-primary">All User Tender Requests</a>
            <a href="../sent-tenders/" class="btn btn-primary">Sent Tender</a>
            <a href="../alot-tenders/" class="btn btn-primary">Alot Tender</a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <h1>All Tender Request</h1>
                <div class="form-wrap border rounded p-4">
                    <section class="content">

                        <!-- general form elements -->
                        <div class="box box-primary">
                            <br>
                            <table class="table  table-bordered" width="98%" style="margin-left:10px border=1">
                                <tbody>
                                    <tr>
                                        <td width="20%"><b>Tender ID</b> : </td>
                                        <td width="80%"><?php echo $tenderRequest['tenderID'] ?></td>
                                    </tr>

                                    <tr>
                                        <td width="20%"><b>Tender No</b> : </td>
                                        <td width="80%"> <?php echo $tenderRequest['tender_No'] ?></td>
                                    </tr>

                                    <tr>
                                        <td width="20%"><b>Ref No</b> : </td>
                                        <td width="80%"><?php echo $tenderRequest['reference_code'] ?></td>
                                    </tr>

                                    <tr>
                                        <td width="20%"><b>Work Name</b> : </td>
                                        <td width="80%"><?php echo $tenderRequest['name_of_work'] ?></td>
                                    </tr>

                                    <tr>
                                        <td width="20%"><b>Department</b> : </td>
                                        <td width="20%"><?php echo $tenderRequest['departmentName'] ?></td>

                                    </tr>

                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><?php echo $tenderRequest['section_name'] ?></td>
                                    </tr>

                                    <tr>
                                        <td>&nbsp;</td>
                                        <td></td>
                                    </tr>

                                </tbody>
                            </table>

                            <hr>

                            <div class="box-header">
                                <h4 class="box-title">Update Alot Tender</h4>
                            </div><!-- /.box-header -->
                            <!-- form start -->



                            <form method="POST" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                                <div class="box-body">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Edit User</span>
                                        <select name="editUser" class="js-example-basic-multiple form-control" required="">
                                            <option value="">Select</option>
                                            <?php if ($users->num_rows > 0) {
                                                while ($row = $users->fetch_assoc()) { ?>
                                                    <option value="<?= $row["id"] ?>"><?= $row["username"] ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                        <small class="text-danger"><?= $editUserError; ?></small>
                                    </div><br>
                                    <span id="other"></span>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Set Reminder</span>
                                        <select name="reminder" class="form-control">
                                            <option value="0">0 Days</option>
                                            <?php for ($i = 1; $i <= 365; $i++) { ?>
                                                <option value="<?= $i ?>"><?= $i ?> Days</option>
                                            <?php } ?>
                                        </select>
                                        <small class="text-danger"><?= $reminderError; ?></small>
                                    </div>

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                                    </div>

                                </div>
                            </form><!-- /.box -->



                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</body>

</html>