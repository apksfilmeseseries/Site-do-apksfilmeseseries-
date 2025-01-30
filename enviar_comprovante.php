<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $uploadDir = "uploads/";
    $uploadFile = $uploadDir . basename($_FILES['comprovante']['name']);

    // Verifica se o upload foi bem-sucedido
    if (move_uploaded_file($_FILES['comprovante']['tmp_name'], $uploadFile)) {
        // Enviar e-mail para você
        $to = "apksfilmeseseries@gmail.com";
        $subject = "Novo Comprovante de Pagamento";
        $message = "E-mail do cliente: $email\nComprovante: $uploadFile";
        $headers = "From: no-reply@seudominio.com";

        if (mail($to, $subject, $message, $headers)) {
            echo "<p>Comprovante enviado com sucesso! Aguarde a confirmação.</p>";
        } else {
            echo "<p>Houve um erro ao enviar o comprovante. Tente novamente.</p>";
        }
    } else {
        echo "<p>Houve um erro ao fazer o upload do comprovante.</p>";
    }
}
?>