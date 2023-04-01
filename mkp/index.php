<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto|Sriracha&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>


<body>
    <?php

    $insert = true;
    $insert1 = false;
    if (isset($_POST['button2'])) {
        header("Location: login.php");
        exit();
    }
    if (isset($_POST['button1'])) {

        // Set connection variables
        $server = "localhost";
        $username = "root";
        $password = "";
        $dbname = "Users";
        // Create a database connection
        $con = mysqli_connect($server, $username, $password, $dbname);

        // Check for connection success
        if (!$con) {
            die("connection to this database failed due to" . mysqli_connect_error());
        }
        // echo "Success connecting to the db";
        $first_name = "";
        $last_name = "";
        $email = "";
        $password = "";
        $cnf_password = "";
        $nameErr = "";
        $emailErr = "";
        $passErr = "";
        // For Email
        if (empty($_POST["first_name"])) {
            $nameErr = "First Name Field is required";
            //echo $nameErr;
            $insert = false;
        } else {
            $first_name = ($_POST["first_name"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $first_name)) {
                $nameErr = "Only letters and white space allowed";
                //  echo $nameErr;
                $insert = false;
            }
        }
        $last_name = $_POST["last_name"];
        // For Email  
        if (empty($_POST["email"])) {
            $emailErr = "Email field is required";
            //echo $emailErr;
            $insert = false;
        } else {
            $email = ($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
                //  echo $emailErr;
                $insert = false;
            }
        }
        // For Password

        if (empty($_POST["password"])) {
            $passErr = "Password Cant be Empty!";
            $insert = false;
        } else {
            $password = $_POST["password"];
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number    = preg_match('@[0-9]@', $password);
            $specialChars = preg_match('@[^\w]@', $password);

            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
                $passErr = "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
                // echo $passErr;
                $insert = false;
            } else {
            }
        }
        $cnfErr = "";
        // For Confirm Password
        $cnf_password = $_POST["cnf_password"];
        if ($cnf_password != $password) {
            $insert = false;
            $cnfErr = "Passwords Does'nt Match!";
            //echo $cnfErr;
        }

        $userErr = "";
        if ($insert) {

            $check = mysqli_query($con, "SELECT * FROM users WHERE email= '$email' ");
            if (mysqli_num_rows($check) > 0) {
                $userErr = "User With Same Email Already Exists!";
                echo "<script>alert('$userErr')</script>";
            } else {

                $sql = "INSERT INTO users (first_name,last_name,email,password) VALUES ('$first_name', '$last_name','$email','$password')";
                if (mysqli_query($con, $sql)) {
                    $insert1 = true;
                } else {

                    echo "ERROR: Hush! Sorry $sql. "
                        . mysqli_error($con);
                }
            }
            // $con->close();

        } else {

            if (!empty($nameErr)) {
                echo "<script>alert('$nameErr')</script>";
            } else if (!empty($emailErr)) {
                echo "<script>alert('$emailErr')</script>";
            } else if (!empty($passErr)) {
                echo "<script>alert('$passErr')</script>";
            } else if (!empty($cnfErr)) {
                echo "<script>alert('$cnfErr')</script>";
            }
        }

        $con->close();
    }


    ?>



    <div class="container">
        <h1>User Registration</h3>
            <p>Enter your details and submit this form to Register </p>
            <?php
            if ($insert1 == true) {
                echo "<p class='submitMsg'>Thanks for submitting your form.</p>";
            } ?>
            <form method="post">
                <input type="text" name="first_name" id="first_name" placeholder="Enter your First Name">
                <input type="text" name="last_name" id="last_name" placeholder="Enter your Last Name">
                <input type="email" name="email" id="email" placeholder="Enter your email">
                <input type="password" name="password" id="password" placeholder="Enter your Password">
                <input type="password" name="cnf_password" id="cnf_password" placeholder="Confirm Your Password">
                <input type="submit" name="button1" value="Register">
                <input type="submit" name="button2" value="Login">
            </form>
    </div>
    <script src="index.js"></script>

</body>

</html>