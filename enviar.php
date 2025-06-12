<?php
// Configurações do email
$destinatario = "edsonvirtual2@gmail.com"; // SUBSTITUA POR SEU EMAIL
$assunto_email = "Nova mensagem do formulário de contato";

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta os dados do formulário
    $nome = strip_tags(trim($_POST["nome"]));
    $nome = str_replace(array("\r","\n"),array(" "," "),$nome);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $telefone = trim($_POST["telefone"]);
    $assunto = trim($_POST["assunto"]);
    $mensagem = trim($_POST["mensagem"]);
    
    // Validação dos dados
    if (empty($nome) || empty($mensagem) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Configura resposta de erro
        http_response_code(400);
        echo "Por favor, preencha o formulário corretamente.";
        exit;
    }
    
    // Monta o corpo do email
    $corpo_email = "Nome: $nome\n";
    $corpo_email .= "Email: $email\n";
    $corpo_email .= "Telefone: $telefone\n";
    $corpo_email .= "Assunto: $assunto\n\n";
    $corpo_email .= "Mensagem:\n$mensagem\n";
    
    // Cabeçalhos do email
    $cabecalhos = "From: $nome <$email>";
    
    // Envia o email
    if (mail($destinatario, $assunto_email, $corpo_email, $cabecalhos)) {
        // Configura resposta de sucesso
        http_response_code(200);
        echo "Obrigado! Sua mensagem foi enviada com sucesso.";
    } else {
        // Configura resposta de erro no envio
        http_response_code(500);
        echo "Ops! Algo deu errado e não pudemos enviar sua mensagem.";
    }
} else {
    // Se não for método POST, configura resposta de erro
    http_response_code(403);
    echo "Houve um problema com seu envio. Por favor, tente novamente.";
}
?>