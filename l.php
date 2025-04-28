<?php
include("navbar.php");
$joinsql = "SELECT u.id, u.name, u.image, u.mobile,u.email, u.gender ,r.`room_no`, r.`floor` FROM users u
JOIN `alloted_room` ar ON u.`id` = ar.`u_id`
JOIN `rooms` r ON ar.`room_no` = r.`id`;";

$joinresult = mysqli_query($conn, $joinsql);

?>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['submit']) {

    $room_err = "";
    $selectedRoom = $_POST['selected_room'];
    $selectFloor = $_POST['select_floor'];

    $name_err = $fname_err = $mname_err = $phone_err = $aadhar_err = $email_err =  $gender_err = $emr_err = $add_err = $occupation_err =  $sch_err =  $yc_err = $clgAd_err =  $com_err = $job_err = $comAd_err = $rent_err = $sec_err = $date_err = '';

    // For Name's
    if (empty($_POST["name"])) {
        $name_err = "Name is required";
    } else {
        if (!preg_match('/^[a-zA-Z]*$/', $_POST["name"])) {
            $name_err = "Name Should only contain letters";
        }
    }

    if (empty($_POST["fatherName"])) {
        $fname_err = "Father Name is Required";
    } else {
        if (!preg_match('/^[a-zA-Z]*$/', $_POST["fatherName"])) {
            $fname_err = "Name Should only contain letters";
        }
    }

    if (empty($_POST["motherName"])) {
        $mname_err = "Mother Name is Required";
    } else {
        if (!preg_match('/^[a-zA-Z]*$/', $_POST["motherName"])) {
            $mname_err = "Name Should only contain letters";
        }
    }

    // For Mobile
    if (empty($_POST["mobile"])) {
        $phone_err = " Number is required";
    } else if (!preg_match("/^[0-9]{10}$/", $_POST["mobile"])) {
        $phone_err = "Number should only contain 10 numbers";
    } else {
        if (preg_match('/^(\d)\1+$/', $_POST["mobile"])) {
            $phone_err = "invalid";
        }
    }

    //For Aadhar No.
    if (empty($_POST["aadhar"])) {
        $aadhar_err = " Number is required";
    } else if (!preg_match("/^[0-9]{12}$/", $_POST["aadhar"])) {
        $aadhar_err = "Number should only contain 12 numbers";
    } else {
        if (preg_match('/^(\d)\1+$/', $_POST["aadhar"])) {
            $aadhar_err = "invalid";
        }
    }

    //For email
    if (empty($_POST["email"])) {
        $email_err = " Email is required";
    } else {
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $email_err = "Email is not valid";
        }
    }

    //For Gender
    if (empty($_POST["gender"])) {
        $gender_err = "Select gender";
    }

    //Emergency Contact Number
    if (empty($_POST["emergencyContact"])) {
        $emr_err = " Number is required";
    } else if (!preg_match("/^[0-9]{10}$/", $_POST["emergencyContact"])) {
        $emr_err = "Number should only contain 10 numbers";
    } else {
        if (preg_match('/^(\d)\1+$/', $_POST["emergencyContact"])) {
            $emr_err = "invalid";
        }
    }

    //For Permanent Address
    if (empty($_POST["permanentAddress"])) {
        $add_err = "Select PermanentAddress";
    }

    //For Occupation
    if (empty($_POST["occupation"])) {
        $occupation_err = "Select occupation";
    }

    //School/College Name:
    if (empty($_POST["schoolOrCollege"])) {
        $sch_err = "Select School Or College";
    }

    if (empty($_POST["yearOrClass"])) {
        $yc_err = "Select Year Or Class";
    }

    if (empty($_POST["collegeAddress"])) {
        $clgAd_err = "Select collegeAddress";
    }

    if (empty($_POST["companyName"])) {
        $com_err = "Select Company Name";
    }

    if (empty($_POST["jobProfile"])) {
        $job_err = "Select Job Profile";
    }

    if (empty($_POST["companyAddress"])) {
        $comAd_err = "Select Company Address";
    }

    //For Rent
    if (empty($_POST["rent"])) {
        $rent_err = "Select Rent";
    }

    //For Security Deposite
    if (empty($_POST["security_deposite"])) {
        $sec_err = "Select Security Deposite";
    }

    //For Date
    if (empty($_POST["date"])) {
        $date_err = "Select Date";
    }




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

            echo "<script>alert('Success'); window.location='add_user.php'</script>";
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
        echo "<script>alert('Update Success'); window.location='add_user.php'</script>";
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

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

        #customers {
            font-family: Arial, Helvetica, sans-serif;
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
        .delete-button {
            padding: 5px 10px;
            margin-right: 20px;
            border-radius: 5px;
            font-size: 12px;
            border: none;
        }

        .edit-button:hover,
        .delete-button:hover {
            background-color: #0081ff;
            color: #fff;
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
            display: none;
        }

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
    </style>
