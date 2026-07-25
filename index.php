<?php
require_once "includes/banco_ficticio.php";

$id_produto = $_GET['id'] ?? null;
$p= buscarProdutosPorid($id_produto);

if(!$p){
    echo "
    <div class='text-center py-20'><h2 class= 'text-2x1 font-bold text-gray-800'> Produto não encontrado</h2>
    <a href= 'index.php?pg=produtos' class= 'text-indigo-600 underline ml-1 inline-block'> Voltar para a vitrine</a>
    </div>";
    exit;
}
?>

<div class="bg-white border border-gray-100 rounded-3xl p-6 md:p-10 max-w-5xl mx-auto shadow-sm">
    <a href="index.php?pg=produtos" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-600 mb-8 transition">
        <i class="ph ph-arrow-left"></i> Voltar para os produtos
    </a>

    <div class="flex flex-col md:flex-row gap-12">
        <div class="w-full md:w-1/2">
            <div class="bg-gray-50 rounded-2xl overflow-hidden aspect-square border border-gray-100/50">
                <img src="<?php echo $p['imagem']; ?>" class="w-full h-full object-cover">
            </div>
        </div>

        <div class="w-full md:w-1/2 flex flex-col justify-center">
            <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest"><?php echo $p['categoria']; ?></span>
            <h1 class="text-3xl md:text-4xl font-black tracking-tight text-gray-900 mt-2 mb-4"><?php echo $p['nome']; ?></h1>
            
            <p class="text-gray-600 text-sm md:text-base leading-relaxed mb-8">
                <?php echo $p['descricao']; ?>
            </p>

            <div class="border-y border-gray-100 py-6 mb-8 flex items-baseline gap-3">
                <span class="text-3xl font-black text-gray-900">
                    R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?>
                </span>
                <span class="text-xs text-gray-400 font-medium">no Pix ou boleto</span>
            </div>

            <div class="space-y-3">
                <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-100 transition flex items-center justify-center gap-2 text-base">
                    <i class="ph ph-shopping-bag"></i> Adicionar à Sacola
                </button>
                <button class="w-full bg-gray-50 hover:bg-gray-100 text-gray-800 font-semibold py-3 rounded-xl transition text-sm">
                    Calcular Frete e Prazo
                </button>
            </div>
        </div>
    </div>
</div>