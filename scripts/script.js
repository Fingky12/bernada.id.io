document.addEventListener("DOMContentLoaded", function () {
  // Reveal on scroll
  function reveal() {
    document
      .querySelectorAll(".reveal, .reveal-left, .reveal-right")
      .forEach((el) => {
        const top = el.getBoundingClientRect().top;
        if (top < window.innerHeight - 100) el.classList.add("active");
        else el.classList.remove("active");
      });
  }
  window.addEventListener("scroll", reveal);
  window.addEventListener("load", reveal);

  // Alert box auto show & hide
  const alertBox = document.getElementById("alertBox");
  if (alertBox) {
    setTimeout(() => alertBox.classList.add("show"), 100);
    setTimeout(() => alertBox.classList.remove("show"), 4000);
  }

  // Search filter tema
  document
    .querySelector(".search-wrapper input")
    .addEventListener("input", function () {
      const q = this.value.toLowerCase();
      document.querySelectorAll(".tema-card").forEach((card) => {
        const nama = card.querySelector("h3")?.textContent.toLowerCase() || "";
        card.style.opacity = !q || nama.includes(q) ? "1" : "0.3";
      });
    });

  const profileBox = document.querySelector(".profile-box");
  const avatarCircle = document.querySelector(".avatar-circle");

  if (avatarCircle)
    avatarCircle.addEventListener("click", () =>
      profileBox.classList.toggle("show"),
    );

  // Form Submit AJAX
  const form = document.getElementById("formUndangan");
  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();

      const wa = document.querySelector("[name=no_whatsapp]")?.value.trim();
      if (!wa) return showAlert("error", "Nomor WhatsApp wajib diisi!");

      toggleLoading(true);
      hideAlert();

      try {
        const formData = new FormData(form);
        const response = await fetch("./buat_undangan/proses_undangan.php", {
          method: "POST",
          body: formData,
        });

        const data = await response.json();
        toggleLoading(false);

        if (data.status === "success") {
          form.style.display = "none";
          document.getElementById("successPage").style.display = "block";
          document.getElementById("kodeUndangan").textContent = data.kode;
          document.getElementById("waTarget").textContent = wa;
          const steps = document.querySelector(".steps-bar");
          if (steps) steps.style.display = "none";
          window.scrollTo({ top: 0, behavior: "smooth" });
        } else {
          showAlert("error", data.message || "Terjadi kesalahan, coba lagi.");
        }
      } catch (error) {
        toggleLoading(false);
        showAlert("error", "Gagal terhubung ke server.");
      }
    });
  }
});

