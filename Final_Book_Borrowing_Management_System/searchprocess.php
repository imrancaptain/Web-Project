<!DOCTYPE html>
<html lang="en">
<body>

<?php
$cookie_name = "book_search";
$book_search = '';
{

    
    if (empty($_POST["book_search"])) {
        $error_message .= "<p>Book Title is required</p>";
    } else {
        $book_search = htmlspecialchars($_POST["book_search"]);
    }

    if (empty($_POST["book_title"])) {
        $error_message .= "<p>Book Title is required</p>";
    } else {
        $book_title = htmlspecialchars($_POST["book_title"]);
    }

    if (empty($_POST["borrow_date"])) {
        $error_message .= "<p>Borrow Date is required</p>";
    } else {
        $borrow_date = $_POST["borrow_date"];
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $borrow_date)) {
            $error_message .= "<p>Invalid Borrow Date format</p>";
        }
    }

    if (empty($_POST["return_date"])) {
        $error_message .= "<p>Return Date is required</p>";
    } else {
        $return_date = $_POST["return_date"];
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $return_date)) {
            $error_message .= "<p>Invalid Return Date format</p>";
        } else {
            if (strtotime($return_date) < strtotime($borrow_date)) {
                $error_message .= "<p>Return Date cannot be before Borrow Date</p>";
            }
        }
    }

    if (empty($error_message)) {
        if (isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == $book_search) {
            echo "<p style='color:red'>You have already submitted this ID. Please try again after 7 days.</p>";
        } else {
            setcookie($cookie_name, $book_search, time() + (7 * 24 * 60 * 60));

            $con = mysqli_connect('localhost', 'root', '', 'borrowdatabase');

            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "INSERT INTO borrow_info (book_search, book_title, borrow_date, return_date) VALUES ('$book_search', '$book_title', '$borrow_date', '$return_date')";

            if (mysqli_query($con, $sql)) {
                echo "<h2>Submitted Information</h2>";
                echo "<p><strong>Book Search:</strong> $book_search</p>";
                echo "<p><strong>Book Title:</strong> $book_title</p>";
                echo "<p><strong>Borrow Date:</strong> $borrow_date</p>";
                echo "<p><strong>Return Date:</strong> $return_date</p>";
            } else {
                echo "<p style='color:red'>Error: " . mysqli_error($con) . "</p>";
            }

            mysqli_close($con);
        }
    } else {
        echo "<h2>Validation Errors:</h2>";
        echo $error_message;
    }
}
?>

</body>
</html>
