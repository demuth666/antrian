const KUNCI_TEMA = 'tema';
const akar = document.documentElement;
const media = window.matchMedia('(prefers-color-scheme: dark)');
const tombolTema = document.querySelectorAll('[data-theme-toggle]');

const bacaTema = () => {
    try {
        return localStorage.getItem(KUNCI_TEMA);
    } catch {
        return null;
    }
};

const simpanTema = (tema) => {
    try {
        localStorage.setItem(KUNCI_TEMA, tema);
    } catch {
        // localStorage tidak tersedia (misalnya mode privat ketat).
    }
};

const perbaruiTombol = (gelap) => {
    tombolTema.forEach((tombol) => {
        const label = gelap
            ? (tombol.dataset.labelLight || 'Aktifkan mode terang')
            : (tombol.dataset.labelDark || 'Aktifkan mode gelap');

        tombol.setAttribute('aria-pressed', gelap ? 'true' : 'false');
        tombol.setAttribute('aria-label', label);
        tombol.setAttribute('title', label);
    });
};

const terapkanTema = (gelap) => {
    akar.dataset.theme = gelap ? 'dark' : 'light';
    perbaruiTombol(gelap);
};

let gelap = akar.dataset.theme === 'dark';

perbaruiTombol(gelap);

tombolTema.forEach((tombol) => {
    tombol.addEventListener('click', () => {
        gelap = !gelap;
        simpanTema(gelap ? 'gelap' : 'terang');
        terapkanTema(gelap);
    });
});

media.addEventListener('change', (event) => {
    if (bacaTema() === null) {
        gelap = event.matches;
        terapkanTema(gelap);
    }
});
