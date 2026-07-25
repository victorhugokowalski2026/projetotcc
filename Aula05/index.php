<?php
    // Captura a página atual. Padrão: vitrine de produtos
    $pagina = $_GET['pg'] ?? 'produtos';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title>CArShop | Sua Loja de Tecnologia</title>
</head>
<body class="bg-gray-50 text-gray-900 flex flex-col min-h-screen font-sans antialiased">

    <header class="bg-white border-b border-gray-300 sticky top-0 z-50 backdrop-blur-md bg-white/90">
        <div class="container mx-auto px-6 h-20 flex justify-between items-center">
            
            <a href="index.php?pg=produtos" class="text-2xl font-black tracking-tight text-gray-900 flex items-center gap-0,5">
                <i class="ph ph-handbag text-indigo-600"></i> minimal<span class="text-indigo-600">.shop</span>
            </a>

            <nav>
                <ul class="flex gap-8 text-sm font-medium text-gray-600">
                    <li><a href="index.php?pg=produtos" class="hover:text-indigo-600 transition <?php echo $pagina == 'produtos' ? 'text-indigo-600 font-semibold' : ''; ?>">Produtos</a></li>
                    <li><a href="index.php?pg=sobre" class="hover:text-indigo-600 transition <?php echo $pagina == 'sobre' ? 'text-indigo-600 font-semibold' : ''; ?>">Nossa História</a></li>
                    <li><a href="index.php?pg=contato" class="hover:text-indigo-600 transition <?php echo $pagina == 'contato' ? 'text-indigo-600 font-semibold' : ''; ?>">Contato</a></li>
                </ul>
            </nav>

            <div class="flex items-center gap-4 text-xl text-gray-700">
                <button class="hover:text-indigo-600 transition"><i class="ph ph-magnifying-glass"></i></button>
                <a href="#" class="hover:text-indigo-600 transition relative">
                    <i class="ph ph-shopping-cart"></i>
                    <span class="absolute -top-2 -right-2 bg-indigo-600 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">3</span>
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-6 flex-grow py-8">
        <?php
            $arquivo = "paginas/" . $pagina . ".php";

            if (file_exists($arquivo)) {
                include($arquivo);
            } else {
                echo "
                <div class='text-center py-20'>
                    <i class='ph ph-prohibit text-6xl text-gray-300 mb-4 inline-block'></i>
                    <h1 class='text-2xl font-bold text-gray-800'>Página não encontrada!</h1>
                    <a href='index.php?pg=produtos' class='mt-4 inline-block text-indigo-600 font-medium hover:underline'>Voltar para o início</a>
                </div>";
            }
        ?>
    </main>

    <footer class="bg-white border-t border-gray-100 py-12 mt-20">
        <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-sm text-gray-500">&copy; 2026 minimal.shop. Todos os direitos reservados.</p>
            <div class="flex gap-6 text-sm text-gray-400">
                <a href="#" class="hover:text-gray-600 transition">Políticas de Privacidade</a>
                <a href="#" class="hover:text-gray-600 transition">Termos de Uso</a>
            </div>
        </div>
    </footer>

</body>
</html>