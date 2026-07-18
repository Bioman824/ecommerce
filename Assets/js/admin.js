document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.getElementById("adminSidebar");
  const overlay = document.getElementById("adminOverlay");
  const toggle = document.getElementById("adminSidebarToggle");

  if (sidebar && overlay && toggle) {
    const closeSidebar = () => {
      sidebar.classList.remove("open");
      overlay.classList.remove("show");
    };
    toggle.addEventListener("click", () => {
      sidebar.classList.toggle("open");
      overlay.classList.toggle("show");
    });
    overlay.addEventListener("click", closeSidebar);
    sidebar.querySelectorAll("a").forEach((link) => link.addEventListener("click", closeSidebar));
  }

  document.querySelectorAll("[data-confirm]").forEach((el) => {
    el.addEventListener("submit", function (event) {
      if (!window.confirm(el.dataset.confirm)) {
        event.preventDefault();
      }
    });
  });
});
