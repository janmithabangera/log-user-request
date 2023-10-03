<?php
require_once "../config.php";
# Initialize the session
session_start();

# If user is not logged in then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    echo "<script>" . "window.location.href='./login.php';" . "</script>";
    exit;
}

$query = "SELECT users.username,users.email, department.name as departmentName, 
tenders.tenderID, tenders.due_date, user_tender_requests.created_at,user_tender_requests.id FROM `user_tender_requests` 
inner join `users` on user_tender_requests.user_id= users.id inner join `tenders` on
user_tender_requests.tender_id = tenders.id inner join `department` on tenders.department_id = department.id where user_tender_requests.status= 'Requested'";

$data = [];
$result = $link->query($query);
while ($row = $result->fetch_assoc()) {
    $data[$row['tenderID']][] = $row;
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
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="col-lg-5 text-left">
            <h4 class="my-4"><?= htmlspecialchars($_SESSION["username"]); ?></h4>
            <a href="./logout.php" class="btn btn-primary">Log Out</a>
            <a href="../" class="btn btn-primary">Back </a>
            <a href="../sent-tenders/" class="btn btn-primary">Sent Tender</a>
            <a href="../alot-tenders/" class="btn btn-primary">Alot Tender</a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <h1>All Tender Request</h1>
                <div class="form-wrap border rounded p-4">
                    <?php if (count($data) > 0) {
                        foreach ($data  as $key => $values) { ?>
                            <table class="table  table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="6" class="text-center tenderID"> Tender ID: <?= $key ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="header">
                                        <td>S No.</td>
                                        <td>Username</td>
                                        <td>Email</td>
                                        <td>Department Name</td>
                                        <td>Due Date</td>
                                        <td>Request Created At</td>
                                        <td>Action</td>
                                    </tr>
                                    <?php 
                                    $counter=1;
                                    foreach ($values as $itemKey => $item) { ?>
                                        <tr>
                                            <td><?php echo $counter ?></td>
                                            <td><?php echo $values[$itemKey]["username"] ?></td>
                                            <td><?php echo $values[$itemKey]["email"] ?></td>
                                            <td><?php echo $values[$itemKey]['departmentName'] ?></td>
                                            <td><?php echo $values[$itemKey]['due_date'] ?></td>
                                            <td><?php echo $values[$itemKey]['created_at'] ?></td>
                                            <td width="15%">
                                                <a href="./update.php?id=<?php echo $values[$itemKey]["id"]; ?>" class="btn btn-success"> <i class="fa fa-edit"></i> Update File</a>
                                            </td>
                                        </tr>
                                    <?php $counter++; } ?>
                                </tbody>
                            </table>
                    <?php }
                    } else { 
                        echo  "<p class='text-center'>No data Found </p>";
                    } ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>