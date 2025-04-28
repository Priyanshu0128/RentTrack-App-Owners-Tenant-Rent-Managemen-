<?php
include("navbar.php");
$joinsql = "SELECT u.id, u.name, u.image, u.mobile,u.email, u.gender ,r.`room_no`, r.`floor`,u.disable_date  FROM users u
JOIN `alloted_room` ar ON u.`id` = ar.`u_id`
JOIN `rooms` r ON ar.`room_no` = r.`id`
WHERE u.disable_date IS NULL;";

$joinresult = mysqli_query($conn, $joinsql);

$joinsql1 = "SELECT u.id, u.name, u.image, u.mobile, u.gender, u.date, u.disable_date FROM users u WHERE u.disable_date IS NOT NULL";


$joinresult1 = mysqli_query($conn, $joinsql1);

?>

<?php



if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['submit']) {

    $room_err = "";
    $selectedRoom = $_POST['selected_room'];
    $selectFloor = $_POST['select_floor'];



    if (empty($selectFloor) || empty($selectedRoom)) {
        $room_err = "Please Select Floor Or Room";
    } else {

        $name = $_POST['name'];
        $fatherName = $_POST['fatherName'];
        $motherName = $_POST['motherName'];
        $mobile = $_POST['mobile'];
        $aadhar = $_POST['aadhar'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $emergencyContact = $_POST['emergencyContact'];
        $permanentAddress = $_POST['permanentAddress'];
        $occupation = $_POST['occupation'];
        $schoolOrCollege = $_POST['schoolOrCollege'];
        $yearOrClass = $_POST['yearOrClass'];
        $collegeAddress = $_POST['collegeAddress'];
        $companyName = $_POST['companyName'];
        $jobProfile = $_POST['jobProfile'];
        $companyAddress = $_POST['companyAddress'];
        $roomrent = $_POST['rent'];
        $securityDeposite = $_POST['security_deposite'];
        $date = $_POST['date'];
        $imagetmp = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $rentTemp = $_FILES['rent_image']['tmp_name'];
        $rentImage = $_FILES['rent_image']['name'];
        $policeVariTemp = $_FILES['policeVari_image']['tmp_name'];
        $policeVari = $_FILES['policeVari_image']['name'];
        $aadharImg = $_FILES['aadhar_image']['name'];
        $aadharImgtmp = $_FILES['aadhar_image']['tmp_name'];

        $folder = './uploads';

        move_uploaded_file($imagetmp, $folder . '/' . $imageName);
        move_uploaded_file($rentTemp, $folder . '/' . $rentImage);
        move_uploaded_file($policeVariTemp, $folder . '/' . $policeVari);
        move_uploaded_file($aadharImgtmp, $folder . '/' . $aadharImg);
        // $aadharImg = $folder.'/'.$aadharImg;

        $sql = "INSERT INTO users (name,fatherName,motherName,mobile,aadhar,email,gender,emergencyContact,permanentAddress,occupation,schoolOrCollege,yearOrClass,collegeAddress,companyName,jobProfile,companyAddress,image,rent_image,policeVari_image,aadhar_image,rent,security_deposite,date) 
       VALUES ('$name','$fatherName','$motherName','$mobile','$aadhar','$email','$gender','$emergencyContact','$permanentAddress','$occupation','$schoolOrCollege','$yearOrClass','$collegeAddress','$companyName','$jobProfile','$companyAddress','$imageName','$rentImage','$policeVari','$aadharImg','$roomrent','$securityDeposite','$date')";

        $result = mysqli_query($conn, $sql);
        if ($result) {

            $last_id = mysqli_insert_id($conn);
            $selectFloor;
            $selectedRoom;

            $select_id = "SELECT * FROM rooms WHERE `room_no`='$selectedRoom' AND `floor`='$selectFloor'";
            $result1 = mysqli_query($conn,  $select_id);
            $row = mysqli_fetch_assoc($result1);

            $room_id = $row['id'];
            $room_capacity = $row['capacity'];

            $select_room = "INSERT INTO alloted_room (room_no , u_id) VALUES ('$room_id', $last_id)";
            mysqli_query($conn, $select_room);

            echo "<script>window.location='add_user.php'</script>";
        }
    }
}

?>

<!-- For Update -->
<?php

if (isset($_POST['update_user']) && !empty($_POST['user_id'])) {
    $userId = $_POST['user_id'];

    $uSql = "SELECT * FROM `users` WHERE `id`=$userId";
    $res = mysqli_query($conn, $uSql);
    if (mysqli_num_rows($res) == 0) {
        echo "<script>alert('Invalid Request');</script>";
        die();
    }
    $selectedRoom = $_POST['selected_room'];
    $selectFloor = $_POST['select_floor'];

    mysqli_query($conn, "DELETE FROM `alloted_room` WHERE `u_id`=$userId");

    $select_id = "SELECT * FROM rooms WHERE `room_no`='$selectedRoom' AND `floor`='$selectFloor'";
    $result1 = mysqli_query($conn,  $select_id);
    $rowRoom = mysqli_fetch_assoc($result1);

    $row = mysqli_fetch_assoc($res);
    $name = $_POST['name'];
    $fatherName = $_POST['fatherName'];
    $motherName = $_POST['motherName'];
    $mobile = $_POST['mobile'];
    $aadhar = $_POST['aadhar'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $emergencyContact = $_POST['emergencyContact'];
    $permanentAddress = $_POST['permanentAddress'];
    $occupation = $_POST['occupation'];
    $schoolOrCollege = $_POST['schoolOrCollege'];
    $yearOrClass = $_POST['yearOrClass'];
    $collegeAddress = $_POST['collegeAddress'];
    $companyName = $_POST['companyName'];
    $jobProfile = $_POST['jobProfile'];
    $companyAddress = $_POST['companyAddress'];
    $roomrent = $_POST['rent'];
    $securityDeposite = $_POST['security_deposite'];
    $date = $_POST['date'];

    $folder = './uploads';

    $imageName = $row['image'];
    if (!empty($_FILES['image']['name'])) {
        $imageName = $_FILES['image']['name'];
        $imagetmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($imagetmp, $folder . '/' . $imageName);
    }

    $rentImage = $row['rent_image'];
    if (!empty($_FILES['rent_image']['name'])) {
        $rentImage = $_FILES['rent_image']['name'];
        $rentTemp = $_FILES['rent_image']['tmp_name'];
        move_uploaded_file($rentTemp, $folder . '/' . $rentImage);
    }

    $policeVari = $row['policeVari_image'];
    if (!empty($_FILES['policeVari_image']['name'])) {
        $policeVari = $_FILES['policeVari_image']['name'];
        $policeVariTemp = $_FILES['policeVari_image']['tmp_name'];
        move_uploaded_file($policeVariTemp, $folder . '/' . $policeVari);
    }


    $aadharImg = $row['aadhar_image'];
    if (!empty($_FILES['aadhar_image']['name'])) {
        $aadharImg = $_FILES['aadhar_image']['name'];
        $aadharImgtmp = $_FILES['aadhar_image']['tmp_name'];
        move_uploaded_file($aadharImgtmp, $folder . '/' . $aadharImg);
    }


    $updateSql = "UPDATE users SET
        name='$name',
        fatherName='$fatherName',
        motherName='$motherName',
        mobile='$mobile',
        aadhar='$aadhar',
        email='$email',
        gender='$gender',
        emergencyContact='$emergencyContact',
        permanentAddress='$permanentAddress',
        occupation='$occupation',
        schoolOrCollege='$schoolOrCollege',
        yearOrClass='$yearOrClass',
        collegeAddress='$collegeAddress',
        companyName='$companyName',
        jobProfile='$jobProfile',
        companyAddress='$companyAddress',
        image='$imageName',
        rent_image='$rentImage',
        policeVari_image='$policeVari',
        aadhar_image='$aadharImg',
        rent='$roomrent',
        security_deposite='$securityDeposite',
        date='$date'
        WHERE id=$userId";

    $updateResult = mysqli_query($conn, $updateSql);

    $room_id = $rowRoom['id'];

    $select_room = "INSERT INTO alloted_room (room_no , u_id) VALUES ('$room_id', $userId)";
    mysqli_query($conn, $select_room);

    if ($updateResult) {
        echo "<script>window.location='add_user.php'</script>";
    } else {
        echo "<script>alert('Update Failed');</script>";
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user_details.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <title>Document</title>
    <style>
        .pad {
            padding: 10px;
        }

        .button-1 {
            background-color: #0081ff;
            border-radius: 8px;
            border-style: none;
            box-sizing: border-box;
            color: #FFFFFF;
            cursor: pointer;
            display: inline-block;
            font-family: "Haas Grot Text R Web", "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
            font-weight: 500;
            height: 40px;
            line-height: 20px;
            list-style: none;
            margin: 0;
            outline: none;
            padding: 10px 16px;
            text-align: center;
            text-decoration: none;
            transition: color 100ms;
            vertical-align: baseline;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        /* .button-1:hover,
        .button-1:focus {
            background-color: #F082AC;
        } */

        .home {
            /* height: 100vh; */
            width: calc(100% - 88px);
            background: #fff;
            transition: var(--tran-05);
            padding: 50px 80px 50px 80px;
        }

        .home .text {
            font-size: 30px;
        }

        .sidebar.close~.home {
            left: 88px;
            width: calc(100% - 88px);
        }

        #customers {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        #customers td,
        #customers th {
            font-size: 13px;
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        .dr {
            text-decoration: none;
        }

        #t_data {
            font-size: 12px;
            color: grey;
            background-color: #fff;
            text-align: left;
        }

        .anr {
            text-decoration: none;
            color: grey;
        }

        .sub {
            background-color: #ec9d28;
            color: black;
            padding: 0px 5px;
            border-radius: 5px;
        }

        .button {
            background-color: #000000b3;
            float: right;
            border: none;
            color: white;
            padding: 5px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 13px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }

        .send {
            background-color: #0000003d;
            /* float: right; */
            border: none;
            color: #000000;
            padding: 5px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 13px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }

        .head {
            display: flex;
            justify-content: space-between;
        }

        .imgCont {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .edit-button,
        .disable-button {
            padding: 5px 10px;
            margin-right: 20px;
            border-radius: 5px;
            font-size: 10px;
            border: none;
        }

        .edit-button:hover {
            background-color: #000;
            color: #fff;
            font-weight: bold;
        }

        .disable-button:hover {
            background-color: red;
            color: #fff;
            font-weight: bold;
        }

        /* Jquery Css */
        .dataTables_length {
            display: none;
        }

        .dataTables_filter {
            margin-bottom: 25px;
        }

        .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dataTables_info {
            display: none;
        }

        .dataTables_paginate {
            margin: 20px
        }

        /* .dataTables_paginate {
            display: none;
        }  */

        .image-popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
        }

        .popup-content {
            background: #fff;
            padding: 20px;
            max-width: 800px;
            overflow: auto;
        }

        .close-btn {
            position: absolute;
            top: 50px;
            right: 50px;
            cursor: pointer;
            font-size: 25px;
            font-weight: bold;
        }

        .error {
            color: red !important;
        }

        ::placeholder {
            color: #03a9f4
        }

        .tablerow td {
            border: 1px solid #8080802b;
            font-size: 14px;
            padding: 5px 10px;
        }

        table.dataTable.no-footer {
            border: none;
        }

        .tablerow.odd:hover,
        .tablerow.even:hover {
            background-color: #60585e24;
        }

        .head p {
            color: #707070;
            font-weight: 600;
            font-size: 20px;
            /* margin-bottom: 20px; */
            border-bottom: 4px solid var(--sub_btn);
            display: inline-block;
            letter-spacing: 1px
        }

        /* #userImage{
            position: absolute;
             top:35px; 
             right:30px; 
             cursor: pointer;
        } */

        .addMore {

            display: inline-block;
            padding: 6px 25px;
            /* Adjust the padding to set the button size */
            background-color: #4CAF50;
            /* Add your desired background color */
            color: white;
            /* Set the text color */
            text-align: center;
            text-decoration: none;
            font-size: 15px;
            /* Set the font size */
            cursor: pointer;
            border-radius: 5px;
            /* Optional: Add rounded corners */
        }

        .checkUser {
            color: #707070;
            font-weight: 600;
            font-size: 16px;
            /* margin-bottom: 20px; */
            border-bottom: 4px solid var(--sub_btn);
            display: inline-block;
            letter-spacing: 1px;
        }

        @media screen and (max-width: 760px) {
            nav.sidebar.close {
                display: none;
            }

            .sidebar.close~.home {
                width: 100%;
                left: 0;
                padding: 15px 20px 0px;
            }

            .navbar.mob {
                display: block;
            }

            .head p {
                font-size: 12px;
            }

            .button-1 {
                font-size: 12px;
                height: 30px;
                line-height: 10px;
                font-weight: bold;
            }

            .mainDash {
                overflow-x: scroll;
            }

            .mainDash1 {
                overflow-x: scroll;
            }


            .modal-dialog {
                display: flex;
                justify-content: center;
            }

            .modal-content {
                width: 90%;
            }

            .inputBx input {
                font-size: 12px;
            }

            .inputBx label {
                font-size: 12px;
            }

            #aadhar_image {
                top: 25px;
                right: 70px;
                width: 90px
            }

            .inputBx select {
                font-size: 10px;
            }

            .inputBx input[type="submit"] {
                font-weight: bold;
            }

            .modal-title {
                font-size: 16px;
            }

            #userImage {
                width: 40px;
                height: 30px;
            }

            #rentImage {
                width: 40px;
                height: 30px;
            }

            #polImage {
                width: 40px;
                height: 30px;
            }

            #aadharImage {
                width: 40px;
                height: 30px;
            }

            .box_container input {
                max-width: 120px;
                width: 100%;
            }

            .edit-button,
            .disable-button {
                background-color: #0081ff;
                color: #ffffff;
                margin-top: 5px;
            }

            .imgCont {
                flex-direction: column;
            }

            table.dataTable tbody th,
            table.dataTable tbody td {
                padding: 2px 8px;
                font-size: 15px;
            }


        }
    </style>
