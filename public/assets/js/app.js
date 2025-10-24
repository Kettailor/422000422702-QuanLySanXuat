document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('.sidebar__link');
    links.forEach(link => {
        link.addEventListener('click', () => {
            links.forEach(item => item.classList.remove('is-active'));
            link.classList.add('is-active');
        });
    });
});
