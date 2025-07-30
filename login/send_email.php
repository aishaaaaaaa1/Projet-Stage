<?php
require_once 'email_system.php';

$mail = new PHPMailer(true);

try {
    // Configuration SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'aichaoutajer2@gmail.com'; 
    $mail->Password = 'bufs bbgc vdph uoxi'; 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Infos mail
    $mail->setFrom('aichaoutajer2@gmail.com', 'CampusOne');
    $mail->addAddress($email, $name);
    $mail->Subject = 'Bienvenue sur CampusOne';
    $mail->Body = "Bonjour $name,\n\nVotre inscription sur CampusOne a bien été prise en compte.\n\nMerci et bienvenue !";

    $mail->send();
    $message = "Inscription réussie ! Veuillez vous connecter. Un email de confirmation a été envoyé.";
} catch (Exception $e) {
    $message = "Inscription réussie, mais l'email n'a pas pu être envoyé. Erreur : {$mail->ErrorInfo}";
}
?>
