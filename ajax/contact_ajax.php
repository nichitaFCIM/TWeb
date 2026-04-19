<?php
// ================================================
// ajax/contact_ajax.php
// Proceseaza formularul de contact prin AJAX
// Returneaza raspuns in format JSON
// Lucrarea de laborator N5 – Tehnologii Web
// ================================================

// Setam header-ul pentru a indica ca raspundem cu JSON
header('Content-Type: application/json; charset=utf-8');

// Initializam raspunsul
$raspuns = [
    'succes' => false,
    'mesaj'  => '',
    'erori'  => []
];

// Verificam ca cererea vine prin metoda POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $raspuns['mesaj'] = 'Metoda de cerere invalida.';
    echo json_encode($raspuns);
    exit;
}

// Citim si curatam datele primite
$nume    = trim($_POST['nume']    ?? '');
$email   = trim($_POST['email']   ?? '');
$subiect = trim($_POST['subiect'] ?? '');
$mesaj   = trim($_POST['mesaj']   ?? '');

// Validare camp Nume
if (empty($nume)) {
    $raspuns['erori'][] = 'Numele este obligatoriu.';
}

// Validare camp Email
if (empty($email)) {
    $raspuns['erori'][] = 'Email-ul este obligatoriu.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $raspuns['erori'][] = 'Email-ul nu este valid.';
}

// Validare camp Subiect
if (empty($subiect)) {
    $raspuns['erori'][] = 'Subiectul este obligatoriu.';
}

// Validare camp Mesaj
if (empty($mesaj)) {
    $raspuns['erori'][] = 'Mesajul nu poate fi gol.';
} elseif (strlen($mesaj) < 10) {
    $raspuns['erori'][] = 'Mesajul trebuie sa aiba cel putin 10 caractere.';
}

// Daca nu sunt erori, salvam mesajul si returnam succes
if (empty($raspuns['erori'])) {
    $linie = date('d.m.Y H:i') . ' | ' . $nume . ' | ' . $email . ' | ' . $subiect . "\n";
    
    // Salvam in fisierul de mesaje (un nivel mai sus fata de folderul ajax/)
    $cale_fisier = __DIR__ . '/../mesaje.txt';
    file_put_contents($cale_fisier, $linie, FILE_APPEND | LOCK_EX);

    $raspuns['succes'] = true;
    $raspuns['mesaj']  = 'Multumim, ' . htmlspecialchars($nume) . '! Mesajul tau a fost trimis cu succes.';
} else {
    $raspuns['mesaj'] = 'Formularul contine erori. Verifica campurile marcate.';
}

// Trimitem raspunsul JSON inapoi catre JavaScript
echo json_encode($raspuns, JSON_UNESCAPED_UNICODE);
?>
