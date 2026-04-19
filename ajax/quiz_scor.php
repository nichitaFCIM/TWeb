<?php
// ================================================
// ajax/quiz_scor.php
// Primeste scorul quiz-ului prin AJAX (POST)
// Salveaza scorul si returneaza statistici JSON
// Lucrarea de laborator N5 – Tehnologii Web
// ================================================

header('Content-Type: application/json; charset=utf-8');

$raspuns = [
    'succes'       => false,
    'total_jucatori' => 0,
    'scor_mediu'   => 0,
    'cel_mai_bun'  => 0,
    'mesaj'        => ''
];

// Acceptam doar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $raspuns['mesaj'] = 'Metoda invalida.';
    echo json_encode($raspuns);
    exit;
}

// Citim scorul si numarul total de intrebari trimise
$scor_obtinut  = intval($_POST['scor']  ?? 0);
$total_intrebari = intval($_POST['total'] ?? 4);

// Validam valorile
if ($scor_obtinut < 0 || $scor_obtinut > $total_intrebari) {
    $raspuns['mesaj'] = 'Scor invalid.';
    echo json_encode($raspuns);
    exit;
}

// Fisierul unde pastram istoricul scorurilor
$cale_scoruri = __DIR__ . '/../scoruri_quiz.txt';

// Salvam scorul nou (un scor pe linie)
file_put_contents($cale_scoruri, $scor_obtinut . "\n", FILE_APPEND | LOCK_EX);

// Citim toate scorurile pentru a calcula statistici
$scoruri = [];
if (file_exists($cale_scoruri)) {
    $linii = file($cale_scoruri, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($linii as $linie) {
        $val = intval(trim($linie));
        if ($val >= 0 && $val <= $total_intrebari) {
            $scoruri[] = $val;
        }
    }
}

// Calculam statistici
$total_jucatori = count($scoruri);
$scor_mediu     = $total_jucatori > 0 ? round(array_sum($scoruri) / $total_jucatori, 1) : 0;
$cel_mai_bun    = $total_jucatori > 0 ? max($scoruri) : 0;

$raspuns['succes']          = true;
$raspuns['total_jucatori']  = $total_jucatori;
$raspuns['scor_mediu']      = $scor_mediu;
$raspuns['cel_mai_bun']     = $cel_mai_bun;
$raspuns['mesaj']           = 'Scorul a fost salvat.';

echo json_encode($raspuns, JSON_UNESCAPED_UNICODE);
?>