</head>

<body>

    <section class="home">
        <div class="head">
            <p>Registration Page</p>
            <a class="button-1" role="button" id="openRegistrationModal">Registration</a>
        </div>
        <br>
        <div class="mainDash">

            <table id="example" style="box-sizing: border-box; box-shadow: 0px 0px 2px 0px;background: #fff;">
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
                while ($row = $joinresult->fetch_assoc()) {
                    echo '<tr class="tablerow">';
                    $imageSrc = (!empty($row["image"])) ? 'uploads/' . $row["image"] : 'default.png';
                    echo '<td class="imgCont" >';
                    echo '<img class="user-image" src="' . $imageSrc . '" alt="" style="width:40px; height:40px; border-radius:50px;">';
                    echo '<span>' . $row["name"] . '</span>';
                    echo '</td>';
                    echo '<td>' . $row["mobile"] . '</td>';
                    echo '<td>' . $row["email"] . '</td>';
                    echo '<td>' . $row["gender"] . '</td>';
                    echo '<td>' . $row["room_no"] . '</td>';
                    echo '<td>' . $row["floor"] . '</td>';
                    echo '<td>';
                    echo '<button class=" edit-button" onclick="editRecord(' . $row["id"] . ')">Edit</button>';
                    echo '<button class=" delete-button" onclick="deleteRecord(' . $row["id"] . ')">Delete</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </table>

        </div>
    </section>

    <div class="modal" tabindex="-1" role="dialog" id="registrationModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registration Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="registrationForm" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                        <input type="hidden" name="user_id" id="userId">
                        <div class="row">



                            <div class="inputBx">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" autocomplete="off"><br>
                            </div>
                            <span class="text-danger"><?php echo "$name_err" ?></span>

                            <div class="inputBx">
                                <label for="fatherName">Father's Name:</label>
                                <input type="text" id="fatherName" name="fatherName" autocomplete="off"><br>
                            </div>
                            <span class="text-danger"><?php echo "$fname_err" ?></span>

                            <div class="inputBx">
                                <label for="motherName">Mother's Name:</label>
                                <input type="text" id="motherName" name="motherName" autocomplete="off"><br>
                            </div>
                            <span class="text-danger"><?php echo "$mname_err" ?></span>

                            <div class="inputBx">
                                <label for="mobile">Mobile Number:</label>
                                <input type="tel" id="mobile" name="mobile" autocomplete="off"><br>
                            </div>
                            <span class="text-danger"><?php echo "$phone_err" ?></span>

                            <div class="inputBx">
                                <div><label for="aadhar">Aadhar Number:</label></div>
                                <div>
                                    <input type="text" id="aadhar" name="aadhar" autocomplete="off" style="position:realtive">
                                    <input type="file" name="aadhar_image" id="aadhar_image" value="ddddd"><br>
                                    <div id="aadharImage" style="position: absolute; top:31px; right:25px; cursor: pointer;">
                                    </div>
                                </div>
                            </div>
                            <!-- <span class="text-danger"><?php echo "$aadhar_err" ?></span> -->

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
                            <span class="text-danger"><?php echo "$gender_err" ?></span>

                            <div class="inputBx">
                                <label for="email">Email ID:</label>
                                <input type="email" id="email" name="email" autocomplete="off"><br>
                            </div>
                            <!-- <span class="text-danger"><?php echo "$email_err" ?></span> -->

                            <div class="inputBx">
                                <label for="emergencyContact">Emergency Contact Number:</label>
                                <input type="tel" id="emergencyContact" name="emergencyContact" autocomplete="off"><br>
                            </div>
                            <span class="text-danger"><?php echo "$emr_err" ?></span>

                            <div class="inputBx">
                                <label for="permanentAddress">Permanent Address:</label>
                                <input type="text" id="permanentAddress" name="permanentAddress" autocomplete="off"><br>
                            </div>
                            <span class="text-danger"><?php echo "$add_err" ?></span>



                            <div class="inputBx">
                                <label for="occupation">Occupation:</label>
                                <select id="occupation" name="occupation" onchange="showFields()">
                                    <option value="" selected disabled>Select Occupation</option>
                                    <option value="student">Student</option>
                                    <option value="jobPerson">Job Person</option>
                                </select><br>
                            </div>
                            <span class="text-danger"><?php echo "$occupation_err" ?></span>



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
                                    <div id="userImage" style="position: absolute; top:35px; right:30px; cursor: pointer;"></div>
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

                            <span class="text-danger"><?php echo "$room_err" ?></span>
                            <div class="ground_flr">
                                <input type="hidden" name="selected_room" class="selected_room">
                                <div class="box_container boxrooms"></div>
                            </div>

                            <!-- <div class="first_flr"></div> -->

                            <div class="inputBx">
                                <label for="rent">Rent:</label>
                                <input type="text" id="rent" name="rent" autocomplete="off"><br>
                            </div>
                            <span class="text-danger"><?php echo "$rent_err" ?></span>

                            <div class="inputBx">
                                <label for="security_deposite">Security Deposite:</label>
                                <input type="text" id="security_deposite" name="security_deposite" autocomplete="off"><br>
                            </div>
                            <span class="text-danger"><?php echo "$sec_err" ?></span>

                            <div class="inputBx">
                                <label for="date">Date:</label>
                                <input type="date" id="date" name="date" autocomplete="off"><br>
                            </div>
                            <span class="text-danger"><?php echo "$date_err" ?></span>

                            <br>
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


    <div id="userDetailsModal" class="pop">
        <div class="popup">
            <span class="close" onclick="closeUserDetailsModal()">&times;</span>
            <div id="userDetailsContent"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>

    <script>
        new DataTable('#example');

        function openRegistrationModal(method) {
            if (method === 'register') {
                // Reset the form using the native reset method
                document.getElementById('registrationForm').reset();
                document.querySelector('.ground_flr').style.display = 'none'
                document.querySelector('.modal-title').innerHTML = 'Registration Form';
                document.getElementById('subBtn').setAttribute('name', 'submit');


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
                url: "ajax_call.php",
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
      <div class="box ${isSelected} ${occupancyStatus}" onmousedown="startLongPress('${val.room_no}')" onmouseup="cancelLongPress()" onclick="showUserDetails('${val.room_no}')">
       <div><input type="text" name="${val.room_no}" id="${val.id}" value="${val.room_no}" data-capacity="${val.room_allocation_count}" ${isDisabled} readonly></div>
     </div>
  `);
                    });

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

        // window.onclick = function(event){
        //     if(!event.target.matches('.optionNameInput')){
        //         closeDropdown('genderDropdown1');
        //     }
        // }


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
                    openRegistrationModal('edit');
                }
            });

        }



        //For User Deleting 
        function deleteRecord(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                $.ajax({
                    type: "POST",
                    url: "ajax_delete_user.php",
                    data: {
                        "id": userId
                    },
                    success: function(response) {
                        if (response === "success") {
                            alert("User deleted successfully");
                            window.location.href = "add_user.php";
                            location.reload();
                        } else {
                            alert("Failed to delete user");
                            window.location.href = "add_user.php";
                        }
                    }
                });
            }
        }


        // Function to display the image popup
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

        // function showTower(){
        //     $('')
        // }




        //For Validation
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
                        required: true
                    },
                    aadhar_image: {
                        required: true
                    },
                    rent_image: {
                        required: true
                    },
                    image: {
                        required: true
                    },
                    date: {
                        required: true
                    },
                    select_floor: {
                        required: true
                    },


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
                },
                errorPlacement: function(error, element) {
                    // Customize error placement if needed
                    error.insertAfter(element);
                }
            });

            $.validator.addMethod("validIndianMobile", function(value, element) {
                return this.optional(element) || /^[6-9]\d{9}$/.test(value);
            }, "Please enter a valid Indian mobile number");

            $.validator.addMethod("lettersOnly", function(value, element) {
                return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
            }, "Please enter only alphabetic characters");

            // $("#registrationForm").submit(function(e) {
            //     e.preventDefault();

            //     if ($(this).valid()) {
            //         var formData = new FormData(this);

            //         $.ajax({
            //             url: "ajax_validation.php",
            //             type: "POST",
            //             data: formData,
            //             contentType: false,
            //             processData: false,
            //             success: function(response) {
            //                 if (response === "success") {
            //                     alert("Form submitted successfully!");
            //                     window.location = 'add_user.php';
            //                 } else {
            //                     alert("Form not submitted!");
            //                     window.location = 'add_user.php';
            //                 }
            //             }
            //         });
            //     }
            // });
        });
    </script>

</body>

</html>

//Header Script 
 <!-- <link rel="stylesheet" href="navbar.css">  -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"> -->

    //Down Script 

     <!-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script> -->

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('billForm');

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            var isValid = validateForm();

            if (isValid) {
                form.submit();
            }
        });


        function validateForm() {
            var isValid = true;

            var selectFloor = document.getElementById('rselect_floor');
            if (selectFloor.value === '') {
                isValid = false;
                document.getElementById('roomNoError').innerText = 'Please select a room floor.';
            }

            var roomNo = document.getElementById('room_no').value.trim();
            if (roomNo === '') {
                isValid = false;
                document.getElementById('roomNoError').innerText = 'Please enter a room name.';
            }

            var capacity = document.getElementById('capacity').value.trim();
            if (capacity === '') {
                isValid = false;
             document.getElementById('capacityError').innerText = 'Please enter the room capacity.';
            } else if (isNaN(capacity) || parseInt(capacity) <= 0) {
                isValid = false;
                document.getElementById('capacityError').innerText = 'Please enter a valid room capacity.';
            }

            return isValid;
        }
    });

    //     function handel_ajax(allotroom, selectedRoom) {

//         $.ajax({
//             type: "POST",
//             url: "ajax_call.php",
//             data: {
//                 "floor": allotroom,
//                 "selectedRoom": selectedRoom
//             },
//             success: function(response) {
//                 let groundFlr = document.querySelector(".ground_flr");
//                 groundFlr.style.display = "block";

//                 let res = JSON.parse(response);
//                 // console.log(res)
//                 $('.boxrooms').empty();

//                 $.each(res, (i, val) => {

//                     let isSelected = selectedRoom && val.room_no === selectedRoom ? 'selected' : '';

//                     let occupancyStatus = determineOccupancyStatus(val.capacity, val.room_allocation_count);

//                     function determineOccupancyStatus(roomCapacity, currentOccupancy) {
//                         if (currentOccupancy == 0) {
//                             return 'vacant';
//                         } else if (currentOccupancy < roomCapacity) {
//                             return 'half-filled';
//                         } else {
//                             return 'full';
//                         }
//                     }

//                     let isDisabled = occupancyStatus === 'full' ? 'disabled' : '';

//                     $('.boxrooms').append(`
//                     <table style="font-family: arial, sans-serif;border-collapse: collapse;width: 100%;">
//                     <tr>
//                     <th>Rooms</th>
//                     <th>Incoming</th>
//                     </tr>
//                     <tr>
//                         <td>
//                         <div class="box ${isSelected} " onmousedown="startLongPress('${val.room_no}')" onmouseup="cancelLongPress()" onclick="showUserDetails('${val.room_no}')">
//                             <div><input type="text" name="${val.room_no}" id="${val.id}" style="height:35px;" value="${val.room_no}" data-capacity="${val.room_allocation_count}" ${isDisabled} readonly></div>
//                         </div>
//                      </td>
//                         <td>
//                         <input type="text" name="vacant_${val.room_no}" id="vacant_${val.id}" style="height:35px;">
//                         </td>
//                     </tr>
//                     </table>

//   `);
//                 });
//             }
//         });
//     }
</script>


<div class="col-md-3 mb-3 floor-card <?= $row['floor'] ?>" id="roomCard_<?= $row['id'] ?>">

<div class="modal-body">
                    Are You Sure you want to delete this room
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">Ok</button>
                </div>

                $incomingExpenses = $_POST['incoming_expenses'];

    // Iterate through each incoming expense and insert into the database
    foreach ($incomingExpenses as $roomId => $incomingExpense) {
        // Sanitize data before inserting into the database
        $roomId = mysqli_real_escape_string($conn, $roomId);
        $incomingExpense = mysqli_real_escape_string($conn, $incomingExpense);

        // Insert incoming expense into the database
        $sqlIncomingExpense = "INSERT INTO incoming_expenses (room_id, incoming_expense) VALUES ('$roomId', '$incomingExpense')";
        $resultIncomingExpense = mysqli_query($conn, $sqlIncomingExpense);

        // Check for success and handle accordingly
        if (!$resultIncomingExpense) {
            // Handle the error, you may want to redirect or display an error message
            echo "Error inserting incoming expense for room $roomId: " . mysqli_error($conn);
        }
    }

    <section class="home">
        <div class="head">
            <p>Electricity Bill</p>
            <a class="button-1" role="button" id="openbillModel">Bill Form</a>
        </div>
        <br>
        <div class="mainDash">
            <table id="example" style="box-sizing: border-box; box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.1);background: #fff;">
                <thead class="tablerow" id="t_data">
                    <tr>
                        <th>Bill Image</th>
                        <th>Units</th>
                        <th>Expenses</th>
                        <th>Month</th>
                        <th>Incoming</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch data from the database
                    $fetchDataQuery = "SELECT * FROM electricity_bill";
                    $result = mysqli_query($conn, $fetchDataQuery);
                    
                    while ($row = mysqli_fetch_assoc($result)) {
                        $billImage = $row['bill_image'];
                        $units = $row['units'];
                        $bill = $row['bill'];
                        $date = $row['date'];
                    
                        // Calculate total incoming expenses for this row
                        $roomId = $row['id']; // Adjust this based on your actual column name for the room ID
                        $fetchIncomingQuery = "SELECT SUM(incoming_expense) AS totalIncoming FROM incoming_expenses WHERE room_id = '$roomId'";
                        $resultIncoming = mysqli_query($conn, $fetchIncomingQuery);
                        $totalIncomingRow = mysqli_fetch_assoc($resultIncoming);
                        $totalIncoming = $totalIncomingRow['totalIncoming'];
                    
                        echo "<tr>";
                        echo "<td><img src='./uploads/$billImage' alt='Bill Image' style='width: 50px; height: 50px;'></td>";
                        echo "<td>$units</td>";
                        echo "<td>$bill</td>";
                        echo "<td>$date</td>";
                        echo "<td>$totalIncoming</td>";
                        echo "<td>Action Buttons</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>


//For Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_bill'])) {
    $roomId = mysqli_real_escape_string($conn, $_POST['room_id']);
    $units = mysqli_real_escape_string($conn, $_POST['units']);
    $bill = mysqli_real_escape_string($conn, $_POST['bill']);
    $month = mysqli_real_escape_string($conn, $_POST['month']);

    $folder = './uploads';
    $bill_imageName = $_FILES['bill_image']['name'];
    $bill_imagetmp = $_FILES['bill_image']['tmp_name'];

    if (!empty($bill_imageName)) {
        move_uploaded_file($bill_imagetmp, $folder . '/' .  $bill_imageName);
    } else {
        $selectExistingImageQuery = "SELECT `bill_image` FROM `electricity_bill` WHERE `id`='$roomId'";
        $resultExistingImage = mysqli_query($conn, $selectExistingImageQuery);
        $rowExistingImage = mysqli_fetch_assoc($resultExistingImage);
        $bill_imageName = $rowExistingImage['bill_image'];
    }

    $updateQuery = "UPDATE electricity_bill SET bill_image = '$bill_imageName', units='$units', bill='$bill', month = '$month' WHERE id='$roomId'";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {

        $incomingExpenses = $_POST['incoming_expenses'];

        foreach ($incomingExpenses as $roomToUpdate => $incomingExpense) {
            $roomIdToUpdate = mysqli_real_escape_string($conn, $roomToUpdate);
            $incomingExpenseToUpdate = mysqli_real_escape_string($conn, $incomingExpense);

            $updateIncomingQuery = "UPDATE incoming_expenses SET incoming_expense='$incomingExpenseToUpdate' WHERE room_id='$roomIdToUpdate' AND month='$month'";
            $updateIncomingResult = mysqli_query($conn, $updateIncomingQuery);

            if (!$updateIncomingResult) {
                echo json_encode(["error" => "Error updating incoming expenses for room $roomIdToUpdate"]);
                exit;
            }
        }

        echo json_encode(["success" => "Data updated successfully"]);
    } else {
        echo json_encode(["error" => "Error updating data in the database"]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}

<?php
                    $sql = "SELECT id, expenses_name FROM expenses_list";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $expenseId = $row['id'];
                            $expenseName = $row['expenses_name'];
                            echo '<span class="badge rounded-pill tag" data-id="' . $expenseId . '">' . $expenseName . '<span class="badge text-dark" onclick="hideBadge(' . $expenseId . ')">&#10060;</span> </span>';
                        }
                    } else {
                        echo "No expenses found";
                    }
                    ?>


<!-- Add jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Your existing HTML code -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- ... (your existing HTML code) ... -->
</div>

<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Expenses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-primary" id="showInputBtn" style="float:inline-end">Add</button>
                    <br><br>
                    <?php
                    $sql = "SELECT id, expenses_name FROM expenses_list";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $expenseId = $row['id'];
                            $expenseName = $row['expenses_name'];
                            echo '<span class="badge rounded-pill tag" data-id="' . $expenseId . '">' . $expenseName . '<span class="badge text-dark" onclick="hideBadge(' . $expenseId . ', \'newSubmitButtonId\')">&#10060;</span></span>';
                        }
                    } else {
                        echo "No expenses found";
                        echo "<br>";
                    }
                    ?>
                    <div id="inputSection" style="display: none;">
                        <label for="inputField">Add Exepenses:</label>
                        <input type="text" class="form-control" id="inputField">
                    </div><br>
                    <button type="button" class="btn btn-success mt-3" id="submitButton">Submit</button>
                </div>
            </div>
        </div>
    </div>

   
