document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.querySelector('.sidebar');
  const togglers = document.querySelectorAll('[data-toggle="sidebar"]');
  const backdrop = document.querySelector('.sidebar-backdrop');

  const toggleSidebar = (forceState) => {
    if (!sidebar) {
      return;
    }

    const shouldOpen =
      typeof forceState === 'boolean'
        ? forceState
        : !sidebar.classList.contains('open');

    sidebar.classList.toggle('open', shouldOpen);

    if (backdrop) {
      backdrop.classList.toggle('show', shouldOpen);
    }

    document.body.classList.toggle('sidebar-open', shouldOpen);
  };

  if (togglers.length && sidebar) {
    togglers.forEach((button) => {
      button.addEventListener('click', (event) => {
        event.preventDefault();
        toggleSidebar();
      });
    });
  }

  window.addEventListener('resize', () => {
    if (window.innerWidth >= 992) {
      toggleSidebar(false);
    }
  });

  const flash = document.querySelector('.toast');
  if (flash) {
    const toast = new bootstrap.Toast(flash, { delay: 3000 });
    toast.show();
  }
});
