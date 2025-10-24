document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.querySelector('.sidebar');
  const toggler = document.querySelector('[data-toggle="sidebar"]');

  if (toggler && sidebar) {
    toggler.addEventListener('click', () => {
      sidebar.classList.toggle('open');
    });
  }

  const flash = document.querySelector('.toast');
  if (flash) {
    const toast = new bootstrap.Toast(flash, { delay: 3000 });
    toast.show();
  }
});
