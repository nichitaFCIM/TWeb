// ================================================
// script.js – JavaScript pentru site-ul Mineralia
// Lucrarea de laborator N1 – Tehnologii Web
// ================================================


// ------------------------------------------------
// 1. MENIU MOBIL – deschide/inchide navigarea
// ------------------------------------------------

// Cautam butonul de meniu si lista de navigare
var menuBtn = document.getElementById("menu-btn");
var navMenu = document.getElementById("site-nav");

// Cand apesi butonul, adaugam sau scoatem clasa "open"
menuBtn.addEventListener("click", function () {
    navMenu.classList.toggle("open");
});


// ------------------------------------------------
// 2. ANIMATIE LA SCROLL – cardurile apar treptat
// ------------------------------------------------

// Selectam toate cardurile de minerale
var carduri = document.querySelectorAll(".mineral-card");

// Functie care verifica daca un element e vizibil pe ecran
function verificaVizibilitate() {
    carduri.forEach(function (card) {
        // getBoundingClientRect() ne da pozitia elementului pe ecran
        var pozitie = card.getBoundingClientRect();

        // Daca elementul a intrat in ecran (top < inaltimea ferestrei)
        if (pozitie.top < window.innerHeight - 80) {
            card.classList.add("vizibil");
        }
    });
}

// Ascultam evenimentul de scroll
window.addEventListener("scroll", verificaVizibilitate);

// Rulam si la incarcare (pentru cardurile deja vizibile)
verificaVizibilitate();


// ------------------------------------------------
// 3. QUIZ – test simplu despre minerale
// ------------------------------------------------

// Intrebarile quiz-ului
var intrebari = [
    {
        intrebare: "Care mineral are duritatea maxima pe scara Mohs (10)?",
        variante: ["Cuarț", "Granit", "Diamant", "Calcar"],
        corect: 2
    },
    {
        intrebare: "Care este formula chimică a Cuarțului?",
        variante: ["CaCO₃", "SiO₂", "Al₂O₃", "Fe₂O₃"],
        corect: 1
    },
    {
        intrebare: "Ce tip de rocă este Granitul?",
        variante: ["Sedimentară", "Metamorfică", "Magmatică", "Organică"],
        corect: 2
    },
    {
        intrebare: "Din ce este format în principal Calcarul?",
        variante: ["SiO₂", "Carbon pur", "CaCO₃", "Feldspat"],
        corect: 2
    }
];

var intrebareaIndex = 0;  // care intrebare afisam acum
var scor = 0;             // cate raspunsuri corecte

// Afisam prima intrebare cand se incarca pagina
function afiseazaIntrebare() {
    var quizBox = document.getElementById("quiz-box");
    if (!quizBox) return; // daca nu exista quiz pe pagina, iesim

    var intrebareaActuala = intrebari[intrebareaIndex];

    // Construim HTML-ul pentru intrebare
    var html = "<p class='quiz-intrebare'>" + (intrebareaIndex + 1) + ". " + intrebareaActuala.intrebare + "</p>";
    html += "<div class='quiz-variante'>";

    for (var i = 0; i < intrebareaActuala.variante.length; i++) {
        html += "<button class='quiz-btn' onclick='verificaRaspuns(" + i + ")'>" + intrebareaActuala.variante[i] + "</button>";
    }

    html += "</div>";
    html += "<p class='quiz-progres'>Intrebarea " + (intrebareaIndex + 1) + " din " + intrebari.length + "</p>";

    quizBox.innerHTML = html;
}

// Verificam daca raspunsul ales e corect
function verificaRaspuns(indexAles) {
    var intrebareaActuala = intrebari[intrebareaIndex];
    var butoane = document.querySelectorAll(".quiz-btn");

    // Coloram butonul corect si cel gresit
    butoane[intrebareaActuala.corect].style.background = "#2e7d32"; // verde
    butoane[intrebareaActuala.corect].style.color = "white";

    if (indexAles !== intrebareaActuala.corect) {
        butoane[indexAles].style.background = "#c62828"; // rosu
        butoane[indexAles].style.color = "white";
    } else {
        scor++; // raspuns corect, marim scorul
    }

    // Dezactivam toate butoanele dupa raspuns
    butoane.forEach(function (btn) {
        btn.disabled = true;
    });

    // Dupa 1.5 secunde trecem la urmatoarea intrebare
    setTimeout(function () {
        intrebareaIndex++;

        if (intrebareaIndex < intrebari.length) {
            afiseazaIntrebare(); // mai sunt intrebari
        } else {
            afiseazaRezultat(); // am terminat toate intrebarile
        }
    }, 1500);
}

// Afisam rezultatul final
function afiseazaRezultat() {
    var quizBox = document.getElementById("quiz-box");

    var mesaj = "";
    if (scor === intrebari.length) {
        mesaj = "Felicitari! Ai raspuns corect la toate!";
    } else if (scor >= 2) {
        mesaj = "Bine! Mai ai ce invata.";
    } else {
        mesaj = "Incearca din nou, citeste paginile mineralelor!";
    }

    quizBox.innerHTML =
        "<div class='quiz-rezultat'>" +
        "<h3>Scor final: " + scor + " / " + intrebari.length + "</h3>" +
        "<p>" + mesaj + "</p>" +
        "<button class='quiz-btn' onclick='resetQuiz()'>Incearca din nou</button>" +
        "</div>";

    // AJAX: trimitem scorul la server si afisam statistici globale
    // Functia trimiteScorQuiz() este definita in js/ajax.js
    if (typeof trimiteScorQuiz === "function") {
        trimiteScorQuiz(scor, intrebari.length);
    }
}

// Resetam quiz-ul de la inceput
function resetQuiz() {
    intrebareaIndex = 0;
    scor = 0;
    afiseazaIntrebare();
}

// Pornim quiz-ul
afiseazaIntrebare();


// ------------------------------------------------
// 4. BARA DE DURITATE – animatie la scroll
// ------------------------------------------------

// Animam barele de duritate cand ajung in ecran
var bareFill = document.querySelectorAll(".bar-fill");

function animeazaBarele() {
    bareFill.forEach(function (bara) {
        var pozitie = bara.getBoundingClientRect();
        if (pozitie.top < window.innerHeight - 50) {
            // Luam latimea setata in HTML (ex: "70%") si o aplicam cu animatie
            var latime = bara.getAttribute("data-width");
            if (latime) {
                bara.style.width = latime;
            }
        }
    });
}

window.addEventListener("scroll", animeazaBarele);
animeazaBarele();


// ------------------------------------------------
// 5. BUTON "INAPOI SUS" – apare dupa scroll
// ------------------------------------------------

var btnSus = document.getElementById("btn-sus");

window.addEventListener("scroll", function () {
    if (!btnSus) return;

    // Daca am scrollat mai mult de 300px, afisam butonul
    if (window.scrollY > 300) {
        btnSus.style.display = "block";
    } else {
        btnSus.style.display = "none";
    }
});

// Cand apesi butonul, te duce sus
if (btnSus) {
    btnSus.addEventListener("click", function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
}
