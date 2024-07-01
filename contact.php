<?php
session_start();

// Configure
$from = 'Nova mensagem de contato <leonardo@lgservicosengenharia.com.br>';
$sendTo = 'Contato <S.engenharialg@gmail.com>'; // Add Your Email
$subject = 'Nova mensagem do formulário de contato';
$fields = array('name' => 'Nome', 'subject' => 'Assunto', 'email' => 'E-mail', 'message' => 'Mensagem'); // array variable name => Text to appear in the email
$okMessage = 'Formulário enviado com sucesso. Obrigado, em breve retornaremos o contato. :)';
$errorMessage = 'Ocorreu um erro ao enviar o formulário. Por favor, tente novamente mais tarde.';

// Allow CORS for all origins (for testing)
header("Access-Control-Allow-Origin: *");

try {
    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('Método de solicitação inválido');
    }

    // Build the email text
    $emailText = "Você tem uma nova mensagem do formulário para contato!\n=============================\n";

    foreach ($_POST as $key => $value) {
        if (isset($fields[$key])) {
            $emailText .= "$fields[$key]: $value\n";
        }
    }

    $headers = array(
        'Content-Type: text/plain; charset="UTF-8";',
        'From: ' . $from,
        'Reply-To: ' . $from,
        'Return-Path: ' . $from,
    );

    if (!mail($sendTo, $subject, $emailText, implode("\n", $headers))) {
        throw new Exception('Falha ao enviar o email');
    }

    $responseArray = array('type' => 'success', 'message' => $okMessage);
} catch (Exception $e) {
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($responseArray);
} else {
    echo $responseArray['message'];
}
?>
