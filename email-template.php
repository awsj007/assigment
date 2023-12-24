<?php 
if(isset($_POST['submit'])){
    $to = "awaisjaved55@gmail.com"; // this is your Email address
    $from = $_POST['email']; // this is the sender's Email address
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $phone_number = $_POST['phone'];
    $email_address = $_POST['email'];
    $subject = "Form submission";
    $message = $first_name . " " . $last_name . $phone_number . $email_address;
    $headers = "From:" . $from;
    mail($to,$subject,$message,$headers);
    
    echo "Mail Sent. Thank you " . $first_name . ", we will contact you shortly.";
    // You can also use header('Location: thank_you.php'); to redirect to another page.
    // You cannot use header and echo together. It's one or the other.
    }
?>