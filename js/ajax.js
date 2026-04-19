// ================================================
// js/ajax.js – AJAX pentru site-ul Mineralia
// Lucrarea de laborator N5 – Tehnologii Web
// ================================================
// Ce este AJAX?
// AJAX = Asynchronous JavaScript And XML
// Permite trimiterea si primirea de date de la server
// FARA a reincarca pagina. Comunicarea se face in fundal,
// iar utilizatorul nu vede nicio intrerupere vizuala.
//
// Flux AJAX:
//   1. Utilizatorul face o actiune (apasa buton, scrie in input)
//   2. JavaScript trimite o cerere HTTP catre un fisier PHP
//   3. PHP proceseaza cererea si trimite inapoi date (JSON)
//   4. JavaScript primeste datele si actualizeaza pagina
// ================================================


// ------------------------------------------------
// AJAX 1: FORMULAR CONTACT – trimitere fara refresh
// ------------------------------------------------
// Gasim formularul de contact pe pagina contact.php
var formContact = document.getElementById('form-contact-ajax');

if (formContact) {

    formContact.addEventListener('submit', function (eveniment) {

        // Oprim comportamentul implicit al formularului (reincarcarea paginii)
        eveniment.preventDefault();

        // Culegem datele din formular
        var date = new FormData(formContact);

        // Referinta la zona unde afisam mesaje de succes/eroare
        var zonaMesaj = document.getElementById('contact-mesaj');
        var butonTrimite = document.getElementById('btn-trimite-contact');

        // Dezactivam butonul si afisam starea de trimitere
        butonTrimite.disabled = true;
        butonTrimite.textContent = 'Se trimite...';
        zonaMesaj.innerHTML = '';
        zonaMesaj.className = '';

        // *** CEREREA AJAX cu XMLHttpRequest ***
        var xhr = new XMLHttpRequest();

        // Deschidem conexiunea: metoda POST catre fisierul PHP
        xhr.open('POST', 'ajax/contact_ajax.php', true);

        // Functie apelata cand serverul ne raspunde
        xhr.onload = function () {

            // Reactivam butonul indiferent de rezultat
            butonTrimite.disabled = false;
            butonTrimite.textContent = 'Trimite mesajul';

            if (xhr.status === 200) {
                // Parsam raspunsul JSON primit de la PHP
                var raspuns = JSON.parse(xhr.responseText);

                if (raspuns.succes) {
                    // Succes: curatam formularul si afisam mesaj verde
                    formContact.reset();
                    zonaMesaj.innerHTML =
                        '<div class="ajax-mesaj ajax-succes">✔ ' + raspuns.mesaj + '</div>';
                } else {
                    // Erori: afisam lista de erori rosii
                    var htmlErori = '<div class="ajax-mesaj ajax-eroare"><strong>⚠ ' + raspuns.mesaj + '</strong><ul>';
                    raspuns.erori.forEach(function (eroare) {
                        htmlErori += '<li>' + eroare + '</li>';
                    });
                    htmlErori += '</ul></div>';
                    zonaMesaj.innerHTML = htmlErori;
                }
            } else {
                zonaMesaj.innerHTML = '<div class="ajax-mesaj ajax-eroare">Eroare de server. Incearca din nou.</div>';
            }
        };

        // Functie apelata daca conexiunea esueaza complet
        xhr.onerror = function () {
            butonTrimite.disabled = false;
            butonTrimite.textContent = 'Trimite mesajul';
            zonaMesaj.innerHTML = '<div class="ajax-mesaj ajax-eroare">Nu s-a putut conecta la server.</div>';
        };

        // Trimitem datele formularului la PHP
        xhr.send(date);
    });
}


// ------------------------------------------------
// AJAX 2: QUIZ SCOR – statistici trimise la server
// ------------------------------------------------
// Aceasta functie este apelata din script.js cand quiz-ul s-a terminat.
// Trimite scorul la PHP si primeste inapoi statistici globale.

