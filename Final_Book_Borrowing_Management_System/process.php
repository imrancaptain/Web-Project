<!DOCTYPE html>
<html lang="en">
<body>

<?php
$cookie_name = "student_id";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error_message = "";

    if (empty($_POST["student_name"])) {
        $error_message .= "<p>Student Name is required</p>";
    } else {
        $student_name = htmlspecialchars($_POST["student_name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $student_name)) {
            $error_message .= "<p>Only letters and white space allowed in Student Name</p>";
        }
    }

    if (empty($_POST["student_id"])) {
        $error_message .= "<p>Student ID is required</p>";
    } else {
        $student_id = htmlspecialchars($_POST["student_id"]);
        if (!preg_match("/^[0-9]{2}-[0-9]{5}-[0-9]$/", $student_id)) {
            $error_message .= "<p>Student ID must match the format xx-xxxxx-x</p>";
        }
    }

      
        
    }

    if (empty($error_message)) {
        if (isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == $student_id) {
            echo "<p style='color:red'>You have already submitted this ID. Please try again after 7 days.</p>";
        } else {
            setcookie($cookie_name, $student_id, time() + (7 * 24 * 60 * 60));

            $con = mysqli_connect('localhost', 'root', '', 'mydatabase');

            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "INSERT INTO borrow_records (student_name, student_id) VALUES ('$student_name', '$student_id')";

            if (mysqli_query($con, $sql)) {
                echo "<h2>Submitted Information</h2>";
                echo "<p><strong>Student Name:</strong> $student_name</p>";
                echo "<p><strong>Student ID:</strong> $student_id</p>";
                
            } else {
                echo "<p style='color:red'>Error: " . mysqli_error($con) . "</p>";
            }

            mysqli_close($con);
        }
    } else {
        echo "<h2>Validation Errors:</h2>";
        echo $error_message;
    }

?>

</body>
</html>
