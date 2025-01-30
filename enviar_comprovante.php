<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    
    if (!$email) {
        die("<p>E-mail inválido. Tente novamente.</p>");
    }

    $uploadDir = "uploads/";
    
    // Criar diretório caso não exista
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = basename($_FILES['comprovante']['name']);
    $fileTmp = $_FILES['comprovante']['tmp_name'];
    $fileType = mime_content_type($fileTmp);
    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];

    if (!in_array($fileType, $allowedTypes)) {
        die("<p>Formato de arquivo não permitido. Envie JPG, PNG ou PDF.</p>");
    }

    // Garante que o nome do arquivo seja único
    $newFileName = uniqid("comp_", true) . "." . pathinfo($fileName, PATHINFO_EXTENSION);
    $uploadFile = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmp, $uploadFile)) {
        // Configuração do PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'seuemail@gmail.com'; // Seu e-mail Gmail
            $mail->Password = 'suasenha'; // Sua senha ou senha de aplicativo
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('seuemail@gmail.com', 'Seu Nome');
            $mail->addAddress('apksfilmeseseries@gmail.com');
            $mail->Subject = 'Novo Comprovante de Pagamento';
            $mail->Body = "E-mail do cliente: $email\n\nComprovante anexado.";
            $mail->addAttachment($uploadFile);

            $mail->send();
            echo "<p>Comprovante enviado com sucesso! Aguarde a confirmação.</p>";
        } catch (Exception $e) {
            echo "<p>Erro ao enviar e-mail: {$mail->ErrorInfo}</p>";
        }
    } else {
        echo "<p>Erro ao fazer o upload do comprovante.</p>";
    }
}
?>
