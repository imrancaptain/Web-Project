<?php
$name = $_REQUEST['name'];
$email = $_REQUEST['email'];
$booktitle = $_REQUEST['bookTitle'];
$loanduration = $_REQUEST['loanDuration'];

echo " Your name is {$name}<br/> Email is {$email}<br/>  Book Title is {$booktitle}<br/>  Book Loan Duration is {$loanduration} ";


session_start();


function checkBorrowing($name, $email,$booktitle,$loanduration) {
    
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
    $name= $_POST['name'];
    $email = $_POST['email'];
    $booktitle = $_POST['bookTitle'];
    $loanduration = $_POST['loanDuration'];


    
    if (empty($name) || empty($email)|| empty($booktitle)|| empty($loanduration)) {
        $error = "ID and Username are required.";
    } else {
        if (checkBorrowing($name, $email,$booktitle,$loanduration)) {
            $message = "Please wait 7 days before borrowing again with the same ID and username.";
        } else {
            setBorrowCookie($name, $email,$booktitle,$loanduration);
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

    
?>
