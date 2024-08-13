<?php
session_start();
include 'connection.php';

// Function to generate OTP
function generateOTP() {
    $otpLength = 6; // Length of OTP
    $otp = rand(pow(10, $otpLength-1), pow(10, $otpLength)-1); // Generate random OTP
    return $otp;
}

if(isset($_POST['submit'])) {
    // Get email from form
    $email = $_POST['email'];
    $username = $_POST['username']; // Ambil nilai username dari form

    // Check if email and username exist in akun_pelanggan
    $check_email_username = mysqli_query($conn, "SELECT * FROM akun_pelanggan WHERE email = '$email' AND username = '$username' ");
    $count = mysqli_num_rows($check_email_username);

    if($count == 1) {
        // Generate OTP
        $otp = generateOTP();

        // Save OTP and email to session for verification
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $email;


        // Send email
        $to = $email;
        $subject = "Ubah Password OTP";
        $message = "$otp ini adalah kode verifikasi. Untuk keamanan, jangan sebarkan kode ini: ";
        $headers = "From: popooptikal2@gmail.com"; // Replace with your email

        // Send email
        mail($to, $subject, $message, $headers);

        // Redirect to OTP verification page
        header("Location: verify_otp.php");
        exit();
    } else {
        // Email or username not found, redirect back to forgot password page with error message
        $_SESSION['error'] = "Username and/or email not found!";
        header("Location: lupa_password.php");
        exit();
    }
}
?>
