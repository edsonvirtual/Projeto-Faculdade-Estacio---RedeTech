<?php
// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta os dados do formulário
    $nome = strip_tags(trim($_POST["nome"]));
    $nome = str_replace(array("\r","\n"),array(" "," "),$nome);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $assunto = trim($_POST["assunto"]);
    $mensagem = trim($_POST["mensagem"]);

    // Verifica se os dados são válidos
    if (empty($nome) || empty($mensagem) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Configura um código de resposta 400 (bad request) e exibe mensagem de erro
        http_response_code(400);
        echo "Por favor, preencha o formulário corretamente.";
        exit;
    }

    // Configura o destinatário do e-mail (substitua pelo seu e-mail)
    $destinatario = "edsonvirtual2@gmail.com";
    
    // Configura o assunto do e-mail
    $assunto_email = "Novo contato de $nome: $assunto";
    
    // Constrói o corpo do e-mail
    $corpo_email = "Nome: $nome\n";
    $corpo_email .= "E-mail: $email\n\n";
    $corpo_email .= "Mensagem:\n$mensagem\n";
    
    // Configura os cabeçalhos do e-mail
    $cabecalhos = "From: $nome <$email>";
    
    // Envia o e-mail
    if (mail($destinatario, $assunto_email, $corpo_email, $cabecalhos)) {
        // Configura um código de resposta 200 (OK) e exibe mensagem de sucesso
        http_response_code(200);
        echo "Obrigado! Sua mensagem foi enviada com sucesso.";
    } else {
        // Configura um código de resposta 500 (internal server error) e exibe mensagem de erro
        http_response_code(500);
        echo "Ops! Algo deu errado e não foi possível enviar sua mensagem.";
    }
} else {
    // Se não for método POST, configura um código de resposta 403 (forbidden)
    http_response_code(403);
    echo "Houve um problema com seu envio. Por favor, tente novamente.";
}
?>