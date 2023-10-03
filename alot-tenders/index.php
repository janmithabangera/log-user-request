<?php
require_once "../config.php";
# Initialize the session
session_start();

# If user is not logged in then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    echo "<script>" . "window.location.href='./login.php';" . "</script>";
    exit;
}

#query to get all allotted tender requests
$query = "SELECT us.username,s.username as selectedUser, department.name as departmentName, tenders.tenderID, 
ur.id, ur.reference_code, ur.tender_No, ur.file_name, ur.allotted_at, ur.name_of_work, ur.reminder_days
FROM user_tender_requests ur
left join users us on ur.user_id= us.id 
left join users s on ur.edit_user_id= s.id
inner join `tenders` on ur.tender_id = tenders.id 
inner join `department` on tenders.department_id = department.id 
where ur.status= 'Allotted';";

$data = [];
$result = $link->query($query);

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
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="col-lg-5 text-left">
            <h4 class="my-4"><?= htmlspecialchars($_SESSION["username"]); ?></h4>
            <a href="./logout.php" class="btn btn-primary">Log Out</a>
            <a href="../tender-requests/" class="btn btn-primary">All User Tender Requests</a>
            <a href="../sent-tenders/" class="btn btn-primary">Sent Tender</a>
            <a href="../alot-tenders/" class="btn btn-success">Alot Tender</a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <h1>Allotted Tender Request</h1>
                <div class="form-wrap border rounded p-4">
                    <table class="table  table-bordered">
                        <thead>
                            <tr role="row" class="header">
                                <th rowspan="1" colspan="1">SNo</th>
                                <th rowspan="1" colspan="1">Tender No</th>
                                <th rowspan="1" colspan="1">Department</th>
                                <th rowspan="1" colspan="1">Work Name</th>
                                <th rowspan="1" colspan="1">User</th>
                                <th rowspan="1" colspan="1">Reminder</th>
                                <th rowspan="1" colspan="1">Allotted On</th>
                                <th rowspan="1" colspan="1">Option</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $counter = 1;
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <th colspan="8" class="text-center tenderID"> Tender ID: <?= $row["tenderID"] ?> Reference code: <?= $row["reference_code"] ?> </th>
                                    </tr>
                                    <tr>
                                        <td><?= $counter ?></td>
                                        <td><?= $row["tender_No"] ?></td>
                                        <td><?= $row['departmentName'] ?></td>
                                        <td><?= $row['name_of_work'] ?></td>
                                        <td><?= $row["username"] ?></td>
                                        <td><?= $row['reminder_days'] ?> Days</td>
                                        <td><?php echo $row['allotted_at'] . " (" . $row['selectedUser'] . ")" ?>
                                            <a href="../uploadedFiles/<?= $row['file_name'] ?>" target="_blank">View file</a>
                                        </td>
                                        <td width="15%">
                                            <a href="./update.php?id=<?php echo $row["id"]; ?>" class="btn btn-success"> <i class="fa fa-edit"></i> Edit Tender</a>
                                        </td>
                                    </tr>
                            <?php $counter++;
                                }
                            } else {
                                echo  "<p class='text-center'>No data Found </p>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>