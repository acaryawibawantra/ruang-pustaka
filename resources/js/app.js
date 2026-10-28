const scene = document.querySelector('[data-parallax]');
const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

if (scene) {
    const foreground = scene.querySelector('[data-parallax-foreground]');
    const copy = scene.querySelector('[data-parallax-copy]');
    let ticking = false;

    const update = () => {
        const offset = Math.max(0, Math.min(-scene.getBoundingClientRect().top, scene.offsetHeight));
        const motion = reducedMotion.matches ? 0 : offset;
        foreground.style.transform = `translate3d(-50%, ${-motion * 0.08}px, 0)`;
        copy.style.transform = `translate3d(0, ${motion * 0.24}px, 0)`;
        ticking = false;
    };

    const requestUpdate = () => {
        if (!ticking) {
            window.requestAnimationFrame(update);
            ticking = true;
        }
    };

    window.addEventListener('scroll', requestUpdate, { passive: true });
    window.addEventListener('resize', requestUpdate);
    reducedMotion.addEventListener('change', requestUpdate);
    update();
}


const catalog = document.querySelector('[data-catalog]');

if (catalog) {
    const search = catalog.querySelector('[data-catalog-search]');
    const category = catalog.querySelector('[data-catalog-category]');
    const books = [...catalog.querySelectorAll('[data-catalog-book]')];
    const count = catalog.querySelector('[data-catalog-count]');
    const empty = catalog.querySelector('[data-catalog-empty]');

    const filterBooks = () => {
        const query = search.value.trim().toLocaleLowerCase('id');
        let visibleCount = 0;

        books.forEach((book) => {
            const text = `${book.dataset.title} ${book.dataset.author}`.toLocaleLowerCase('id');
            const matches = text.includes(query) && (!category.value || category.value === book.dataset.category);
            book.hidden = !matches;
            visibleCount += Number(matches);
        });

        count.textContent = `Menampilkan ${visibleCount} buku`;
        empty.hidden = visibleCount > 0;
    };

    search.addEventListener('input', filterBooks);
    category.addEventListener('change', filterBooks);
    catalog.querySelector('[data-catalog-reset]').addEventListener('click', () => {
        search.value = '';
        category.value = '';
        filterBooks();
        search.focus();
    });
    filterBooks();
}

const coverInput = document.querySelector('[data-cover-input]');
const coverPreview = document.querySelector('[data-cover-preview]');

const memberSearch = document.querySelector('[data-member-search]');

if (memberSearch) {
    const memberId = document.querySelector('[data-member-id-input]');
    const members = [...document.querySelectorAll('#active-members option')];

    const selectMember = () => {
        const selected = members.find((member) => member.value === memberSearch.value);
        memberId.value = selected?.dataset.memberId ?? '';
    };

    memberSearch.addEventListener('input', selectMember);
    memberSearch.addEventListener('change', selectMember);
    selectMember();
}

if (coverInput && coverPreview) {
    coverInput.addEventListener('change', () => {
        const [file] = coverInput.files;

        if (!file) {
            coverPreview.innerHTML = '<span class="grid h-full place-items-center p-4 text-center text-xs text-muted">Pratinjau sampul</span>';
            return;
        }

        const image = document.createElement('img');
        image.src = URL.createObjectURL(file);
        image.alt = 'Pratinjau sampul buku';
        image.className = 'h-full w-full object-cover';
        coverPreview.replaceChildren(image);
    });
}
