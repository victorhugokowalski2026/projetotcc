<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
$mensagem_sucesso = null;
$mensagem_erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = htmlspecialchars(trim($_POST['nome'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $assunto = htmlspecialchars(trim($_POST['assunto'] ?? ''));
    $mensagem = htmlspecialchars(trim($_POST['mensagem'] ?? ''));

    if (empty($nome) || !$email || empty($assunto) || empty($mensagem)) {
        $mensagem_erro = "Por favor, preencha todos os campos corretamente.";
    } else {
        
        require 'lib/PHPMailer/Exception.php';
        require 'lib/PHPMailer/PHPMailer.php';
        require 'lib/PHPMailer/SMTP.php';

        $mail = new PHPMailer(true);

        try {
            // CONFIGURAÇÕES DO SERVIDOR GMAIL
            $mail->isSMTP();                                      
            $mail->Host       = 'smtp.gmail.com';                 // Servidor SMTP do Gmail
            $mail->SMTPAuth   = true;                             
            $mail->Username   = 'seu-email@gmail.com';            // O e-mail do Gmail do aluno
            $mail->Password   = 'abcd efgh ijkl mnop';            // A SENHA DE APP de 16 letras gerada no Google
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;      // Ativa criptografia SSL (obrigatório para o Gmail)
            $mail->Port       = 465;                              // Porta oficial do Gmail para SSL
            $mail->CharSet    = 'UTF-8';                          

            // REMETENTE E DESTINATÁRIO
            // Nota pedagógica: O Gmail força o remetente a ser o dono da conta (por segurança), 
            // mas podemos colocar o e-mail do cliente no "Reply-To" (Responder para).
            $mail->setFrom('seu-email@gmail.com', $nome);
            $mail->addAddress('seu-email@gmail.com', 'Minimal Shop'); // O e-mail que vai receber a mensagem
            $mail->addReplyTo($email, $nome);                         // Se o Admin clicar em "Responder", vai para o cliente!

            // CONTEÚDO DO E-MAIL
            $mail->isHTML(true);                                  
            $mail->Subject = "[Contato Site] - " . $assunto;      
            
            $mail->Body    = "
                <div style='font-family: sans-serif; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px;'>
                    <h2 style='color: #4f46e5;'>Nova mensagem recebida pelo site!</h2>
                    <p><strong>Nome:</strong> {$nome}</p>
                    <p><strong>E-mail do Cliente:</strong> {$email}</p>
                    <p><strong>Assunto:</strong> {$assunto}</p>
                    <hr style='border: none; border-top: 1px solid #eeeeee;'>
                    <p><strong>Mensagem:</strong></p>
                    <p style='background-color: #f9fafb; padding: 15px; border-radius: 6px;'>{$mensagem}</p>
                </div>
            ";

            $mail->send();
            $mensagem_sucesso = "Obrigado, <strong>$nome</strong>! Sua mensagem foi enviada direto para nossa caixa de entrada.";
            $_POST = array(); // Limpa o formulário
            
        } catch (Exception $e) {
            $mensagem_erro = "Erro ao enviar. Detalhes: {$mail->ErrorInfo}";
        }
    }
}
?>

<div class="max-w-5xl mx-auto my-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-sm">
        
        <div class="bg-indigo-600 p-8 md:p-12 text-white flex flex-col justify-between h-full">
            <div class="space-y-6">
                <h2 class="text-3xl font-black tracking-tight">Fale Conosco</h2>
                <p class="text-indigo-100 text-sm leading-relaxed">Tem alguma dúvida sobre compatibilidade de produtos ou deseja fazer um orçamento corporativo? Deixe sua mensagem.</p>
            </div>
            
            <div class="space-y-6 my-8 text-sm">
                <div class="flex items-center gap-4">
                    <i class="ph ph-envelope-simple text-2xl text-indigo-200"></i>
                    <span>suporte@minimal.shop</span>
                </div>
                <div class="flex items-center gap-4">
                    <i class="ph ph-phone text-2xl text-indigo-200"></i>
                    <span>+55 (11) 4003-0000</span>
                </div>
                <div class="flex items-center gap-4">
                    <i class="ph ph-map-pin text-2xl text-indigo-200"></i>
                    <span>Av. Paulista, 1000 - São Paulo, SP</span>
                </div>
            </div>

            <div class="flex gap-4 text-xl text-indigo-200">
                <a href="#" class="hover:text-white transition"><i class="ph ph-instagram-logo"></i></a>
                <a href="#" class="hover:text-white transition"><i class="ph ph-twitter-logo"></i></a>
                <a href="#" class="hover:text-white transition"><i class="ph ph-linkedin-logo"></i></a>
            </div>
        </div>

        <div class="md:col-span-2 p-8 md:p-12">
            
            <?php if ($mensagem_sucesso): ?>
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl text-sm flex items-center gap-3">
                    <i class="ph ph-check-circle text-xl text-green-600"></i>
                    <div><?php echo $mensagem_sucesso; ?></div>
                </div>
            <?php $_POST = array(); // Limpa o formulário após o sucesso ?>
            <?php endif; ?>

            <?php if ($mensagem_erro): ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl text-sm flex items-center gap-3">
                    <i class="ph ph-warning-circle text-xl text-red-600"></i>
                    <div><?php echo $mensagem_erro; ?></div>
                </div>
            <?php endif; ?>

            <form action="index.php?pg=contato" method="POST" class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label for="nome" class="text-xs font-bold text-gray-500 uppercase tracking-wider">Seu Nome</label>
                        <input type="text" id="nome" name="nome" value="<?php echo $_POST['nome'] ?? ''; ?>" required
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                    </div>
                    
                    <div class="flex flex-col gap-2">
                        <label for="email" class="text-xs font-bold text-gray-500 uppercase tracking-wider">E-mail Corporativo</label>
                        <input type="email" id="email" name="email" value="<?php echo $_POST['email'] ?? ''; ?>" required
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label for="assunto" class="text-xs font-bold text-gray-500 uppercase tracking-wider">Assunto</label>
                    <select id="assunto" name="assunto" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-indigo-600 focus:bg-white transition appearance-none">
                        <option value="">Selecione o motivo...</option>
                        <option value="Suporte Técnico" <?php echo isset($_POST['assunto']) && $_POST['assunto'] == 'Suporte Técnico' ? 'selected' : ''; ?>>Suporte Técnico</option>
                        <option value="Vendas e Parcerias" <?php echo isset($_POST['assunto']) && $_POST['assunto'] == 'Vendas e Parcerias' ? 'selected' : ''; ?>>Vendas e Parcerias</option>
                        <option value="Dúvidas Gerais" <?php echo isset($_POST['assunto']) && $_POST['assunto'] == 'Dúvidas Gerais' ? 'selected' : ''; ?>>Dúvidas Gerais</option>
                    </select>
                </div>

                <div class="flex flex-col gap-2">
                    <label for="mensagem" class="text-xs font-bold text-gray-500 uppercase tracking-wider">Como podemos ajudar?</label>
                    <textarea id="mensagem" name="mensagem" rows="5" required
                              class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-indigo-600 focus:bg-white transition resize-none"><?php echo $_POST['mensagem'] ?? ''; ?></textarea>
                </div>

                <button type="submit" 
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-100 transition duration-300 flex items-center justify-center gap-2">
                    <i class="ph ph-paper-plane-right"></i> Enviar Mensagem
                </button>
            </form>
        </div>
    </div>
</div>