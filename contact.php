<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact – Mineralia</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header class="site-header">
        <div class="header-inner">
            <a href="index.html" class="site-logo">
                <div class="logo-icon">💎</div>
                <span class="logo-text">Mine<span>ralia</span></span>
            </a>
            <button id="menu-btn">&#9776;</button>
            <nav class="site-nav" id="site-nav">
                <a href="index.html">Acasa</a>
                <a href="pages/cuart.html">Cuart</a>
                <a href="pages/diamant.html">Diamant</a>
                <a href="pages/calcar.html">Calcar</a>
                <a href="pages/granit.html">Granit</a>
                <a href="contact.php" class="active">Contact</a>
            </nav>
        </div>
    </header>

    <section class="mineral-hero">
        <div class="mineral-hero-inner">
            <div class="breadcrumb">
                <a href="index.html">Acasa</a>
                <span class="breadcrumb-sep">›</span>
                <span>Contact</span>
            </div>
            <div class="hero-label" style="display:inline-block;background:rgba(184,134,11,0.2);border:1px solid rgba(184,134,11,0.4);color:var(--gold-light);font-size:0.75rem;font-weight:600;letter-spacing:0.15em;text-transform:uppercase;padding:0.35rem 0.9rem;border-radius:20px;margin-bottom:1rem;">Pagina Contact</div>
            <h1>Contacteaza-ne</h1>
            <p class="mineral-hero-sub">Trimite un mesaj daca ai intrebari despre minerale si roci</p>
        </div>
    </section>

    <div class="mineral-content">
        <div class="mineral-single">
            <div class="mineral-body">

                <?php

                // --- Procesarea formularului ---

                $mesaj_succes = "";
                $erori = [];

                // Valorile campurilor (le pastram dupa submit daca sunt erori)
                $nume_val    = "";
                $email_val   = "";
                $subiect_val = "";
                $mesaj_val   = "";

                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    $nume_val    = trim($_POST["nume"]);
                    $email_val   = trim($_POST["email"]);
                    $subiect_val = trim($_POST["subiect"]);
                    $mesaj_val   = trim($_POST["mesaj"]);

                    // Validare
                    if (empty($nume_val)) {
                        $erori[] = "Numele este obligatoriu.";
                    }

                    if (empty($email_val)) {
                        $erori[] = "Email-ul este obligatoriu.";
                    } elseif (!filter_var($email_val, FILTER_VALIDATE_EMAIL)) {
                        $erori[] = "Email-ul nu este valid.";
                    }

                    if (empty($subiect_val)) {
                        $erori[] = "Subiectul este obligatoriu.";
                    }

                    if (empty($mesaj_val)) {
                        $erori[] = "Mesajul nu poate fi gol.";
                    } elseif (strlen($mesaj_val) < 10) {
                        $erori[] = "Mesajul trebuie sa aiba cel putin 10 caractere.";
                    }

                    // Daca nu sunt erori, salvam mesajul in fisier
                    if (empty($erori)) {
                        $linie = date("d.m.Y H:i") . " | " . $nume_val . " | " . $email_val . " | " . $subiect_val . "\n";
                        file_put_contents("mesaje.txt", $linie, FILE_APPEND);

                        $mesaj_succes = "Multumim, " . htmlspecialchars($nume_val) . "! Mesajul tau a fost trimis cu succes.";
                        $nume_val = $email_val = $subiect_val = $mesaj_val = "";
                    }
                }

                // Afisam mesajul de succes
                if (!empty($mesaj_succes)) {
                    echo '<div class="fact-box" style="border-color:#2e7d32;margin-bottom:1.5rem;">';
                    echo '<div class="fact-box-title" style="color:#2e7d32;">✔ ' . $mesaj_succes . '</div>';
                    echo '</div>';
                }

                // Afisam erorile daca exista
                if (!empty($erori)) {
                    echo '<div class="fact-box" style="border-color:#c62828;margin-bottom:1.5rem;">';
                    echo '<div class="fact-box-title" style="color:#c62828;">⚠ Erori in formular:</div>';
                    echo '<ul>';
                    foreach ($erori as $eroare) {
                        echo '<li>' . htmlspecialchars($eroare) . '</li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                }

                ?>

                <h2 class="char-title">Formular de Contact</h2>

                <!-- AJAX: id="form-contact-ajax" este preluat de javascript in ajax.js -->
                <!-- Formularul NU are action= sau method= – trimiterea e gestionata de AJAX -->
                <div id="contact-mesaj"></div>

                <form id="form-contact-ajax" style="display:flex;flex-direction:column;gap:1.2rem;max-width:600px;">

                    <div style="display:flex;flex-direction:column;gap:0.4rem;">
                        <label style="color:var(--stone-300);font-size:0.9rem;">Nume complet *</label>
                        <input type="text" name="nume"
                            placeholder="Ex: Ion Popescu"
                            style="padding:0.7rem 1rem;border-radius:8px;border:1px solid var(--stone-600);background:var(--stone-800);color:var(--stone-100);font-size:1rem;outline:none;">
                    </div>

                    <div style="display:flex;flex-direction:column;gap:0.4rem;">
                        <label style="color:var(--stone-300);font-size:0.9rem;">Adresa de email *</label>
                        <input type="text" name="email"
                            placeholder="Ex: ion@gmail.com"
                            style="padding:0.7rem 1rem;border-radius:8px;border:1px solid var(--stone-600);background:var(--stone-800);color:var(--stone-100);font-size:1rem;outline:none;">
                    </div>

                    <div style="display:flex;flex-direction:column;gap:0.4rem;">
                        <label style="color:var(--stone-300);font-size:0.9rem;">Subiect *</label>
                        <input type="text" name="subiect"
                            placeholder="Ex: Intrebare despre Cuart"
                            style="padding:0.7rem 1rem;border-radius:8px;border:1px solid var(--stone-600);background:var(--stone-800);color:var(--stone-100);font-size:1rem;outline:none;">
                    </div>

                    <div style="display:flex;flex-direction:column;gap:0.4rem;">
                        <label style="color:var(--stone-300);font-size:0.9rem;">Mesaj *</label>
                        <textarea name="mesaj" rows="5" placeholder="Scrie mesajul tau aici..."
                            style="padding:0.7rem 1rem;border-radius:8px;border:1px solid var(--stone-600);background:var(--stone-800);color:var(--stone-100);font-size:1rem;outline:none;resize:vertical;"></textarea>
                    </div>

                    <div>
                        <!-- id="btn-trimite-contact" permite JS sa dezactiveze butonul in timp ce se trimite -->
                        <button type="submit" id="btn-trimite-contact"
                            style="padding:0.8rem 2rem;background:var(--gold);color:#1a1612;font-weight:700;font-size:1rem;border:none;border-radius:8px;cursor:pointer;">
                            Trimite mesajul
                        </button>
                    </div>

                </form>

                <?php

                // --- Afisam mesajele salvate anterior ---

                if (file_exists("mesaje.txt")) {
                    $continut = file_get_contents("mesaje.txt");
                    $linii = array_filter(explode("\n", trim($continut)));

                    if (!empty($linii)) {
                        echo '<h2 class="char-title" style="margin-top:2.5rem;">Mesaje trimise anterior</h2>';
                        echo '<table class="props-table">';
                        echo '<thead><tr><th>Data</th><th>Nume</th><th>Email</th><th>Subiect</th></tr></thead>';
                        echo '<tbody>';
                        foreach ($linii as $linie) {
                            $parti = explode(" | ", $linie);
                            if (count($parti) == 4) {
                                echo '<tr>';
                                foreach ($parti as $parte) {
                                    echo '<td>' . htmlspecialchars(trim($parte)) . '</td>';
                                }
                                echo '</tr>';
                            }
                        }
                        echo '</tbody></table>';
                    }
                }

                ?>

                <div class="page-navigation" style="margin-top:2.5rem;">
                    <a href="index.html" class="nav-btn">
                        <span class="nav-btn-label">← Inapoi</span>
                        <span class="nav-btn-name">Pagina principala</span>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:0.5rem;">
                        <div class="logo-icon" style="width:32px;height:32px;font-size:15px;">&#x1F48E;</div>
                        <span style="font-family:'Playfair Display',serif;font-size:1.2rem;color:var(--stone-200);">Mineralia</span>
                    </div>
                    <p>Proiect educational despre mineralele si rocile din natura inconjuratoare. Lucrarea de laborator N5 - Tehnologii Web.</p>
                </div>
                <div>
                    <h4 class="footer-heading">Navigare</h4>
                    <ul class="footer-links">
                        <li><a href="index.html">Pagina principala</a></li>
                        <li><a href="pages/cuart.html">Cuart</a></li>
                        <li><a href="pages/diamant.html">Diamant</a></li>
                        <li><a href="pages/calcar.html">Calcar</a></li>
                        <li><a href="pages/granit.html">Granit</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer-heading">Tipuri de roci</h4>
                    <ul class="footer-links">
                        <li><a href="#">Roci magmatice</a></li>
                        <li><a href="#">Roci sedimentare</a></li>
                        <li><a href="#">Roci metamorfice</a></li>
                        <li><a href="#">Minerale native</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <span>&copy; 2025 Mineralia &middot; Lucrare de Laborator N5 &middot; Tehnologii Web</span>
                <div class="footer-bottom-links">
                    <a href="index.html">Acasa</a>
                    <a href="pages/cuart.html">Cuart</a>
                    <a href="pages/diamant.html">Diamant</a>
                    <a href="pages/calcar.html">Calcar</a>
                    <a href="pages/granit.html">Granit</a>
                    <a href="contact.php">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <button id="btn-sus" title="Inapoi sus">&#8593;</button>
    <script src="js/script.js"></script>
    <!-- ajax.js – Lab 5 -->
    <script src="js/ajax.js"></script>
</body>
</html>
