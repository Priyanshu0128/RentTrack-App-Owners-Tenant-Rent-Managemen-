<?php
include("navbar.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['submit']) {

    $selectFloor = $_POST['select_floor'];
    $room_no = $_POST['room_no'];
    $room_capacity = $_POST['capacity'];

    $sql = "INSERT INTO rooms (room_no,floor,capacity) 
       VALUES ('$room_no','$selectFloor','$room_capacity')";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script>window.location='addrooms.php'</script>";
    } else {
        echo "Failed";
    }
}
?>
<?php
if (isset($_POST['update_room'])) {

    $room_id = $_POST['room_id'];

    $selectFloor = mysqli_real_escape_string($conn, $_POST['select_floor']);
    $room_no = mysqli_real_escape_string($conn, $_POST['room_no']);
    $room_capacity = mysqli_real_escape_string($conn, $_POST['capacity']);
    $update_room = "UPDATE rooms SET floor='$selectFloor', room_no='$room_no', capacity='$room_capacity' WHERE id=$room_id";

    $roomUpdate = mysqli_query($conn, $update_room);
    if ($roomUpdate) {
        echo "<script>window.location='addrooms.php'</script>";
    } else {
        echo "<script>alert('Update Failed');</script>";
    }
}

?>
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
        echo "<script>window.location='addrooms.php'</script>";
    } else {
        echo "<script>alert('Update Failed');</script>";
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Rooms</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="user_details.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <style>
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

        .head {
            display: flex;
            justify-content: space-between;
        }

        .home {
            height: 100vh;
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

        .inputBx input[type="submit"] {
            margin-top: 20px;
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

        .tablerow td {
            border: 1px solid #8080802b;
        }

        table.dataTable.no-footer {
            border: none;
        }

        .tablerow.odd:hover,
        .tablerow.even:hover {
            background-color: #60585e24;
        }

        .edit-button {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 15px;
            margin-right: 10px;
            border: none;
        }

        .edit-button i {
            font-weight: bold;
        }

        .del-button i {
            font-weight: bold;
        }

        .del-button {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 15px;
            border: none;
            color: #ff0e0b;
        }

        .user-button {
            padding: 5px 10px;
            margin-right: 20px;
            margin-bottom: 5px;
            border-radius: 5px;
            font-size: 15px;
            border: none;
            background-color: #0081ff;
            color: #ffffff;
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

        .cardsCont {
            display: flex;
            flex-direction: column;
            row-gap: 30px;
        }

        #floorFilter {
            width: 50%;
        }

        .card .card-body {
            padding: 16px 30px;
        }

        .bg-gradient-danger {
            background: linear-gradient(to right, #ffbf96, #fe7096) !important;
        }

        .card.card-img-holder .card-img-absolute {
            position: absolute;
            top: 0;
            right: 0;
            height: 100%;
        }

        .stretch-card>.card {
            width: 100%;
            min-width: 100%;
        }

        .card.card-img-holder {
            position: relative;
        }

        .bg-gradient-info {
            background: linear-gradient(to right, #90acf9, #0081ff 99%) !important;
        }

        .bg-gradient-success {
            background: linear-gradient(to right, #84d9d2, #07cdae) !important;
        }


        .card {}

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

            .modal-dialog {
                display: flex;
                justify-content: center;
            }

            .modal-content {
                width: 90%;
                top: 100px
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
                width: 80px
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

        }
    </style>
</head>

<body>
    <section class="home">
        <div class="head">
            <p>Rooms</p>
            <a class="button-1" role="button" id="openbillModel">Add Rooms</a>
        </div>
        <br>
        <div class="cardsCont">
            <div><select id="floorFilter" onchange="filterRooms()">
                    <option value="all">All Floors</option>
                    <option value="ground_floor">Ground Floor</option>
                    <option value="first_floor">First Floor</option>
                </select></div>
            <div class="mainDash">
                <div class="row" id="roomCards">
                    <?php
                    $query = "SELECT * FROM rooms";
                    $res = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($res)) {
                        $roomId = $row['id'];
                        $query1 = "SELECT COUNT(*) as userCount FROM alloted_room WHERE room_no = '$roomId'";
                        $res1 = mysqli_query($conn, $query1);
                        $userCount = mysqli_fetch_assoc($res1)['userCount'];
                    ?>
                        <div class="col-md-3 mb-3 floor-card <?= $row['floor'] ?>">
                            <div class="card card-img-holder text-dark" style="background: <?= ($userCount == 0) ? 'linear-gradient(to right, #2EDF3F,#21AF59)' : (($userCount == $row['capacity']) ? 'linear-gradient(to right,#ea6c5e,#fe4f44)' : 'linear-gradient(to right, #FEC91D, #F1B323)') ?>">
                                <div class="card-body  pt-4 px-3">
                                <!-- <img src="images/circle.svg" class="card-img-absolute" alt="circle-image" /> -->
                                    <button class="del-button" data-bs-toggle="modal" data-bs-target="#exampleModal" style="float:right;" onclick="deleteRecord(<?= $row['id']; ?>)"><i class='bx bx-trash-alt'></i></button>
                                    <button class="edit-button" style="float:right;" onclick="editRecord(<?= $row['id']; ?>)"><i class='bx bxs-edit'></i></button>
                                    <h5 class="card-title fs-6 fw-bold">Room No: <?= $row["room_no"]; ?></h5>
                                    <p class="card-text" style="font-weight:bold; font-size:12px;">Floor: <?= ucfirst(preg_replace("/[^a-zA-Z ]/", " ", $row["floor"])); ?></p>
                                    <p class="card-text" style="font-weight:bold; font-size:12px;">Capacity: <?= $row["capacity"]; ?></p>
                                    <p style="font-weight:bold; font-size:12px;">Candidate:</p>

                                    <?php
                                    $roomId = $row['id'];
                                    $query1 = "SELECT u.name,u.id FROM alloted_room ar
                                              JOIN users u ON ar.u_id = u.id
                                              WHERE ar.room_no = '$roomId'";
                                    $res1 = mysqli_query($conn, $query1);
                                    if (mysqli_num_rows($res1) > 0) {
                                        while ($allocatedRow = mysqli_fetch_assoc($res1)) {
                                            $userId = $allocatedRow['id'];
                                            $userName = $allocatedRow['name'];
                                    ?>

                                            <button class="user-button" type='button' onclick='userDetails(<?= $userId; ?>)' style="text-transform:capitalize"><?= $userName ?></button>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <p style="font-weight:bold;">No candidate in this room</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>


    <div class="modal" tabindex="-1" role="dialog" id="registrationModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registration Form</h5>
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
                                    <div id="aadharImage" style="position: absolute; top:26px; right:16px; cursor: pointer;">
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
                                    <div id="userImage" style=" position: absolute;top:30px; right:18px; cursor: pointer;"></div>
                                </div>

                                <!-- <img src="" id="userImage" width="50" height="50" > -->
                            </div>

                            <div class="inputBx">
                                <div><label for="rent_image">Rent Agreement</label></div>
                                <div>
                                    <input type="file" name="rent_image" id="rent_image" class="pad" style="position:relative">
                                    <div id="rentImage" style="position: absolute; top:30px; right:18px;  cursor: pointer;"></div>
                                </div>
                                <!-- <img src="" id="rentImage" width="50" height="50"> -->
                            </div>

                            <div class="inputBx">
                                <div> <label for="policeVari_image">Police Verification</label></div>
                                <div>
                                    <input type="file" name="policeVari_image" id="policeVari_image" class="pad" style="position:relative">
                                    <div id="polImage" style="position: absolute; top:30px; right:18px;  cursor: pointer;"></div>
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
                                    <img id="popupImage" src="" style="width:100%;">
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="billModel">
        <div class="modal-dialog modal-w-80" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tle">Add Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="billForm" method="post">
                        <input type="hidden" name="room_id" id="id">
                        <div class="row">
                            <div class="inputBx">
                                <label for="select_floor">Allot Room:</label>
                                <select id="rselect_floor" name="select_floor">
                                    <option value="" selected disabled>Select Room</option>
                                    <option value="ground_floor">Ground Floor</option>
                                    <option value="first_floor">First Floor</option>
                                </select>
                                <span id="floorError" style="color: red;"></span>
                            </div>

                            <div class="inputBx">
                                <label for="room_no">Room Name</label>
                                <input type="text" id="room_no" name="room_no" autocomplete="off"><br>
                                <span id="roomNoError" style="color: red;"></span>
                            </div>

                            <div class="inputBx">
                                <label for="capacity">Capacity</label>
                                <input type="text" id="capacity" name="capacity" autocomplete="off"><br>
                                <span id="capacityError" style="color: red;"></span>
                            </div>

                            <div class="inputBx">
                                <input type="submit" name="submit" id="rsubBtn" value="Submit">
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Room</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are You Sure you want to delete this room
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="roomToDelete">

    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Alert</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="alertMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script>
    new DataTable('#example');


    //For Modal Validation
    // document.addEventListener('DOMContentLoaded', function () {
    //     var form = document.getElementById('billForm');

    //     form.addEventListener('submit', function (event) {
    //         // event.preventDefault();

    //         var isValid = validateForm();

    //         if (isValid) {
    //             form.submit();
    //         }
    //     });

    //     function validateForm() {
    //         var isValid = true;

    //         var selectFloor = document.getElementById('rselect_floor');
    //         if (selectFloor.value === '') {
    //             isValid = false;
    //             document.getElementById('floorError').innerText = 'Please select a room floor.';
    //         }

    //         var roomNo = document.getElementById('room_no').value.trim();
    //         if (roomNo === '') {
    //             isValid = false;
    //             document.getElementById('roomNoError').innerText = 'Please enter a room name.';
    //         }

    //         var capacity = document.getElementById('capacity').value.trim();
    //         if (capacity === '') {
    //             isValid = false;
    //          document.getElementById('capacityError').innerText = 'Please enter the room capacity.';
    //         } else if (isNaN(capacity) || parseInt(capacity) <= 0) {
    //             isValid = false;
    //             document.getElementById('capacityError').innerText = 'Please enter a valid room capacity.';
    //         }

    //         return isValid;
    //     }
    // });


    //For Model Open
    function openbillModel(method) {
        if (method === 'room') {
            document.getElementById('tle').innerHTML = 'Add Rooms';
            document.getElementById('rsubBtn').setAttribute('name', 'submit');
            document.getElementById('billForm').reset();

        }
        $('#billModel').modal('show');
    }

    // Function to close the registration modal
    function closebillModel() {
        $('#billModel').modal('hide');
    }

    $('#openbillModel').on('click', openbillModel);

    $('#openbillModel').on('click', function() {
        openbillModel('room');
    });


    //Filter Rooms 
    function filterRooms() {
        var selectedFloor = document.getElementById('floorFilter').value;
        var cards = document.getElementsByClassName('floor-card');

        for (var i = 0; i < cards.length; i++) {
            var card = cards[i];
            if (selectedFloor === 'all' || card.classList.contains(selectedFloor)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        }
    }



    //For User Details
    function displayImagePopup(imageSrc) {
        // console.log('Clicked image source:', imageSrc);

        if (imageSrc !== undefined) {
            $('#popupImage').attr('src', imageSrc);
            $('#imagePopup').css('display', 'flex');
        } else {
            // console.log('Image source is undefined');
        }
    }

    function closePopup() {
        $('#imagePopup').css('display', 'none');
    }

    $('#aadharImage, #userImage, #rentImage, #polImage').on('click', function() {
        let imageSrc = $(this).attr('src');

        displayImagePopup(imageSrc);
    });

    function showImage(event) {
        $('#imagePopup').css('display', 'flex');
        $('#popupImage').attr("src", event.src);
        // popupImage
    }

    function openRegistrationModal() {
        $('#registrationModal').modal('show');
    }

    function closeRegistrationModal() {
        $('#registrationModal').modal('hide');
    }

    $('#openRegistrationModal').on('click', function() {
        openRegistrationModal();
    });

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

    function userDetails(userId) {

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
                } else {
                    $('.job-fields').show();
                }
                $('#schoolOrCollege').val(userData.schoolOrCollege);
                $('#yearOrClass').val(userData.yearOrClass);
                $('#collegeAddress').val(userData.collegeAddress);
                $('#companyName').val(userData.companyName);
                $('#jobProfile').val(userData.jobProfile);
                $('#companyAddress').val(userData.companyAddress);
                $('#aadharImage').html(`<img onclick="showImage(this)" src="./uploads/${userData.aadhar_image}" id="aadharImage" width="40" height="40" >`);
                loadURLToInputFiled(`./uploads/${userData.aadhar_image}`, "aadhar_image")
                $('#userImage').html(`<img onclick="showImage(this)"  src="./uploads/${userData.image}" id="userImage" width="40" height="40" >`);
                loadURLToInputFiled(`./uploads/${userData.image}`, "image")
                $('#rentImage').html(`<img onclick="showImage(this)"  src="./uploads/${userData.rent_image}" id="rentImage" width="40" height="40" >`);
                loadURLToInputFiled(`./uploads/${userData.rent_image}`, "rent_image")
                $('#polImage').html(`<img onclick="showImage(this)"  src="./uploads/${userData.policeVari_image}" id="polImage" width="40" height="40" >`);;
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
                openRegistrationModal();
            }
        });

    }

    //Edit Rooms
    function editRecord(id) {
        $('.modal-title').html(`Update`);
        $('#rsubBtn').attr("name", 'update_room');
        $.ajax({
            type: 'POST',
            url: 'edit_room.php',
            data: {
                "room_id": id
            },
            success: function(response) {

                var roomData = JSON.parse(response);
                console.log(roomData);
                $('#id').val(roomData.id);
                $('#rselect_floor').val(roomData.floor);
                $('#room_no').val(roomData.room_no);
                $('#capacity').val(roomData.capacity);

                $('#billForm').attr('action', '');

                openbillModel();
            },
            error: function(xhr, status, error) {
                console.log('Error fetching room data:', status, error);
            }
        });
    }

    //For Deleting the room

    function deleteRecord(roomId) {
        $('#roomToDelete').val(roomId);

        $('#exampleModal').modal('show');
    }

    function confirmDelete() {
        var roomId = $('#roomToDelete').val();

        $.ajax({
            type: "POST",
            url: "delete_room.php",
            data: {
                roomId: roomId
            },
            success: function(response) {
                let res = JSON.parse(response);
                if (res.status == "success") {
                    $('#alertModalLabel').text('Success');
                    $('#alertMessage').text(res.message);
                    location.reload();
                } else {
                    $('#alertModalLabel').text('Error');
                    $('#alertMessage').text(res.message);
                }

                // Show the modal
                $('#alertModal').modal('show');
                // setTimeout(function() {
                //    
                // }, 1000);
            },
            error: function(error) {
                console.error("Error deleting room: ", error);
            }
        });
    }
</script>

</html>