<?php 
    require_once "includes/banco_ficticio.php";
    $produtos = listarProdutos();

    //Captura se o usuario clicou em alguma categoria no menu lateral
    $categoria_selecionada = $_GET["cat"] ?? null;

    // Se houver categoria selecionada, filtramos o array original
    if ($categoria_selecionada) {
        $produtos = array_filter($produtos, function ($p) use ($categoria_selecionada) {
            return $p["categoria"] == $categoria_selecionada;
        } );
    }
?>
<div class="bg-indigo-50 rounded-3xl p-8 md:p-12 flex flex-col md:flex-row items-center justify-between mb-12 border border-indigo-100/50">
    <div class="max-w-md">
        <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest bg-indigo-100 px-3 py-1 rounded-full">Nova Coleção</span>
        <h2 class="text-3xl md:text-4xl font-black tracking-tight text-gray-900 mt-4 mb-2">Simplicidade & Performance</h2>
        <p class="text-gray-600 text-sm md:text-base mb-6">Equipamentos selecionados para elevar a estética e a produtividade do seu workspace.</p>
    </div>
    <div class="hidden md:block w-1/3">
        <img src="https://images.unsplash.com/photo-1585776245991-cf89dd7fc73a?w=500" class="rounded-2xl mix-blend-multiply">
    </div>
</div>

<div class="flex flex-col md:flex-row gap-8">
    
    <aside class="w-full md:w-1/4 shrink-0">
        <div class="bg-white border border-gray-100 rounded-2xl p-6 sticky top-24">
            <h3 class="font-bold text-gray-900 text-base mb-4 flex items-center gap-2">
                <i class="ph ph-sliders-horizontal text-indigo-600"></i> Categorias
            </h3>
            
            <ul class="space-y-2 text-sm text-gray-600">
                <li>
                    <a href="index.php?pg=produtos" class="flex justify-between items-center py-2 px-3 rounded-xl transition <?php echo !$categoria_selecionada ? 'bg-indigo-50 text-indigo-600 font-bold' : 'hover:bg-gray-50'; ?>">
                        <span>Todos os Produtos</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?pg=produtos&cat=Periféricos" class="flex justify-between items-center py-2 px-3 rounded-xl transition <?php echo $categoria_selecionada == 'Periféricos' ? 'bg-indigo-50 text-indigo-600 font-bold' : 'hover:bg-gray-50'; ?>">
                        <span>Periféricos</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?pg=produtos&cat=Armazenamento" class="flex justify-between items-center py-2 px-3 rounded-xl transition <?php echo $categoria_selecionada == 'Periféricos' ? 'bg-indigo-50 text-indigo-600 font-bold' : 'hover:bg-gray-50'; ?>">
                        <span>Armazenamento</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?pg=produtos&cat=Notebooks" class="flex justify-between items-center py-2 px-3 rounded-xl transition <?php echo $categoria_selecionada == 'Periféricos' ? 'bg-indigo-50 text-indigo-600 font-bold' : 'hover:bg-gray-50'; ?>">
                        <span>Notebooks</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?pg=produtos&cat=Áudio" class="flex justify-between items-center py-2 px-3 rounded-xl transition <?php echo $categoria_selecionada == 'Áudio' ? 'bg-indigo-50 text-indigo-600 font-bold' : 'hover:bg-gray-50'; ?>">
                        <span>Áudio</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <section class="w-full md:w-3/4">
        
        <?php if(empty($produtos)): ?>
            <div class="text-center py-12 bg-white rounded-2xl border border-gray-100">
                <p class="text-gray-500">Nenhum produto encontrado nesta categoria.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($produtos as $p): ?>
                    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden group hover:shadow-xl hover:border-transparent transition-all duration-300 flex flex-col h-full">
                        
                        <div class="relative overflow-hidden bg-gray-50 aspect-video md:aspect-square">
                            <img src="<?php echo $p['imagem']; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-gray-800 text-[11px] font-bold px-2.5 py-1 rounded-full shadow-sm border border-gray-100">
                                <?php echo $p['categoria']; ?>
                            </span>
                        </div>

                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="font-bold text-gray-900 group-hover:text-indigo-600 transition text-base line-clamp-1">
                                <?php echo $p['nome']; ?>
                            </h3>
                            <p class="text-gray-500 text-xs mt-2 line-clamp-2 leading-relaxed">
                                <?php echo $p['descricao']; ?>
                            </p>
                            
                            <div class="mt-5 pt-4 border-t border-gray-50 flex items-center justify-between mt-auto">
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">A partir de</span>
                                    <span class="text-lg font-black text-gray-900">
                                        R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?>
                                    </span>
                                </div>
                                <a href="index.php?pg=detalhe&id=<?php echo $p['id']; ?>" class="bg-gray-900 hover:bg-indigo-600 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm flex items-center gap-1">
                                    Ver <i class="ph ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </section>
</div>