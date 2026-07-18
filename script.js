const menuToggle = document.querySelector(".menu-toggle");
const navLinks = document.querySelector(".nav-links");
const cartCount = document.getElementById("cart-count");
const buttons = document.querySelectorAll(".add-to-cart");

menuToggle?.addEventListener("click", () => {
  navLinks?.classList.toggle("active");
});

let count = 0;
buttons.forEach((button) => {
  button.addEventListener("click", () => {
    count += 1;
    cartCount.textContent = count;
    button.textContent = "Added";
    button.disabled = true;
    button.style.opacity = "0.8";
  });
});
