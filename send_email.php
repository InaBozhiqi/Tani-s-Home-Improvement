<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name    = htmlspecialchars(trim($_POST["name"]));
    $email   = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST["message"]));

    if (!empty($name) && !empty($email) && !empty($message) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'muraciromina@gmail.com';
            $mail->Password   = 'imrj tewe ajmv wgsk'; // Use ENV variables in production!
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('muraciromina@gmail.com', 'Romina Contact Form');
            $mail->addAddress('muraciromina@gmail.com');
            $mail->addReplyTo($email, $name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "New message from $name";

            // HTML email body with table & styling
            $mail->Body = "
            <html>
            <head>
              <style>
                body {
                  font-family: Arial, sans-serif;
                  background-color: #f6f8fa;
                  color: #333333;
                  padding: 20px;
                }
                .container {
                  background-color: #ffffff;
                  border-radius: 8px;
                  padding: 20px;
                  max-width: 600px;
                  margin: auto;
                  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                }
                h2 {
                  color: #4a90e2;
                  text-align: center;
                }
                table {
                  width: 100%;
                  border-collapse: collapse;
                  margin-top: 20px;
                }
                th, td {
                  padding: 12px 15px;
                  text-align: left;
                  border-bottom: 1px solid #ddd;
                }
                th {
                  background-color: #4a90e2;
                  color: white;
                }
                .message-cell {
                  padding-top: 15px;
                  font-style: italic;
                  white-space: pre-wrap;
                  background-color: #f9f9f9;
                }
              </style>
            </head>
            <body>
              <div class='container'>
                <h2>New Contact Form Message</h2>
                <table>
                  <tr>
                    <th>Name</th>
                    <td>" . htmlspecialchars($name) . "</td>
                  </tr>
                  <tr>
                    <th>Email</th>
                    <td>" . htmlspecialchars($email) . "</td>
                  </tr>
                  <tr>
                    <th>Message</th>
                    <td class='message-cell'>" . nl2br(htmlspecialchars($message)) . "</td>
                  </tr>
                </table>
              </div>
            </body>
            </html>
            ";

            $mail->send();
            echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Please fill out all fields correctly.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
