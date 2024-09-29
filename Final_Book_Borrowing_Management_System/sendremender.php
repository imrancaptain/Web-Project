<?php
$uname = $_REQUEST['name'];
$email = $_REQUEST['email'];
$booktitle = $_REQUEST['bookTitle'];
$loanduration = $_REQUEST['loanDuration'];

echo " Your name is {$uname}<br/> Email is {$email}<br/>  Book Title is {$booktitle}<br/>  Book Loan Duration is {$loanduration} ";


session_start();


function checkBorrowing($id, $username) {
    
    if (isset($_COOKIE['borrow_data'])) {
        $borrowData = json_decode($_COOKIE['borrow_data'], true);
        if ($borrowData['id'] === $id && $borrowData['username'] === $username) {
            $lastBorrowTime = $borrowData['timestamp'];
            $currentTime = time();
            $sevenDaysInSeconds = 7 * 24 * 60 * 60;
            
            if (($currentTime - $lastBorrowTime) < $sevenDaysInSeconds) {
                return true; 
            }
        }
    }
    return false; 
}
function setBorrowCookie($id, $username) {
    $borrowData = [
        'id' => $id,
        'username' => $username,
        'timestamp' => time()
    ];
    setcookie('borrow_data', json_encode($borrowData), time() + (7 * 24 * 60 * 60), "/"); 
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uname= $_POST['name'];
    $email = $_POST['email'];
    $booktitle = $_POST['bookTitle'];
    $loanduration = $_POST['loanDuration'];


    
    if (empty($uname) || empty($email)|| empty($booktitle)|| empty($loanduration)) {
        $error = "ID and Username are required.";
    } else {
        if (checkBorrowing($uname, $email,$booktitle,$loanduration)) {
            $message = "Please wait 7 days before borrowing again with the same ID and username.";
        } else {
            setBorrowCookie($uname, $email,$booktitle,$loanduration);
            $message = "You have successfully login!";
        }
    }
}
if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}

if (isset($message)) {
    echo "<p style='color: red;'>$message</p>";
}
if (empty($error_message)) {
    if (isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == $book_search) {
        echo "<p style='color:red'>You have already submitted this ID. Please try again after 7 days.</p>";
    } else {
        setcookie($cookie_name, $book_search, time() + (7 * 24 * 60 * 60));

        $con = mysqli_connect('localhost', 'root', '', 'sender remainder');

        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "INSERT INTO reminder (uname, email, booktitle, loanduration) VALUES ('$uname', '$email', '$booktitle', '$loanduration')";

        if (mysqli_query($con, $sql)) {
            echo "<h2>Submitted Information</h2>";
            echo "<p><strong>Book Search:</strong> $uname</p>";
            echo "<p><strong>Book Title:</strong> $email</p>";
            echo "<p><strong>Borrow Date:</strong> $booktitle</p>";
            echo "<p><strong>Return Date:</strong> $loanduration</p>";
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
