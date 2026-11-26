const rating = document.querySelector('[data-rating]');

if (rating) {
    const stars = Array.from(rating.querySelectorAll('.review-star'));
    const inputs = stars.map((star) => star.querySelector('input'));
    const caption = rating.querySelector('[data-rating-caption]');
    const hint = rating.dataset.hint || '';
    const checkedIndex = () => inputs.findIndex((input) => input.checked);

    const paint = (jumlah) => {
        stars.forEach((star, index) => {
            star.classList.toggle('is-filled', index < jumlah);
        });
    };

    const updateCaption = (jumlah) => {
        if (caption) {
            caption.textContent = jumlah > 0 ? stars[jumlah - 1].title : hint;
        }
    };

    const showSelection = () => {
        const jumlah = checkedIndex() + 1;

        paint(jumlah);
        updateCaption(jumlah);
    };

    stars.forEach((star, index) => {
        star.addEventListener('mouseenter', () => {
            paint(index + 1);
            updateCaption(index + 1);
        });

        star.addEventListener('mouseleave', showSelection);

        inputs[index].addEventListener('change', showSelection);
    });

    showSelection();
}
