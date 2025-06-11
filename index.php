<?php
require 'functions.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $code = $_POST['verification_code'] ?? '';

    if (!empty($email) && empty($code)) {
        $vcode = generateVerificationCode();
        sendVerificationEmail($email, $vcode);
        file_put_contents(__DIR__ . "/codes/$email.txt", $vcode);
        $message = "Verification code sent to $email";
    }

    if (!empty($email) && !empty($code)) {
        $savedCode = @file_get_contents(__DIR__ . "/codes/$email.txt");
        if (verifyCode($code, $savedCode)) {
            registerEmail($email);
            $message = "Email verified and registered!";
            @unlink(__DIR__ . "/codes/$email.txt");
        } else {
            $message = "Invalid verification code.";
        }
    }
}
?>

<form method="POST">
    <input type="email" name="email" required placeholder="Enter email">
    <button id="submit-email">Submit</button><br><br>
    <input type="text" name="verification_code" maxlength="6" placeholder="Enter code">
    <button id="submit-verification">Verify</button>
</form>
<p><?= $message ?></p>
