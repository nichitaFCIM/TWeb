<?php
// ================================================
// ajax/cauta_mineral.php
// Primeste un termen de cautare prin AJAX (GET)
// Returneaza mineralele potrivite in format JSON
// Lucrarea de laborator N5 – Tehnologii Web
// ================================================

header('Content-Type: application/json; charset=utf-8');

// Baza de date simpla cu mineralele din site
// (In proiecte reale, asta ar veni dintr-o baza de date SQL)
$minerale = [
    [
        'nume'     => 'Cuarț',
        'tip'      => 'Mineral',
        'formula'  => 'SiO₂',
        'duritate' => 7,
        'culoare'  => 'Variată (incolor, alb, roz, violet)',
        'utilizare'=> 'Sticlă, electronice, bijuterii',
        'link'     => 'pages/cuart.html',
        'imagine'  => 'images/cuart.png',
        'desc'     => 'Cel mai răspândit mineral din scoarța terestră.'
    ],
    [
        'nume'     => 'Diamant',
        'tip'      => 'Mineral',
        'formula'  => 'C',
        'duritate' => 10,
        'culoare'  => 'Incolor, galben, roz, albastru',
        'utilizare'=> 'Bijuterii, unelte de tăiere',
        'link'     => 'pages/diamant.html',
        'imagine'  => 'images/diamant.png',
        'desc'     => 'Cea mai dură substanță naturală, formă cristalină a carbonului.'
    ],
    [
        'nume'     => 'Calcar',
        'tip'      => 'Rocă sedimentară',
        'formula'  => 'CaCO₃',
        'duritate' => 3,
        'culoare'  => 'Alb, gri, bej',
        'utilizare'=> 'Ciment, construcții, var',
        'link'     => 'pages/calcar.html',
        'imagine'  => 'images/calcar.png',
        'desc'     => 'Rocă sedimentară compusă în principal din calcit.'
    ],
    [
        'nume'     => 'Granit',
        'tip'      => 'Rocă magmatică',
        'formula'  => 'SiO₂ + feldspat + mică',
        'duritate' => 6.5,
        'culoare'  => 'Gri, roz, alb cu pete',
        'utilizare'=> 'Plăci decorative, monumente',
        'link'     => 'pages/granit.html',
        'imagine'  => 'images/granit.png',
        'desc'     => 'Rocă magmatică intruzivă, rezistentă și decorativă.'
    ]
];

// Citim termenul de cautare din parametrul GET
$termen = strtolower(trim($_GET['q'] ?? ''));

// Daca nu exista termen, returnam toate mineralele
if (empty($termen)) {
    echo json_encode(['rezultate' => $minerale, 'numar' => count($minerale)], JSON_UNESCAPED_UNICODE);
    exit;
}

// Filtram mineralele care contin termenul cautat
$rezultate = [];
foreach ($minerale as $mineral) {
    // Cautam in mai multe campuri simultan
    $text_de_cautat = strtolower(
        $mineral['nume'] . ' ' .
        $mineral['tip']  . ' ' .
        $mineral['formula'] . ' ' .
        $mineral['culoare'] . ' ' .
        $mineral['utilizare'] . ' ' .
        $mineral['desc']
    );

    if (strpos($text_de_cautat, $termen) !== false) {
        $rezultate[] = $mineral;
    }
}

// Returnam rezultatele gasite
echo json_encode([
    'rezultate' => $rezultate,
    'numar'     => count($rezultate),
    'termen'    => htmlspecialchars($termen)
], JSON_UNESCAPED_UNICODE);
?>
