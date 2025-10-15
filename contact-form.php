<?php
// Contact Form Handler for Hostinger Email
// This file processes the contact form and sends emails using Hostinger's email service

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize and validate input data
    $name = trim(filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING));
    $email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
    $phone = trim(filter_var($_POST['phone'] ?? '', FILTER_SANITIZE_STRING));
    $service = trim(filter_var($_POST['service'] ?? '', FILTER_SANITIZE_STRING));
    $message = trim(filter_var($_POST['message'] ?? '', FILTER_SANITIZE_STRING));
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    // If no errors, send email
    if (empty($errors)) {
        
        // Email configuration
        $to = "admin@atrizwatereco.com"; // Your Hostinger email
        $subject = "New Contact Form Submission - AtrizWaterEco";
        
        // Email body
        $email_body = "
        <html>
        <head>
            <title>New Contact Form Submission</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #223B59, #4CAF50); color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #223B59; }
                .value { margin-top: 5px; }
                .footer { background: #223B59; color: white; padding: 15px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>New Contact Form Submission</h2>
                    <p>AtrizWaterEco Website</p>
                </div>
                <div class='content'>
                    <div class='field'>
                        <div class='label'>Name:</div>
                        <div class='value'>" . htmlspecialchars($name) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>Email:</div>
                        <div class='value'>" . htmlspecialchars($email) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>Phone:</div>
                        <div class='value'>" . htmlspecialchars($phone) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>Service Interest:</div>
                        <div class='value'>" . htmlspecialchars($service) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>Message:</div>
                        <div class='value'>" . nl2br(htmlspecialchars($message)) . "</div>
                    </div>
                </div>
                <div class='footer'>
                    <p>This email was sent from the AtrizWaterEco contact form</p>
                    <p>Submitted on: " . date('Y-m-d H:i:s') . "</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Email headers
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: ' . $email,
            'Reply-To: ' . $email,
            'X-Mailer: PHP/' . phpversion()
        ];
        
        // Send email
        $mail_sent = mail($to, $subject, $email_body, implode("\r\n", $headers));
        
        if ($mail_sent) {
            // Success response
            $response = [
                'success' => true,
                'message' => 'Thank you! Your message has been sent successfully. We will get back to you soon.'
            ];
        } else {
            // Error response
            $response = [
                'success' => false,
                'message' => 'Sorry, there was an error sending your message. Please try again or contact us directly.'
            ];
        }
        
    } else {
        // Validation errors
        $response = [
            'success' => false,
            'message' => 'Please correct the following errors: ' . implode(', ', $errors)
        ];
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
    
} else {
    // If not POST request, redirect to contact page
    header('Location: contact.html');
    exit;
}
?>
