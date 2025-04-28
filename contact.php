<<<<<<< HEAD
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"] ?? ''));
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST["message"] ?? ''));

    if (empty($name) || empty($email) || empty($message)) {
        echo "Please fill in all fields.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; 
        $mail->isSMTP();                           
        $mail->Host       = 'smtp.gmail.com';                    
        $mail->SMTPAuth   = true;                               
        $mail->Username   = 'ramuparasa02@gmail.com';            
        $mail->Password   = 'hbso oieo posv uipy'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;        
        $mail->Port       = 587;                                  
        
        $mail->setFrom($email, $name);
        $mail->addAddress('ramuparasa02@gmail.com');               
        $mail->addReplyTo($email, $name);

        $mail->isHTML(false);                                
        $mail->Subject = 'New Contact Form Message from Your Website';
        $mail->Body    = "You received a new message from your website contact form:\n\n";
        $mail->Body   .= "Name: $name\n";
        $mail->Body   .= "Email: $email\n\n";
        $mail->Body   .= "Message:\n$message\n";

        if ($mail->send()) {
            echo 'Message sent successfully!';
        } else {
            echo "Email sending failed. Error (send() returned false): " . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        echo "Email sending failed. Exception caught: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
=======
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"] ?? ''));
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST["message"] ?? ''));

    if (empty($name) || empty($email) || empty($message)) {
        echo "Please fill in all fields.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    $to = "ramuparasa02@gmail.com";
    $subject = "New Contact Form Message";
    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $email_body = "You received a new message from your website contact form:\n\n";
    $email_body .= "Name: $name\n";
    $email_body .= "Email: $email\n\n";
    $email_body .= "Message:\n$message\n";

    if (mail($to, $subject, $email_body, $headers)) {
        echo "Message sent successfully!";
    } else {
        echo "Email sending failed.";
    }
} else {
    echo "Invalid request.";
}
?>
>>>>>>> ce7708e3aea7bf4ad8e2f9a8a73612b8478d9a94
