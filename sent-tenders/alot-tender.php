<?php
require_once "../config.php";
# Initialize the session
session_start();

# If user is not logged in then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    echo "<script>" . "window.location.href='./login.php';" . "</script>";
    exit;
}

$sectionError = $uploadFileError = $tenderNoError = "";
$fileUploadDirectory = '../uploadedFiles/';

$allDepartmentQuery = "SELECT * FROM department ORDER BY name ASC;";
$departments = $link->query($allDepartmentQuery);

$allSectionsQuery = "SELECT * FROM sections ORDER BY name ASC;";
$sections = $link->query($allSectionsQuery);

$query = "SELECT users.username,users.email, users.id as userID, department.name as departmentName, 
tenders.tenderID, userrequestlogs.id as tender_request_id, tenders.due_date, userrequestlogs.created_at FROM `userrequestlogs` 
inner join `users` on userrequestlogs.user_id= users.id inner join `tenders` on
 userrequestlogs.tender_id = tenders.id inner join `department` on tenders.department_id = department.id where userrequestlogs.id=?";


$updateTenderRequest = $link->prepare($query);
$updateTenderRequest->bind_param("i", $_GET['id']);
if ($updateTenderRequest->execute()) {
    $tenderRequest = $updateTenderRequest->get_result()->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST["editUser"])) {
        $sectionError = "select section Name";
    } else {
        $editUser = $_POST["editUser"];
    }

    if (!isset($_POST["reminder"])) {
        $tenderNoError = "Enter valid Tender No";
    } else {
        $reminder = $_POST["reminder"];
    }


    # Validate credentials 
    if (isset($editUser) && isset($reminder)) {
        $requestID = $tenderRequest['id'];
        $date = date('Y-m-d h:i:s');
        $sql = "UPDATE `user_tender_requests` SET `status`='Allotted',`tender_no`=?,`name_of_work`=?,`file_name`=?,
        `reference_code`=?,`section_id`=?,`sent_at`=? WHERE id=?";

        $stmt = $link->prepare($sql);
        $stmt->bind_param("ssssssi", $_POST['tender_no'], $_POST['work_name'], $filename, $_POST['ref_code'], $_POST['section_1'], $date, $requestID);
        if ($stmt->execute()) {
            echo "<script>" . "window.location.href='../sent-tenders/';" . "</script>";
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
                            <table width="98%" style="margin-left:10px border=1">
                                <tbody>
                                    <tr>
                                        <td width="20%"><b>Tender ID</b> : </td>
                                        <td width="80%">2022_MES_554329_3</td>
                                    </tr>

                                    <tr>
                                        <td width="20%"><b>Tender No</b> : </td>
                                        <td width="80%"> 37/CWE/ASR/2022-23</td>
                                    </tr>

                                    <tr>
                                        <td width="20%"><b>Ref No</b> : </td>
                                        <td width="80%"></td>
                                    </tr>

                                    <tr>
                                        <td width="20%"><b>Work Name</b> : </td>
                                        <td width="80%">SPECIAL REPAIR / REPLACEMENT OF LAUNDRY PLANT AGAINST BER TO MH AT AMRITSAR CANTT UNDER GE AMRITSAR</td>
                                    </tr>

                                    <tr>
                                        <td width="20%"><b>Department</b> : </td>
                                        <td width="20%">MES</td>

                                    </tr>

                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>CE JALANDHAR</td>
                                    </tr>

                                    <tr>
                                        <td>&nbsp;</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>&nbsp;</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>&nbsp;</td>
                                        <td></td>
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
                                    <div>
                                        <span>Edit User</span>
                                        <select name="editUser" class="js-example-basic-multiple form-control" onchange="GetOther(this.value)">
                                            <option value="">Select</option>

                                        </select>
                                    </div><br>
                                    <span id="other"></span>
                                    <div>
                                        <span>Set Reminder</span>
                                        <select name="reminder" class="form-control">
                                            <option value="0">0 Days</option>
                                        </select>
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