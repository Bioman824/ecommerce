document.addEventListener("DOMContentLoaded", function () {
  const notify = document.querySelector("[data-toast]");
  if (notify) {
    setTimeout(() => notify.classList.add("d-none"), 2500);
  }

  const mobileNavEl = document.getElementById("mobileNav");
  if (mobileNavEl && window.bootstrap) {
    const offcanvas = window.bootstrap.Offcanvas.getOrCreateInstance(mobileNavEl);
    mobileNavEl.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => offcanvas.hide());
    });
  }

  const backToTop = document.getElementById("backToTop");
  if (backToTop) {
    window.addEventListener("scroll", () => {
      backToTop.classList.toggle("show", window.scrollY > 400);
    });
    backToTop.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }

  document.querySelectorAll(".add-to-cart").forEach((button) => {
    button.addEventListener("click", function () {
      const productId = this.dataset.productId;
      fetch("API/cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "action=add&product_id=" + productId + "&quantity=1",
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            const cartBadge = document.querySelector("[data-cart-count]");
            if (cartBadge) {
              cartBadge.textContent = data.count;
            }
            this.textContent = "Added";
            this.disabled = true;
          }
        });
    });
  });

  const reviewForm = document.getElementById("review-form");
  if (reviewForm) {
    reviewForm.addEventListener("submit", function (event) {
      event.preventDefault();
      const formData = new FormData(reviewForm);
      formData.append("product_id", reviewForm.dataset.productId);
      fetch("API/review.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            window.location.reload();
          }
        });
    });
  }

  const newsletterForm = document.querySelector("[data-newsletter-form]");
  const newsletterMessage = document.querySelector("[data-newsletter-message]");
  if (newsletterForm && newsletterMessage) {
    newsletterForm.addEventListener("submit", function (event) {
      event.preventDefault();
      const formData = new FormData(newsletterForm);
      fetch("API/newsletter.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          newsletterMessage.textContent = data.success
            ? "Thanks for subscribing!"
            : data.message || "Unable to subscribe right now.";
          if (data.success) {
            newsletterForm.reset();
          }
        });
    });
  }
});