</head>

<body>

    <section class="home">
        <div class="head">
            <p>Registration</p>
            <a class="button-1" role="button" id="openRegistrationModal">Registration</a>
        </div>
        <br>
        <div class="mainDash">
            <p class="checkUser">Present Tenant</p>
            <table id="example" style="box-sizing: border-box; box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.1);background: #fff;">
                <thead class="tablerow" id="t_data">
                    <th>Name</th>
                    <th>Contact No.</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Room No.</th>
                    <th>Floor No.</th>
                    <th>Action</th>
                </thead>
                <?php
                // $row = $joinresult->fetch_assoc()
                // $row = mysqli_fetch_assoc($joinresult)
                while ($row = mysqli_fetch_assoc($joinresult)) {
                    echo '<tr class="tablerow">';
                    $imageSrc = (!empty($row["image"])) ? $baseUrl . 'uploads/' . $row["image"] : 'default.png';
                    echo '<td class="imgCont" >';
                    echo '<img class="user-image" src="' . $imageSrc . '" alt="" style="width:40px; height:40px; border-radius:50px;">';
                    echo '<span style="text-transform: capitalize;">' . $row["name"] . '</span>';
                    echo '</td>';
                    echo '<td>' . $row["mobile"] . '</td>';
                    echo '<td>' . $row["email"] . '</td>';
                    echo '<td>' . $row["gender"] . '</td>';
                    echo '<td>' . $row["room_no"] . '</td>';
                    echo '<td style="text-transform: capitalize;">' . preg_replace("/[^a-zA-Z ]/", " ", $row["floor"]) . '</td>';
                    echo '<td>';
                    echo '<button class="edit-button" onclick="editRecord(' . $row["id"] . ')">Edit</button>';
                    echo '<button class="disable-button" onclick="deleteRecord(' . $row["id"] . ')">Deactivate</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
        <div class="mainDash1">
            <p class="checkUser">Past Tenant</p>
            <table id="example1" style="box-sizing: border-box; box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.1);background: #b6b5b570;">
                <thead class="tablerow" id="t_data">
                    <th>Name</th>
                    <th>Contact No.</th>
                    <th>Gender</th>
                    <th>Join Date</th>
                    <th>Disable Date</th>
                </thead>
                <?php
                while ($row1 = mysqli_fetch_assoc($joinresult1)) {
                    echo '<tr class="tablerow">';
                    $imageSrc = (!empty($row1["image"])) ? $baseUrl . 'uploads/' . $row1["image"] : 'default.png';
                    echo '<td class="imgCont" >';
                    echo '<img class="user-image" src="' . $imageSrc . '" alt="" style="width:40px; height:40px; border-radius:50px;">';
                    echo '<span style="text-transform: capitalize;">' . $row1["name"] . '</span>';
                    echo '</td>';
                    echo '<td>' . $row1["mobile"] . '</td>';
                    echo '<td>' . $row1["gender"] . '</td>';
                    echo '<td>' . date('d-m-Y', strtotime($row1["date"])) . '</td>';
                    echo '<td>' . date('d-m-Y', strtotime($row1["disable_date"])) . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>

        </div>
    </section>


    <div class="modal fade" id="disableModal" tabindex="-1" role="dialog" aria-labelledby="disableModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="disableModalLabel">Deactived Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="disableForm">
                        <div class="form-group">
                            <label for="disable_date">Deactived Date</label>
                            <input type="date" class="form-control" id="disable_date" name="disable_date">
                        </div>
                        <input type="hidden" id="recordId" name="recordId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="disableSubmitButton">Deactive</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" tabindex="-1" role="dialog" id="registrationModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mtitle">Registration Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:30px">
                    <form id="registrationForm" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                        <input type="hidden" name="user_id" id="userId">
                        <div class="row">


                            <div class="inputBx">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" autocomplete="off"><br>
                            </div>

                            <div class="inputBx">
                                <label for="fatherName">Father's Name:</label>
                                <input type="text" id="fatherName" name="fatherName" autocomplete="off"><br>
                            </div>

                            <div class="inputBx">
                                <label for="motherName">Mother's Name:</label>
                                <input type="text" id="motherName" name="motherName" autocomplete="off"><br>
                            </div>

                            <div class="inputBx">
                                <label for="mobile">Mobile Number:</label>
                                <input type="tel" id="mobile" name="mobile" autocomplete="off"><br>
                            </div>

                            <div class="inputBx">
                                <div><label for="aadhar">Aadhar Number:</label></div>
                                <div>
                                    <input type="text" id="aadhar" name="aadhar" autocomplete="off" style="position:realtive">
                                    <input type="file" name="aadhar_image" id="aadhar_image" value="ddddd"><br>
                                    <div id="aadharImage" style="position: absolute; top:31px; right:25px; cursor: pointer;">
                                    </div>
                                </div>
                            </div>

                            <div class="inputBx">
                                <div class="dropdown1">
                                    <label for="gender">Gender:</label>
                                    <input type="text" class="optionNameInput" id="gender" name="gender" onclick="toggleDropdown('genderDropdown1')" value="" autocomplete="off">

                                    <div class="dropdown-content1" id="genderDropdown1">
                                        <div class="dropdown-option1" onclick="selectOption('gender', 'Male')">Male</div>
                                        <div class="dropdown-option1" onclick="selectOption('gender', 'Female')">Female</div>
                                    </div>
                                </div>
                            </div>

                            <div class="inputBx">
                                <label for="email">Email ID:</label>
                                <input type="email" id="email" name="email" autocomplete="off"><br>
                            </div>

                            <div class="inputBx">
                                <label for="emergencyContact">Emergency Contact Number:</label>
                                <input type="tel" id="emergencyContact" name="emergencyContact" autocomplete="off"><br>
                            </div>

                            <div class="inputBx">
                                <label for="permanentAddress">Permanent Address:</label>
                                <input type="text" id="permanentAddress" name="permanentAddress" autocomplete="off"><br>
                            </div>

                            <div class="inputBx">
                                <label for="occupation">Occupation:</label>
                                <select id="occupation" name="occupation" onchange="showFields()">
                                    <option value="" selected disabled>Select Occupation</option>
                                    <option value="student">Student</option>
                                    <option value="jobPerson">Job Person</option>
                                </select><br>
                            </div>

                            <div class="student-fields">

                                <div class="inputBx">
                                    <label for="schoolOrCollege">School/College Name:</label>
                                    <input type="text" id="schoolOrCollege" name="schoolOrCollege" autocomplete="off"><br>
                                </div>
                                <span class="text-danger"><?php echo "$sch_err" ?></span>

                                <div class="inputBx">
                                    <label for="yearOrClass">Year/Class:</label>
                                    <input type="text" id="yearOrClass" name="yearOrClass" autocomplete="off"><br>
                                </div>
                                <span class="text-danger"><?php echo "$yc_err" ?></span>

                                <div class="inputBx">
                                    <label for="collegeAddress">College Address:</label>
                                    <textarea id="collegeAddress" name="collegeAddress" autocomplete="off"></textarea><br>
                                </div>
                                <span class="text-danger"><?php echo "$clgAd_err" ?></span>

                            </div>

                            <div class="job-fields">

                                <div class="inputBx">
                                    <label for="companyName">Company Name:</label>
                                    <input type="text" id="companyName" name="companyName" autocomplete="off"><br>
                                </div>
                                <span class="text-danger"><?php echo "$com_err" ?></span>

                                <div class="inputBx">
                                    <label for="jobProfile">Job Profile:</label>
                                    <input type="text" id="jobProfile" name="jobProfile" autocomplete="off"><br>
                                </div>
                                <span class="text-danger"><?php echo "$job_err" ?></span>

                                <div class="inputBx">
                                    <label for="companyAddress">Company Address:</label>
                                    <textarea id="companyAddress" name="companyAddress" autocomplete="off"></textarea><br>
                                </div>
                                <span class="text-danger"><?php echo "$comAd_err" ?></span>

                            </div>

                            <div class="inputBx">
                                <div><label for="image">Upload Image</label></div>
                                <div>
                                    <input type="file" name="image" id="image" class="pad" style="position:relative">
                                    <div id="userImage" style=" position: absolute;top:35px; right:30px; cursor: pointer;"></div>
                                </div>

                                <!-- <img src="" id="userImage" width="50" height="50" > -->
                            </div>

                            <div class="inputBx">
                                <div><label for="rent_image">Rent Agreement</label></div>
                                <div>
                                    <input type="file" name="rent_image" id="rent_image" class="pad" style="position:relative">
                                    <div id="rentImage" style="position: absolute; top:35px; right:30px; cursor: pointer;"></div>
                                </div>
                                <!-- <img src="" id="rentImage" width="50" height="50"> -->
                            </div>

                            <div class="inputBx">
                                <div> <label for="policeVari_image">Police Verification</label></div>
                                <div>
                                    <input type="file" name="policeVari_image" id="policeVari_image" class="pad" style="position:relative">
                                    <div id="polImage" style="position: absolute; top:35px; right:30px; cursor: pointer;"></div>
                                </div>
                                <!-- <img src="" id="polImage" width="50" height="50"> -->
                            </div>

                            <div class="inputBx">
                                <label for="select_floor">Allot Room:</label>
                                <select id="select_floor" name="select_floor" onchange="showRooms()">
                                    <option value="" selected disabled>Select Room</option>
                                    <option value="ground_floor">Ground Floor</option>
                                    <option value="first_floor">First Floor</option>
                                </select>
                            </div>

                            <div class="ground_flr">
                                <input type="hidden" name="selected_room" class="selected_room">
                                <div class="box_container boxrooms"></div>
                            </div>

                            <!-- <div class="first_flr"></div> -->

                            <div class="inputBx">
                                <label for="rent">Rent:</label>
                                <input type="text" id="rent" name="rent" autocomplete="off"><br>
                            </div>

                            <div class="inputBx">
                                <label for="security_deposite">Security Deposite:</label>
                                <input type="text" id="security_deposite" name="security_deposite" autocomplete="off"><br>
                            </div>

                            <div class="inputBx">
                                <label for="date">Date:</label>
                                <input type="date" id="date" name="date" autocomplete="off"><br>
                            </div>

                            <div class="inputBx">
                                <input type="submit" name="submit" id="subBtn" value="Submit">
                            </div>

                            <div id="imagePopup" class="image-popup">
                                <div class="popup-content">
                                    <span class="close-btn" style="color: #fff;" onclick="closePopup()">X</span>
                                    <object id="popupObject" data="" type="application/pdf" style="width:100%;"></object>
                                    <img id="popupImage" style="width:100%; display: none;">
                                </div>
                            </div>


                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div id="userDetailsModal" class="pop">
        <div class="popup">
            <span class="close" onclick="closeUserDetailsModal()">&times;</span>
            <div id="userDetailsContent"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script>
        new DataTable('#example');
        new DataTable('#example1');

        $(document).ready(function() {
            $('#example').DataTable({
                "lengthMenu": [5, 10],
                "pageLength": 10,
                "info": true,
                "paging": true,
                pagingType: 'full_numbers',
                "responsive": {
                    breakpoints: [{
                            name: 'desktop',
                            width: Infinity
                        },
                        {
                            name: 'tablet',
                            width: 1024
                        },
                        {
                            name: 'fablet',
                            width: 768
                        },
                        {
                            name: 'phone',
                            width: 480
                        }
                    ]
                }
            });
        });
        $(document).ready(function() {
            $('#example1').DataTable({
                "lengthMenu": [5, 10],
                "pageLength": 10,
                "info": true,
                "paging": true,
                pagingType: 'full_numbers',
                "responsive": {
                    breakpoints: [{
                            name: 'desktop',
                            width: Infinity
                        },
                        {
                            name: 'tablet',
                            width: 1024
                        },
                        {
                            name: 'fablet',
                            width: 768
                        },
                        {
                            name: 'phone',
                            width: 480
                        }
                    ]
                }
            });
        });


        function openRegistrationModal(method) {
            if (method === 'register') {
                // Reset the form using the native reset method
                document.getElementById('registrationForm').reset();
                document.querySelector('.ground_flr').style.display = 'none'
                document.getElementById('mtitle').innerHTML = 'Registration Form';
                document.getElementById('subBtn').setAttribute('name', 'submit');
                document.getElementById('occupation').value = '';
                document.querySelector('.student-fields').style.display = 'none'
                document.querySelector('.job-fields').style.display = 'none'




                // Reset the form validation state
                $('#registrationForm').validate().resetForm();

                // Clear the content of a div with id 'resultDiv'
                $('#userImage').html('');
                $('#polImage').html('');
                $('#rentImage').html('');
                $('#aadharImage').html('');
            }
            $('#registrationModal').modal('show');
        }

        // Function to close the registration modal
        function closeRegistrationModal() {
            $('#registrationModal').modal('hide');
        }

        $('#openRegistrationModal').on('click', function() {
            openRegistrationModal('register');
        });



        //For Registration

        function showFields() {

            let occupation = document.getElementById("occupation").value;
            let studentFields = document.querySelector(".student-fields");
            let jobFields = document.querySelector(".job-fields");

            if (occupation === "student") {
                studentFields.style.display = "block";
                jobFields.style.display = "none";
            } else if (occupation === "jobPerson") {
                studentFields.style.display = "none";
                jobFields.style.display = "block";
            } else {
                studentFields.style.display = "none";
                jobFields.style.display = "none";
            }

        }

        let longPressTimer;

        function startLongPress(selectedRoom) {
            longPressTimer = setTimeout(function() {

                $.ajax({
                    type: "POST",
                    url: "ajax_user_details.php",
                    data: {
                        "room_no": selectedRoom
                    },
                    success: function(response) {

                        $('#userDetailsContent').html(response);
                        $('#userDetailsModal').css('display', 'flex');
                    }
                });
            }, 1000);
        }

        function cancelLongPress() {
            clearTimeout(longPressTimer);
        }

        function closeUserDetailsModal() {
            $('#userDetailsModal').css('display', 'none');
        }


        function showRooms() {
            let allotroom = document.getElementById("select_floor").value;
            let groundFlr = document.querySelector(".ground_flr");
            groundFlr.style.display = "none";
            handel_ajax(allotroom);
        }

        function handel_ajax(allotroom, selectedRoom) {

            $.ajax({
                type: "POST",
                url: "ajax_user.php",
                data: {
                    "floor": allotroom,
                    "selectedRoom": selectedRoom
                },
                success: function(response) {
                    let groundFlr = document.querySelector(".ground_flr");
                    groundFlr.style.display = "block";

                    let res = JSON.parse(response);
                    // console.log(res)
                    $('.boxrooms').empty();

                    $.each(res, (i, val) => {

                        let isSelected = selectedRoom && val.room_no === selectedRoom ? 'selected' : '';

                        let occupancyStatus = determineOccupancyStatus(val.capacity, val.room_allocation_count);

                        function determineOccupancyStatus(roomCapacity, currentOccupancy) {
                            if (currentOccupancy == 0) {
                                return 'vacant';
                            } else if (currentOccupancy < roomCapacity) {
                                return 'half-filled';
                            } else {
                                return 'full';
                            }
                        }

                        let isDisabled = occupancyStatus === 'full' ? 'disabled' : '';

                        $('.boxrooms').append(`
      <div class="box ${isSelected} ${occupancyStatus}"  onmousedown="startLongPress('${val.room_no}')" onmouseup="cancelLongPress()" onclick="showUserDetails('${val.room_no}')">
       <div><input type="text" name="${val.room_no}" id="${val.id}" style="height:35px;"  value="${val.room_no}" data-capacity="${val.room_allocation_count}" ${isDisabled} readonly></div>
     </div>
  `);
                    });
                    //         $('.boxrooms').append(`
                    //     <div class="box add-room" onclick="addVacantRoom('${allotroom}')">
                    //         <div><span class="addMore">Add More+</span></div>
                    //     </div>
                    // `);

                    $('.boxrooms input').on('click', function() {
                        let selectedRoom = $(this).val();
                        // console.log(selectedRoom);
                        $('.box').removeClass('selected')
                        let parent = $(this).parent().parent()
                        parent[0].classList.add('selected');
                        $('.selected_room').val(selectedRoom)
                    });
                }
            });
        }

        // function addVacantRoom(floor) {
        //     $.ajax({
        //         type: "POST",
        //         url: "add_vacant_room.php",
        //         data: {
        //             "floor": floor
        //         },
        //         success: function(response) {
        //             handel_ajax(floor, null);
        //         }
        //     });
        // }

        function toggleDropdown(dropdownId) {
            let dropdown = document.getElementById(dropdownId);
            dropdown.classList.toggle("show");
        }

        function selectOption(inputId, optionValue, dropdownId) {
            let inputField = document.getElementById(inputId);
            inputField.value = optionValue;
            closeDropdown(dropdownId);
        }

        function closeDropdown(dropdownId) {
            let dropdown = document.getElementById(dropdownId);
            dropdown.classList.remove("show");
        }

        window.onclick = function(event) {
            if (!event.target.matches('.optionNameInput')) {
                closeDropdown('genderDropdown1');
            }
        }


        //For User Editing
        function editRecord(userId) {
            $('input').removeClass('error');
            $('select').removeClass('error');
            $('label.error').remove();

            if (userId == "") {
                alert('User Not valid.')
                return false;
            }

            $('.modal-title').html(`Update User`)
            $('#subBtn').attr("name", `update_user`)
            $.ajax({
                type: "POST",
                url: "ajax_get_user.php",
                data: {
                    "id": userId
                },
                success: function(response) {

                    function loadURLToInputFiled(url, inputId) {
                        getImgURL(url, (imgBlob) => {
                            // Load img blob to input
                            // WIP: UTF8 character error
                            let fileName = url;
                            let file = new File([imgBlob], fileName, {
                                type: "image/jpeg",
                                lastModified: new Date().getTime()
                            }, 'utf-8');
                            let container = new DataTransfer();
                            container.items.add(file);
                            document.querySelector(`#${inputId}`).files = container.files;

                        })
                    }

                    function getImgURL(url, callback) {
                        var xhr = new XMLHttpRequest();
                        xhr.onload = function() {
                            callback(xhr.response);
                        };
                        xhr.open('GET', url);
                        xhr.responseType = 'blob';
                        xhr.send();
                    }

                    let userData = JSON.parse(response);

                    $('#userId').val(userData.id);
                    $('#name').val(userData.name);
                    $('#fatherName').val(userData.fatherName);
                    $('#motherName').val(userData.motherName);
                    $('#mobile').val(userData.mobile);
                    $('#aadhar').val(userData.aadhar);
                    $('#gender').val(userData.gender);
                    $('#email').val(userData.email);
                    $('#emergencyContact').val(userData.emergencyContact);
                    $('#permanentAddress').val(userData.permanentAddress);
                    $('#occupation').val(userData.occupation);
                    if (userData.occupation == "student") {
                        $('.student-fields').show();
                        $('.job-fields').hide();
                    } else {
                        $('.job-fields').show();
                        $('.student-fields').hide();
                    }
                    $('#schoolOrCollege').val(userData.schoolOrCollege);
                    $('#yearOrClass').val(userData.yearOrClass);
                    $('#collegeAddress').val(userData.collegeAddress);
                    $('#companyName').val(userData.companyName);
                    $('#jobProfile').val(userData.jobProfile);
                    $('#companyAddress').val(userData.companyAddress);
                    $('#aadharImage').html(`
                   ${userData.aadhar_image.endsWith('.pdf') ?
                  `<span class="pdf-icon" style="display: ${userData.aadhar_image.endsWith('.pdf') ? 'inline-block' : 'none'}">
                   <!-- Provide a link to download the PDF -->
                   <a href="./uploads/${userData.aadhar_image}" download>
                   <!-- Insert PDF icon here -->
                   <img src="pdf-icon.png" alt="PDF Icon" width="40" height="40">
                   </a>
                   </span>` :
                   `<img onclick="showImage(this)" src="./uploads/${userData.aadhar_image}" id="aadharImage" width="40" height="40" >`}
                   `);
                    loadURLToInputFiled(`./uploads/${userData.aadhar_image}`, "aadhar_image")

                    // $('#userImage').html(`<img onclick="showImage(this)"  src="./uploads/${userData.image}" id="userImage" width="40" height="40" >`);
                    // loadURLToInputFiled(`./uploads/${userData.image}`, "image")
                    $('#userImage').html(`
                    ${userData.image.endsWith('.pdf') ?
                    `<span class="pdf-icon" style="display: ${userData.image.endsWith('.pdf') ? 'inline-block' : 'none'}">
                    <!-- Provide a link to download the PDF -->
                    <a href="./uploads/${userData.image}" download>
                    <!-- Insert PDF icon here -->
                   <img src="pdf-icon.png" alt="PDF Icon" width="40" height="40">
                   </a>
                   </span>` :
                  `<img onclick="showImage(this)" src="./uploads/${userData.image}" id="userImage" width="40" height="40" >`}
                  `);
                    loadURLToInputFiled(`./uploads/${userData.image}`, "image")

                    // $('#rentImage').html(`<img onclick="showImage(this)"  src="./uploads/${userData.rent_image}" id="rentImage" width="40" height="40" >`);
                    // loadURLToInputFiled(`./uploads/${userData.rent_image}`, "rent_image")

                    $('#rentImage').html(`
                    ${userData.rent_image.endsWith('.pdf') ?
                    `<span class="pdf-icon" style="display: ${userData.rent_image.endsWith('.pdf') ? 'inline-block' : 'none'}">
                    <!-- Provide a link to download the PDF -->
                    <a href="./uploads/${userData.rent_image}" download>
                    <!-- Insert PDF icon here -->
                    <img src="pdf-icon.png" alt="PDF Icon" width="40" height="40">
                    </a>
                    </span>` :
                    `<img onclick="showImage(this)" src="./uploads/${userData.rent_image}" id="rentImage" width="40" height="40" >`}
                    `);
                    loadURLToInputFiled(`./uploads/${userData.rent_image}`, "rent_image")

                    // $('#polImage').html(`<img onclick="showImage(this)"  src="./uploads/${userData.policeVari_image}" id="polImage" width="40" height="40" >`);;
                    // loadURLToInputFiled(`./uploads/${userData.policeVari_image}`, "policeVari_image")

                    $('#polImage').html(`
                    ${userData.policeVari_image.endsWith('.pdf') ?
                    `<span class="pdf-icon" style="display: ${userData.policeVari_image.endsWith('.pdf') ? 'inline-block' : 'none'}">
                    <!-- Provide a link to download the PDF -->
                    <a href="./uploads/${userData.policeVari_image}" download>
                    <!-- Insert PDF icon here -->
                    <img src="pdf-icon.png" alt="PDF Icon" width="40" height="40">
                    </a>
                    </span>` :
                    `<img onclick="showImage(this)" src="./uploads/${userData.policeVari_image}" id="polImage" width="40" height="40" >`}
                    `);
                    loadURLToInputFiled(`./uploads/${userData.policeVari_image}`, "policeVari_image")


                    $('#select_floor').val(userData.floor);

                    if (userData.floor != "") {
                        handel_ajax(userData.floor);
                        $('.ground_flr').show();
                        $('.selected_room').val(userData.room_no);
                    }


                    $('#rent').val(userData.rent);
                    $('#security_deposite').val(userData.security_deposite);
                    $('#date').val(userData.date);

                    // showRooms();
                    openRegistrationModal('edit');
                }
            });

        }

//
        function deleteRecord(userId) {
            $('#disableForm')[0].reset(); // Reset the form
            $('#recordId').val(userId); // Set userId in the hidden input field
            $('#disableModal').modal('show'); // Open the modal
        }

        $('#disableSubmitButton').on('click', function() {

            var userId = $('#recordId').val();
            var disableDate = $('#disable_date').val();

            console.log(userId);
            console.log(disableDate);

            $.ajax({                   
                type: "POST",
                url: "ajax_delete_user.php",
                data: {
                    "id": userId,
                    "disable_date": disableDate
                },
                success: function(response) {

                    console.log(response);
                    if (response === "success") {
                        $('#disableModal').modal('hide');
                        window.location.href = "add_user.php";
                        location.reload();
                    } else {
                        $('#disableModal').modal('hide');
                        window.location.href = "add_user.php";
                    }
                }
            });
        });


        function closePopup() {
            $('#imagePopup').css('display', 'none');
            $('#popupObject').removeAttr('data');
            $('#popupImage').removeAttr('src');
        }

        function showImage(element) {
            let imageSrc = $(element).attr('src');
            let fileType = getFileType(imageSrc);
            if (fileType === 'pdf') {
                $('#popupObject').attr('data', imageSrc);
                $('#popupImage').hide();
                $('#popupObject').show();
            } else {
                $('#popupImage').attr('src', imageSrc);
                $('#popupObject').hide();
                $('#popupImage').show();
            }
            $('#imagePopup').css('display', 'flex');
        }

        function getFileType(filename) {
            let extension = filename.split('.').pop().toLowerCase();
            if (extension === 'pdf') {
                return 'pdf';
            } else {
                return 'image';
            }
        }



        // For Validation
        $(document).ready(function() {


            $("#registrationForm").validate({
                rules: {
                    name: {
                        required: true,
                        lettersOnly: true
                    },
                    fatherName: {
                        required: true,
                        lettersOnly: true
                    },
                    motherName: {
                        required: true,
                        lettersOnly: true
                    },
                    mobile: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        digits: true,
                        validIndianMobile: true
                    },
                    aadhar: {
                        required: true,
                        minlength: 12,
                        maxlength: 12,
                        digits: true,
                    },
                    aadhar_image: {
                        required: true
                    },
                    gender: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    emergencyContact: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        digits: true,
                        validIndianMobile: true
                    },
                    permanentAddress: {
                        required: true
                    },
                    occupation: {
                        required: true
                    },
                    rent: {
                        required: true,
                        digits: true,
                    },
                    security_deposite: {
                        required: true,
                        digits: true,
                    },
                    date: {
                        required: true
                    },
                    policeVari_image: {
                        required: true,
                        validImageFile: true
                    },
                    aadhar_image: {
                        required: true,
                        validImageFile: true
                    },
                    rent_image: {
                        required: true,
                        validImageFile: true
                    },
                    image: {
                        required: true,
                        validImageFile: true
                    },
                    date: {
                        required: true
                    },
                    select_floor: {
                        required: true
                    },
                    selected_room: {
                        isRoomSelected: true
                    }

                },
                messages: {
                    name: {
                        required: "Please enter your name",
                        lettersOnly: "Please enter only alphabetic characters"
                    },
                    fatherName: {
                        required: "Please enter your father's name"
                    },
                    motherName: {
                        required: "Please enter your mother's name"
                    },
                    mobile: {
                        required: "Please enter your mobile number",
                        minlength: "Mobile number must be 10 digits",
                        maxlength: "Mobile number must be 10 digits",
                        digits: "Please enter only digits",
                        validIndianMobile: "Please enter a valid Indian mobile number"
                    },
                    rent: {
                        required: "Please enter the rent amount"
                    },
                    aadhar: {
                        required: "Please enter the aadhar number"
                    },
                    permanentAddress: {
                        required: "Please enter the  Permanent Address"
                    },
                    emergencyContact: {
                        required: "Please enter the Emergency Contact"
                    },
                    email: {
                        required: "Please enter the email"
                    },
                    gender: {
                        required: "Please enter the gender"
                    },
                    date: {
                        required: "Please enter the date"
                    },
                    security_deposite: {
                        required: "Please enter the security deposite"
                    },
                    select_floor: {
                        required: "Please enter the select floor"
                    },
                    selected_room: {
                        isRoomSelected: "Please select a room"
                    },
                    image: {
                        required: "Please upload an image file",
                        accept: "Please upload a valid image file"
                    },
                    policeVari_image: {
                        required: "Please upload an police varification image file",
                        accept: "Please upload a valid image file"
                    },
                    rent_image: {
                        required: "Please upload an  Rent image file",
                        accept: "Please upload a valid image file"
                    },
                    aadhar_image: {
                        required: "Please upload an aadharimage file",
                        accept: "Please upload a valid image file"
                    }
                },
                errorPlacement: function(error, element) {

                    error.insertAfter(element);
                },

            });

            $.validator.addMethod("validIndianMobile", function(value, element) {
                return this.optional(element) || /^[6-9]\d{9}$/.test(value);
            }, "Please enter a valid Indian mobile number");

            $.validator.addMethod("lettersOnly", function(value, element) {
                return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
            }, "Please enter only alphabetic characters");

            $.validator.addMethod("isRoomSelected", function(value, element) {
                return $('.box.selected').val() !== '';
            }, "Please select a room");
            $.validator.addMethod("validImageFile", function(value, element) {
                var extension = value.split('.').pop().toLowerCase();
                var acceptedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
                return this.optional(element) || ($.inArray(extension, acceptedExtensions) !== -1);
            }, "Please upload a valid image file (jpg, jpeg, png, gif,pdf) only");


        });
    </script>

</body>

</html>