<?php
// 0. Error reporting 
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// 1. conections
// load it
require __DIR__ . '../vendor/autoload.php';
//   mail
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;
//   logs
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

// 2. Set up logs
$logDir = __DIR__ . '/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0775, true);
}
$logger = new Logger('mailer');
$logger->pushHandler(new StreamHandler($logDir . '/info.log', Level::Info));
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// 2. Set up mail
$mail = new PHPMailer(true);

// mail info
$mail->SMTPDebug = SMTP::DEBUG_OFF;

$mail->isSMTP();
$mail->Host        = $_ENV['SMTP_HOST'];
$mail->SMTPAuth    = true;
$mail->Username    = $_ENV['SMTP_USER'];
$mail->Password    = $_ENV['SMTP_PASS'];
$mail->SMTPSecure  = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port        = 587;
$mail->Timeout     = 10;
$mail->SMTPOptions = [
    'ssl' => [
        'verify_peer'       => false,
        'verify_peer_name'  => false,
        'allow_self_signed' => true,
    ],
];

// send from me
$mail->setFrom($_ENV['SMTP_USER'], 'Mailer');

// validate email
$recipient = $_SESSION['email'] ?? '';
if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
    throw new Exception('Invalid recipient email address: ' . $recipient);
}
$mail->addAddress($recipient);

// form data
$email_body     = '';
$email_alt_body = '';
$_SESSION['code'] = "blabla";

$email_body     = htmlspecialchars($_SESSION['code']);
$email_alt_body = htmlspecialchars($_SESSION['code']);


// content email
$mail->isHTML(true);
$mail->Subject = 'Nieuwe formulierinzending';
$mail->Body    = '<h3>Ingevulde formuliergegevens:</h3>' . $email_body;
$mail->AltBody = "Ingevulde formuliergegevens: " . $email_alt_body;

// sending email
$mail->send();

// when send will be logged
$logger->info('Message has been sent successfully', [
    'to'        => $recipient,
    'subject'   => $mail->Subject,
    'body'      => $mail->AltBody,
]);

echo 'Message has been sent';
?>
<form action="./login.php" method="post">
    <h2>email code</h2>
    <label for="theEmailCode">Email code</label>
    <input type="text" id="theEmailCode" name="theEmailCode" required>
    <button type="submit">Registreer</button>
</form>