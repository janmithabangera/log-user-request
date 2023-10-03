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

#query to get all departments
$allDepartmentQuery = "SELECT * FROM department ORDER BY name ASC;";
$departments = $link->query($allDepartmentQuery);

#query to get all sections
$allSectionsQuery = "SELECT * FROM sections ORDER BY name ASC;";
$sections = $link->query($allSectionsQuery);

#query to get the request tender to be updated
$query = "SELECT users.username, users.email, users.id as userID, department.name as departmentName, 
tenders.tenderID, user_tender_requests.id, tenders.due_date, user_tender_requests.created_at FROM `user_tender_requests`
inner join `users` on user_tender_requests.user_id= users.id inner join `tenders` on user_tender_requests.tender_id = tenders.id 
inner join `department` on tenders.department_id = department.id where user_tender_requests.id=?;";


$updateTenderRequest = $link->prepare($query);
$updateTenderRequest->bind_param("i", $_GET['id']);
if ($updateTenderRequest->execute()) {
    $tenderRequest = $updateTenderRequest->get_result()->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST["section_1"])) {
        $sectionError = "select section Name";
    } else {
        $sectionID = $_POST["section_1"];
    }

    if (!isset($_POST["tender_no"])) {
        $tenderNoError = "Enter valid Tender No";
    } else {
        $tenderNo = $_POST["tender_no"];
    }

    #file upload to directory
    if (!empty($_FILES['file']['name'])) {
        $name = basename($_FILES["file"]["name"]);

        if ($_FILES["file"]["type"] != "application/pdf") {
            $uploadFileError = "upload valid pdf file";
        } elseif (move_uploaded_file($_FILES["file"]["tmp_name"], "$fileUploadDirectory$name")) {
            $filename = basename($_FILES['file']['name']);
        }
    } else {
        $uploadFileError = "upload valid file";
    }

    # Validate credentials 
    if (isset($tenderNo) && isset($sectionID) && isset($filename)) {
        $requestID = $tenderRequest['id'];
        $date = date('Y-m-d H:i:s');

        #query to update the tender request status along with other fields
        $sql = "UPDATE `user_tender_requests` SET `status`='Sent',`tender_no`=?,`name_of_work`=?,`file_name`=?,
        `reference_code`=?,`section_id`=?,`sent_at`=? WHERE id=?";

        $stmt = $link->prepare($sql);
        #params
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
                <div class="form-wrap border rounded p-4 ">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h4 class="box-title">Update File - Tender ID : <?php echo $tenderRequest['tenderID'] ?> </h4>
                        </div><!-- /.box-header -->

                        <!-- form start -->
                        <form method="POST" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                            <div class="box-body">
                                <div>
                                    <input type="file" class="form-control" placeholder="upload pdf file" name="file" required="">
                                    <small class="text-danger"><?= $uploadFileError; ?></small>
                                </div><br>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Tender No</span>
                                    <input type="text" class="form-control" placeholder="" name="tender_no" required="">
                                    <small class="text-danger"><?= $tenderNoError; ?></small>
                                </div><br>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Reference Code</span>
                                    <input type="text" class="form-control" placeholder="" name="ref_code">
                                </div><br>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Name of Work</span>
                                    <input type="text" class="form-control" placeholder="" name="work_name">
                                </div><br>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Tender ID</span>
                                    <input type="text" class="form-control" placeholder="" name="tender_id" value="<?php echo $tenderRequest['tenderID'] ?>" disabled>
                                </div><br>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Departments</span>
                                    <select name="department_id" class="form-control" required="" disabled>
                                        <option value="">Select Department</option>
                                        <?php if ($departments->num_rows > 0) {
                                            while ($row = $departments->fetch_assoc()) { ?>
                                                <option <?php echo 'value="' .  $row["id"] . '"';
                                                        if ($tenderRequest['departmentName'] == $row["name"]) {
                                                            echo "selected=''";
                                                        } ?>><?= $row["name"] ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div><br>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Section</span>
                                    <select name="section_1" class="form-control" required="" onchange="getOption(this.value,4)">
                                        <option value="">Select Section</option>
                                        <?php if ($sections->num_rows > 0) {
                                            while ($row = $sections->fetch_assoc()) { ?>
                                                <option value="<?= $row["id"] ?>"><?= $row["name"] ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                    <small class="text-danger"><?= $sectionError; ?></small>
                                </div><br>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>