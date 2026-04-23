document.addEventListener('DOMContentLoaded', function () {

 const faqButtons = document.querySelectorAll('.faq-question');
  faqButtons.forEach(btn => {
    btn.addEventListener('click', function () {
      const item = this.closest('.faq-item');
      const answer = item ? item.querySelector('.faq-answer') : null;
      if (!item || !answer) return;

      document.querySelectorAll('.faq-item').forEach(faq => {
        if (faq !== item) {
          faq.classList.remove('active');
          const other = faq.querySelector('.faq-answer');
          if (other) {
            other.style.maxHeight = null;
            other.style.opacity = '0';
          }
        }
      });

      item.classList.toggle('active');
      if (item.classList.contains('active')) {
        answer.style.maxHeight = answer.scrollHeight + 'px';
        answer.style.opacity = '1';
      } else {
        answer.style.maxHeight = null;
        answer.style.opacity = '0';
      }
    });
  });

  // Reveal on scroll
  function reveal() {
    document.querySelectorAll('.reveal, .reveal-left, .reveal-right').forEach(el => {
      const top = el.getBoundingClientRect().top;
      if (top < window.innerHeight - 100) el.classList.add('active');
      else el.classList.remove('active');
    });
  }
  window.addEventListener('scroll', reveal);
  window.addEventListener('load', reveal);

  // Alert box auto show & hide
  const alertBox = document.getElementById('alertBox');
  if (alertBox) {
    setTimeout(() => alertBox.classList.add('show'), 100);
    setTimeout(() => alertBox.classList.remove('show'), 4000);
  }

  // Search filter tema
  document.querySelector('.search-wrapper input').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.tema-card').forEach(card => {
      const nama = card.querySelector('h3')?.textContent.toLowerCase() || '';
      card.style.opacity = (!q || nama.includes(q)) ? '1' : '0.3';
    });
  });


  // Profile Toggle
  const avatar = document.querySelector('.avatar-circle');
  const profile = document.querySelector('.profile-box');
  if (avatar && profile) {
    avatar.addEventListener('click', () => profile.classList.toggle('show'));
  }

  // Form Submit AJAX
  const form = document.getElementById('formUndangan');
  if (form) {
    form.addEventListener('submit', async function (e) {
      e.preventDefault();

      const wa = document.querySelector('[name=no_whatsapp]')?.value.trim();
      if (!wa) return showAlert('error', 'Nomor WhatsApp wajib diisi!');

      toggleLoading(true);
      hideAlert();

      try {
        const formData = new FormData(form);
        const response = await fetch('./buat_undangan/proses_undangan.php', {
          method: 'POST',
          body: formData
        });

        const data = await response.json();
        toggleLoading(false);

        if (data.status === 'success') {
          form.style.display = 'none';
          document.getElementById('successPage').style.display = 'block';
          document.getElementById('kodeUndangan').textContent = data.kode;
          document.getElementById('waTarget').textContent = wa;
          const steps = document.querySelector('.steps-bar');
          if (steps) steps.style.display = 'none';
          window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
          showAlert('error', data.message || 'Terjadi kesalahan, coba lagi.');
        }
      } catch (error) {
        toggleLoading(false);
        showAlert('error', 'Gagal terhubung ke server.');
      }
    });
  }
});

// Multi Step Form
let currentStep = 1;

function setStep(step) {
  for (let i = 1; i <= 4; i++) {
    const sec = document.getElementById('sec' + i);
    const tab = document.getElementById('tab' + i);
    if (sec) sec.style.display = i === step ? 'block' : 'none';
    if (tab) tab.className = 'step-item' + (i === step ? ' active' : '') + (i < step ? ' done' : '');
  }
  currentStep = step;
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function nextStep(from) {
  if (from === 1) {
    const fields = ['nama_pria', 'nama_wanita', 'ayah_pria', 'ayah_wanita'];
    const empty = fields.some(name => !document.querySelector(`[name=${name}]`)?.value.trim());
    if (empty) return showAlert('error', 'Data pengantin & orang tua wajib diisi!');
  }

  if (from === 2) {
    const required = ['tanggal_nikah', 'lokasi', 'waktu_mulai', 'waktu_selesai'];
    const empty = required.some(name => !document.querySelector(`[name=${name}]`)?.value.trim());
    if (empty) return showAlert('error', 'Tanggal, waktu, dan lokasi wajib diisi!');
  }

  hideAlert();
  setStep(from + 1);
}

function prevStep(from) {
  setStep(from - 1);
}

function pilihTema(el, nama) {
  document.querySelectorAll('.tema-card').forEach(card => card.classList.remove('active'));
  el.classList.add('active');
  const tema = document.getElementById('temaValue');
  if (tema) tema.value = nama;
}

function showAlert(type, message) {
  hideAlert();
  const target = document.getElementById(type === 'error' ? 'alertError' : 'alertSuccess');
  if (!target) return;
  target.textContent = message;
  target.style.display = 'block';
  target.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function hideAlert() {
  const error = document.getElementById('alertError');
  const success = document.getElementById('alertSuccess');
  if (error) error.style.display = 'none';
  if (success) success.style.display = 'none';
}

function toggleLoading(show) {
  const overlay = document.getElementById('loadingOverlay');
  if (!overlay) return;
  overlay.classList.toggle('show', show);
}
