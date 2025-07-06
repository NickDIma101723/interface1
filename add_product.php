<?php
require 'config.php';

$errors = [];
$success = false;
$formData = [
    'naam' => '',
    'omschrijving' => '',
    'maat' => '',
    'prijs' => ''
];

if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate name
    $formData['naam'] = isset($_POST['naam']) ? trim($_POST['naam']) : '';
    if (empty($formData['naam'])) {
        $errors['naam'] = 'Naam is verplicht';
    } elseif (strlen($formData['naam']) < 2) {
        $errors['naam'] = 'Naam moet minimaal 2 karakters bevatten';
    }

    // Handle description
    $formData['omschrijving'] = isset($_POST['omschrijving']) ? trim($_POST['omschrijving']) : '';

    // Validate size
    $formData['maat'] = isset($_POST['maat']) && !empty($_POST['maat']) ? $_POST['maat'] : null;
    if (isset($_POST['maat']) && !empty($_POST['maat']) && !in_array($_POST['maat'], ['XS', 'S', 'M', 'L', 'XL'])) {
        $errors['maat'] = 'Maat moet XS, S, M, L of XL zijn';
    }
    
    // Handle and validate price
    $prijs = isset($_POST['prijs']) ? trim($_POST['prijs']) : '';
    if (empty($prijs)) {
        $errors['prijs'] = 'Prijs is verplicht';
    } else {
        // Clean price input
        $prijs = str_replace(',', '.', $prijs);
        $prijs = preg_replace('/[^0-9.]/', '', $prijs);
        
        if (!is_numeric($prijs) || floatval($prijs) <= 0) {
            $errors['prijs'] = 'Prijs moet een positief getal zijn';
        } else {
            // Convert to cents and store
            $formData['prijs'] = (int)(floatval($prijs) * 100);
        }
    }

    // Handle file upload
    $afbeelding = null;
    if (isset($_FILES['afbeelding']) && $_FILES['afbeelding']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        // Check file type
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $_FILES['afbeelding']['tmp_name']);
        finfo_close($fileInfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            $errors['afbeelding'] = 'Alleen JPG, PNG, GIF of WEBP bestanden zijn toegestaan';
        } elseif ($_FILES['afbeelding']['size'] > $maxSize) {
            $errors['afbeelding'] = 'Bestand is te groot (max 5MB)';
        } else {
            // Generate safe filename
            $extension = pathinfo($_FILES['afbeelding']['name'], PATHINFO_EXTENSION);
            $fileName = 'upload_' . uniqid() . '.' . $extension;
            $targetPath = 'uploads/' . $fileName;
            
            if (move_uploaded_file($_FILES['afbeelding']['tmp_name'], $targetPath)) {
                $afbeelding = $fileName;
            } else {
                $errors['afbeelding'] = 'Fout bij het uploaden van de afbeelding';
            }
        }
    } elseif (isset($_FILES['afbeelding']) && $_FILES['afbeelding']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errors['afbeelding'] = 'Er is een fout opgetreden bij het uploaden van het bestand';
    }
    
    if (empty($errors)) {
        try {
            $query = "INSERT INTO products (naam, omschrijving, maat, afbeelding, prijs) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$formData['naam'], $formData['omschrijving'], $formData['maat'], $afbeelding, $formData['prijs']]);
            
            $success = true;
            $formData = ['naam' => '', 'omschrijving' => '', 'maat' => '', 'prijs' => ''];
        } catch(PDOException $e) {
            $errors['general'] = "Er is een fout gebeurd bij het opslaan.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HypeHive - Verkoop je product</title>
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
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="font-poppins font-bold text-3xl sm:text-4xl md:text-5xl mb-4">Verkoop je product</h1>
                <p class="font-lato text-gray-600 max-w-2xl mx-auto">
                    Heb je premium mode items die je wilt verkopen? Voeg ze toe aan onze collectie!
                </p>
            </div>

            <?php if ($success): ?>
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                Product is succesvol toegevoegd! 
                                <a href="products.php" class="font-medium underline text-green-700 hover:text-green-600">
                                    Bekijk alle producten
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>                <?php if (!empty($errors['general'])): ?>
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                    <p class="text-red-700"><?= $errors['general'] ?></p>
                </div>
                <?php endif; ?>

            <form action="add_product.php" method="post" enctype="multipart/form-data" class="bg-white p-6 md:p-10 rounded-lg shadow-sm fade-in">
                <div class="mb-6">
                    <label for="naam" class="block text-sm font-medium text-gray-700 mb-1">Naam *</label>
                    <input type="text" id="naam" name="naam" value="<?= $formData['naam'] ?>" 
                        placeholder="Productnaam" class="w-full px-4 py-3 border <?= !empty($errors['naam']) ? 'border-red-500' : 'border-gray-300' ?> focus:border-black rounded" required>
                    <?php if (!empty($errors['naam'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= $errors['naam'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-6">
                    <label for="omschrijving" class="block text-sm font-medium text-gray-700 mb-1">Omschrijving</label>
                    <textarea id="omschrijving" name="omschrijving" placeholder="Beschrijf je product" 
                        class="w-full px-4 py-3 border <?= !empty($errors['omschrijving']) ? 'border-red-500' : 'border-gray-300' ?> focus:border-black rounded resize-none" rows="5"><?= $formData['omschrijving'] ?? '' ?></textarea>
                    <?php if (!empty($errors['omschrijving'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= $errors['omschrijving'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-6">
                    <label for="maat" class="block text-sm font-medium text-gray-700 mb-1">Maat</label>
                    <select id="maat" name="maat" class="w-full px-4 py-3 border <?= !empty($errors['maat']) ? 'border-red-500' : 'border-gray-300' ?> focus:border-black rounded">
                        <option value="">Selecteer een maat</option>
                        <option value="XS" <?= $formData['maat'] === 'XS' ? 'selected' : '' ?>>XS</option>
                        <option value="S" <?= $formData['maat'] === 'S' ? 'selected' : '' ?>>S</option>
                        <option value="M" <?= $formData['maat'] === 'M' ? 'selected' : '' ?>>M</option>
                        <option value="L" <?= $formData['maat'] === 'L' ? 'selected' : '' ?>>L</option>
                        <option value="XL" <?= $formData['maat'] === 'XL' ? 'selected' : '' ?>>XL</option>
                    </select>
                    <?php if (!empty($errors['maat'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= $errors['maat'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-6">
                    <label for="afbeelding" class="block text-sm font-medium text-gray-700 mb-1">Afbeelding</label>
                    <input type="file" id="afbeelding" name="afbeelding" accept="image/jpeg,image/png,image/gif,image/webp" 
                        class="w-full px-4 py-3 border <?= !empty($errors['afbeelding']) ? 'border-red-500' : 'border-gray-300' ?> focus:border-black rounded">
                    <p class="text-gray-500 text-xs mt-1">Toegestane formaten: JPG, PNG, GIF, WEBP</p>
                    <?php if (!empty($errors['afbeelding'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= $errors['afbeelding'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-8">
                    <label for="prijs" class="block text-sm font-medium text-gray-700 mb-1">Prijs (€) *</label>
                    <input type="number" id="prijs" name="prijs" step="0.01" min="0" 
                        value="<?= $formData['prijs'] !== '' && is_numeric($formData['prijs']) ? number_format($formData['prijs'] / 100, 2, '.', '') : '' ?>" 
                        placeholder="0.00" class="w-full px-4 py-3 border <?= !empty($errors['prijs']) ? 'border-red-500' : 'border-gray-300' ?> focus:border-black rounded" required>
                    <?php if (!empty($errors['prijs'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= $errors['prijs'] ?></p>
                    <?php endif; ?>
                </div>

                <button type="submit" class="w-full px-8 py-4 bg-black text-white border-2 border-black hover:bg-white hover:text-black mt-2">
                    PRODUCT TOEVOEGEN
                </button>
            </form>
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
</body>
</html>