function trimiteScorQuiz(scor, total) {

    var date = new FormData();
    date.append('scor', scor);
    date.append('total', total);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/quiz_scor.php', true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            var stats = JSON.parse(xhr.responseText);

            if (stats.succes) {
                // Afisam statisticile in sectiunea de rezultat a quiz-ului
                var statBox = document.getElementById('quiz-statistici');
                if (statBox) {
                    statBox.innerHTML =
                        '<div class="quiz-stats">' +
                        '<h4>📊 Statistici Globale (AJAX)</h4>' +
                        '<div class="stats-grid">' +
                        '<div class="stat-item"><span class="stat-num">' + stats.total_jucatori + '</span><span class="stat-label">Jucători</span></div>' +
                        '<div class="stat-item"><span class="stat-num">' + stats.scor_mediu + '</span><span class="stat-label">Scor mediu</span></div>' +
                        '<div class="stat-item"><span class="stat-num">' + stats.cel_mai_bun + '/4</span><span class="stat-label">Cel mai bun</span></div>' +
                        '</div>' +
                        '</div>';
                    statBox.style.display = 'block';
                }
            }
        }
    };

    xhr.send(date);
}


// ------------------------------------------------
// AJAX 3: CAUTARE MINERALE LIVE
// ------------------------------------------------
// La fiecare tasta apasata in campul de cautare,
// trimitem o cerere GET la PHP si actualizam rezultatele.

var inputCautare = document.getElementById('cautare-mineral');
var zonaCautare  = document.getElementById('rezultate-cautare');

if (inputCautare && zonaCautare) {

    // Variabila pentru a evita cereri prea dese (debounce)
    var timerCautare = null;

    inputCautare.addEventListener('input', function () {
        var termen = this.value.trim();

        // Stergem timer-ul anterior (debounce: asteptam 300ms dupa ultima tasta)
        clearTimeout(timerCautare);

        // Daca campul e gol, ascundem rezultatele
        if (termen.length === 0) {
            zonaCautare.innerHTML = '';
            zonaCautare.style.display = 'none';
            return;
        }

        // Asteptam 300ms inainte de a trimite cererea
        timerCautare = setTimeout(function () {

            // Afisam starea de incarcare
            zonaCautare.style.display = 'block';
            zonaCautare.innerHTML = '<p class="cautare-loading">🔍 Se cauta...</p>';

            // Cerere AJAX GET cu termenul de cautare in URL
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'ajax/cauta_mineral.php?q=' + encodeURIComponent(termen), true);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    var date = JSON.parse(xhr.responseText);
                    afiseazaRezultateCautare(date);
                }
            };

            xhr.send();

        }, 300);
    });

    // Inchidem rezultatele cand facem click in alta parte
    document.addEventListener('click', function (e) {
        if (!inputCautare.contains(e.target) && !zonaCautare.contains(e.target)) {
            zonaCautare.style.display = 'none';
        }
    });

    inputCautare.addEventListener('focus', function () {
        if (this.value.trim().length > 0 && zonaCautare.innerHTML !== '') {
            zonaCautare.style.display = 'block';
        }
    });
}

// Functie auxiliara: construieste HTML-ul cu rezultatele cautarii
function afiseazaRezultateCautare(date) {
    if (!zonaCautare) return;

    if (date.rezultate.length === 0) {
        zonaCautare.innerHTML =
            '<p class="cautare-gol">Niciun mineral gasit pentru „' + date.termen + '"</p>';
        return;
    }

    var html = '<p class="cautare-info">Gasite: <strong>' + date.numar + '</strong> rezultat(e)</p>';
    html += '<div class="cautare-lista">';

    date.rezultate.forEach(function (m) {
        html +=
            '<a href="' + m.link + '" class="cautare-item">' +
            '<img src="' + m.imagine + '" alt="' + m.nume + '" class="cautare-img">' +
            '<div class="cautare-detalii">' +
            '<strong class="cautare-nume">' + m.nume + '</strong>' +
            '<span class="cautare-tip">' + m.tip + ' · Duritate: ' + m.duritate + ' Mohs</span>' +
            '<span class="cautare-desc">' + m.desc + '</span>' +
            '</div>' +
            '</a>';
    });

    html += '</div>';
    zonaCautare.innerHTML = html;
}
