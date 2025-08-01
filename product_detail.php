<?php
require 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

try {
    $query = "SELECT * FROM products WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        header("Location: products.php");
        exit;
    }
} catch(PDOException $e) {
    ?> 
    <p>Error occurred!</p>
    <p>Query: <?= $query ?></p>
    <p>Error: <?= $e->getMessage() ?></p>
    <?php
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HypeHive - <?= $product['naam'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <script src="./js/animation.js" defer></script>
    <script src="./js/mobileNav.js" defer></script>
</head>
<body class="bg-white text-black">
    <nav class="fixed top-0 w-full z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 lg:h-20">
                <div class="hidden md:flex items-center space-x-8 lg:space-x-10">
                    <a href="index.php" class="relative group font-lato text-sm lg:text-base font-medium text-black hover:text-gray-600 transition-colors duration-300">
                        <span>Home</span>
                        <div class="absolute -bottom-2 left-0 w-0 h-0.5 bg-black group-hover:w-full transition-all duration-300"></div>
                    </a>
                    <a href="products.php" class="relative group font-lato text-sm lg:text-base font-medium text-black hover:text-gray-600 transition-colors duration-300">
                        <span>Collectie</span>
                        <div class="absolute -bottom-2 left-0 w-0 h-0.5 bg-black group-hover:w-full transition-all duration-300"></div>
                    </a>
                    <a href="add_product.php" class="relative group font-lato text-sm lg:text-base font-medium text-black hover:text-gray-600 transition-colors duration-300">
                        <span>Verkopen</span>
                        <div class="absolute -bottom-2 left-0 w-0 h-0.5 bg-black group-hover:w-full transition-all duration-300"></div>
                    </a>
                    <a href="#" class="relative group font-lato text-sm lg:text-base font-medium text-black hover:text-gray-600 transition-colors duration-300">
                        <span>Over Ons</span>
                        <div class="absolute -bottom-2 left-0 w-0 h-0.5 bg-black group-hover:w-full transition-all duration-300"></div>
                    </a>
                </div>
                <div class="flex-1 flex justify-start md:flex-none">
                    <a href="index.php" class="flex items-center space-x-2">
                        <span class="font-poppins font-bold text-lg lg:text-xl text-black">HYPE</span>
                        <div class="w-0.5 h-6 bg-black"></div>
                        <span class="font-poppins font-bold text-lg lg:text-xl text-black">HIVE</span>
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-8 lg:space-x-10">
                    <a href="#" class="relative group font-lato text-sm lg:text-base font-medium text-black hover:text-gray-600 transition-colors duration-300">
                        <span>Duurzaamheid</span>
                        <div class="absolute -bottom-2 left-0 w-0 h-0.5 bg-black group-hover:w-full transition-all duration-300"></div>
                    </a>
                    <a href="#" class="relative group font-lato text-sm lg:text-base font-medium text-black hover:text-gray-600 transition-colors duration-300">
                        <span>Opening</span>
                        <div class="absolute -bottom-2 left-0 w-0 h-0.5 bg-black group-hover:w-full transition-all duration-300"></div>
                    </a>
                    <a href="#" class="relative group font-lato text-sm lg:text-base font-medium text-black hover:text-gray-600 transition-colors duration-300">
                        <span>Contact</span>
                        <div class="absolute -bottom-2 left-0 w-0 h-0.5 bg-black group-hover:w-full transition-all duration-300"></div>
                    </a>
                    <div class="flex items-center space-x-2 lg:space-x-4">
                        <button class="w-9 h-9 lg:w-10 lg:h-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors duration-300">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                        </button>
                        <button class="relative w-9 h-9 lg:w-10 lg:h-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors duration-300">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H19M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"></path>
                            </svg>
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-black text-white text-xs rounded-full flex items-center justify-center">0</span>
                        </button>
                    </div>
                </div>
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="w-10 h-10 flex flex-col items-center justify-center space-y-1.5 hover:bg-gray-100 rounded-full transition-colors duration-300">
                        <span class="w-5 h-0.5 bg-black transition-all duration-300"></span>
                        <span class="w-5 h-0.5 bg-black transition-all duration-300"></span>
                        <span class="w-5 h-0.5 bg-black transition-all duration-300"></span>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    
    <div id="mobile-menu" class="fixed inset-0 z-[999] opacity-0 invisible transition-all duration-300 ease-in-out md:hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        <div class="absolute top-0 right-0 w-full max-w-sm h-full bg-white transform translate-x-full transition-transform duration-300 ease-out">
            <div class="flex justify-between items-center p-6 border-b border-gray-100">
                <div class="flex items-center space-x-2">
                    <span class="font-poppins font-bold text-lg">HYPE</span>
                    <div class="w-0.5 h-5 bg-black"></div>
                    <span class="font-poppins font-bold text-lg">HIVE</span>
                </div>
                <button id="mobile-close-btn" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex-1 p-6">
                <nav class="space-y-4">
                    <a href="index.php" class="block py-3 font-lato text-lg text-black hover:text-gray-600 transition-colors duration-300 border-b border-gray-100">Home</a>
                    <a href="products.php" class="block py-3 font-lato text-lg text-black hover:text-gray-600 transition-colors duration-300 border-b border-gray-100">Collectie</a>
                    <a href="add_product.php" class="block py-3 font-lato text-lg text-black hover:text-gray-600 transition-colors duration-300 border-b border-gray-100">Verkopen</a>
                    <a href="#" class="block py-3 font-lato text-lg text-black hover:text-gray-600 transition-colors duration-300 border-b border-gray-100">Over Ons</a>
                    <a href="#" class="block py-3 font-lato text-lg text-black hover:text-gray-600 transition-colors duration-300 border-b border-gray-100">Duurzaamheid</a>
                    <a href="#" class="block py-3 font-lato text-lg text-black hover:text-gray-600 transition-colors duration-300 border-b border-gray-100">Opening</a>
                    <a href="#" class="block py-3 font-lato text-lg text-black hover:text-gray-600 transition-colors duration-300 border-b border-gray-100">Contact</a>
                </nav>
            </div>
        </div>
    </div>

    <section class="pt-32 pb-16 md:pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="products.php" class="flex items-center text-sm text-gray-600 hover:text-black transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Terug naar collectie
                </a>
            </div>

            <div class="flex flex-col lg:flex-row gap-12 fade-in">
                <div class="w-full lg:w-1/2">
                    <?php if (!empty($product['afbeelding'])): ?>
                        <div class="bg-gray-50 h-96 md:h-[36rem] overflow-hidden">
                            <img src="uploads/<?= $product['afbeelding'] ?>" alt="<?= $product['naam'] ?>" class="w-full h-full object-contain">
                        </div>
                    <?php else: ?>
                        <div class="w-full h-96 md:h-[36rem] bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400 font-lato text-xl">Geen afbeelding</span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="w-full lg:w-1/2">
                    <h1 class="font-poppins font-bold text-3xl sm:text-4xl md:text-5xl mb-4"><?= $product['naam'] ?></h1>
                    
                    <div class="mb-6">
                        <span class="font-bold text-2xl">€<?= ($product['prijs'] ?? 0) / 100 ?></span>
                    </div>

                    <?php if (!empty($product['maat'])): ?>
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">Maat</h3>
                            <div class="inline-block px-4 py-2 border border-black text-center">
                                <?= $product['maat'] ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($product['omschrijving'])): ?>
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-2">Omschrijving</h3>
                            <p class="text-gray-600 whitespace-pre-line"><?= $product['omschrijving'] ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="mt-8">
                        <button class="w-full mb-4 px-8 py-4 bg-black text-white border-2 border-black font-medium text-sm tracking-wide relative overflow-hidden transition-all duration-300 hover:bg-white hover:text-black hover:-translate-y-1 hover:shadow-lg group">
                            <span class="relative z-10">TOEVOEGEN AAN WINKELWAGEN</span>
                        </button>
                        <button class="w-full flex items-center justify-center px-8 py-4 border border-black font-medium text-sm tracking-wide relative overflow-hidden transition-all duration-300 hover:bg-black hover:text-white hover:-translate-y-1 hover:shadow-lg">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span>TOEVOEGEN AAN FAVORIETEN</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-white border-t border-gray-100 py-16 mt-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <div class="lg:col-span-1">
                    <a href="index.php" class="text-2xl font-semibold tracking-tighter text-black">HypeHive</a>
                    <p class="text-gray-500 mt-4 text-sm">
                        Premium mode en streetwear in Rotterdam. Duurzaam en exclusief voor de échte liefhebbers.
                    </p>
                </div>

                <div>
                    <h5 class="font-medium text-sm uppercase tracking-wider mb-5 text-gray-700">Shop</h5>
                    <ul class="space-y-3">
                        <li><a href="products.php" class="text-gray-500 hover:text-black transition-colors text-sm">Collectie</a></li>
                        <li><a href="add_product.php" class="text-gray-500 hover:text-black transition-colors text-sm">Verkopen</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-black transition-colors text-sm">Duurzaamheid</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="font-medium text-sm uppercase tracking-wider mb-5 text-gray-700">Bedrijf</h5>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-500 hover:text-black transition-colors text-sm">Over Ons</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-black transition-colors text-sm">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="font-medium text-sm uppercase tracking-wider mb-5 text-gray-700">Blijf op de hoogte</h5>
                    <form class="mt-2">
                        <div class="flex">
                            <input type="email" placeholder="E-mailadres" class="flex-1 px-4 py-2 text-sm border border-gray-200 focus:outline-none focus:ring-1 focus:ring-black">
                            <button type="submit" class="bg-black text-white px-4 py-2 text-sm">
                                <span>→</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="border-t border-gray-100 mt-16 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-xs text-gray-400 mb-4 md:mb-0">&copy; 2025 HypeHive. Alle rechten voorbehouden.</p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-black transition-colors">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script src="./js/animation.js"></script>
    <script src="./js/mobileNav.js"></script>
</body>
</html>
