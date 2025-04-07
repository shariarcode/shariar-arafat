<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = isset($_POST['name']) ? strip_tags(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $subject = isset($_POST['subject']) ? strip_tags(trim($_POST['subject'])) : '';
    $message = isset($_POST['message']) ? strip_tags(trim($_POST['message'])) : '';

    // Check if data is valid
    if (empty($name) || empty($message) || empty($subject) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit
        http_response_code(400);
        echo "Please complete the form and try again.";
        exit;
    }

    // Recipient email address (your email)
    $recipient = "shariararafar123@gmail.com";

    // Email headers
    $email_headers = "From: $name <$email>" . "\r\n" .
                    "Reply-To: $email" . "\r\n" .
                    "X-Mailer: PHP/" . phpversion();

    // Build the email content
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Subject: $subject\n\n";
    $email_content .= "Message:\n$message\n";

    // Send the email
    if (mail($recipient, "Website Contact: $subject", $email_content, $email_headers)) {
        // Set a 200 (okay) response code
        http_response_code(200);
        echo "Thank You! Your message has been sent.";
        
        // Redirect back to the website
        header("Location: index.html?message=success");
        exit;
    } else {
        // Set a 500 (internal server error) response code
        http_response_code(500);
        echo "Oops! Something went wrong and we couldn't send your message.";
        
        // Redirect back to the website with error
        header("Location: index.html?message=error");
        exit;
    }
} else {
    // Not a POST request, redirect to the main page
    header("Location: index.html");
    exit;
}

// Install PHPMailer via Composer or include files manually
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'shariararafar123@gmail.com';
$mail->Password = 'your-app-password'; // Generate from Google account
?> 