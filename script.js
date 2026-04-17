
document.addEventListener("DOMContentLoaded", function() {

    function reveal() {
        let reveals = document.querySelectorAll(".reveal, .reveal-left, .reveal-right");
        reveals.forEach(function(el) {

            let windowHeight = window.innerHeight;
            let elementTop = el.getBoundingClientRect().top;
            let revealPoint = 120;

            if(elementTop < windowHeight - revealPoint){
                el.classList.add("active");
            }else{
                el.classList.remove("active");
            }
        });

    }

    window.addEventListener("scroll", reveal);
    
    window.addEventListener("load", reveal);

    const profileBox = document.querySelector('.profile-box');
    const avatarCircle = document.querySelector('.avatar-circle');
    const alertBox = document.querySelector('.alert-box');

    if (avatarCircle) avatarCircle.addEventListener('click', () => profileBox.classList.toggle('show'));

    if (alertBox) {
        setTimeout(() => alertBox.classList.add('show'), 50);
        
        setTimeout(() => {
            alertBox.classList.remove('show');
            setTimeout(() => alertBox.classList.remove('show'), 1000);
        }, 3000);
    };
  });

  let currentStep = 1;

  function setStep(step) {
    for (let i = 1; i <= 4; i++) {
      const sec = document.getElementById('sec' + i);
      const tab = document.getElementById('tab' + i);
      sec.style.display = (i === step) ? 'block' : 'none';
      tab.className = 'step-item' + (i === step ? ' active' : '') + (i < step ? ' done' : '');
    }
    currentStep = step;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function nextStep(from) {
    if (from === 1) {
      const p = document.querySelector('[name=nama_pria]').value.trim();
      const w = document.querySelector('[name=nama_wanita]').value.trim();
      const a = document.querySelector('[name=ayah_pria]').value.trim();
      const aw = document.querySelector('[name=ayah_wanita]').value.trim();
      if (!p || !w || !a || !aw) { showAlert('error', 'Nama pengantin pria dan wanita serta nama orang tua wajib diisi!'); return; }
    }
    if (from === 2) {
      const tgl = document.querySelector('[name=tanggal_nikah]').value;
      const lok = document.querySelector('[name=lokasi]').value.trim();
      const wm  = document.querySelector('[name=waktu_mulai]').value;
      const ws  = document.querySelector('[name=waktu_selesai]').value;
      if (!tgl || !lok || !wm || !ws) { showAlert('error', 'Tanggal, waktu, dan lokasi wajib diisi!'); return; }
    }
    hideAlert();
    setStep(from + 1);
  }

  function prevStep(from) { setStep(from - 1); }

  function pilihTema(el, nama) {
    document.querySelectorAll('.tema-card').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('temaValue').value = nama;
  }

  function showAlert(type, msg) {
    hideAlert();
    const el = document.getElementById(type === 'error' ? 'alertError' : 'alertSuccess');
    el.textContent = msg;
    el.style.display = 'block';
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
  function hideAlert() {
    document.getElementById('alertError').style.display   = 'none';
    document.getElementById('alertSuccess').style.display = 'none';
  }
  
  // Submit form via AJAX
  document.getElementById('formUndangan').addEventListener('submit', function(e) {
    e.preventDefault();
    const wa = document.querySelector('[name=no_whatsapp]').value.trim();
    if (!wa) { showAlert('error', 'Nomor WhatsApp wajib diisi!'); return; }

    document.getElementById('loadingOverlay').classList.add('show');
    hideAlert();

    const formData = new FormData(this);

    fetch('config/proses_undangan.php', { method: 'POST', body: formData })
      .then(r => r.json())
      .then(data => {
        document.getElementById('loadingOverlay').classList.remove('show');
        if (data.status === 'success') {
          document.getElementById('formUndangan').style.display = 'none';
          document.getElementById('successPage').style.display  = 'block';
          document.getElementById('kodeUndangan').textContent   = data.kode;
          document.getElementById('waTarget').textContent       = wa;
          document.querySelector('.steps-bar').style.display    = 'none';
          window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
          showAlert('error', data.message || 'Terjadi kesalahan, coba lagi.');
        }
      })
      .catch(() => {
        document.getElementById('loadingOverlay').classList.remove('show');
        showAlert('error', 'Gagal terhubung ke server. Periksa koneksi kamu.');
      });
  });

    // const form=document.getElementById('formUndangan');
    //   if(form){
    //     form.addEventListener('submit',async function(e){
    //     e.preventDefault();
    //     document.getElementById('loadingOverlay').style.display='flex';
    //     const fd=new FormData(form);
    //     const res=await fetch('simpan-undangan-full.php',{method:'POST',body:fd});
    //     const data=await res.json();
    //     document.getElementById('loadingOverlay').style.display='none';
    //     if(data.success){
    //       document.querySelector('form').style.display='none';
    //       document.getElementById('successPage').style.display='block';
    //       document.getElementById('kodeUndangan').innerText=data.invoice;
    //       document.getElementById('waTarget').innerText=data.wa;
    //     }
    //   });
    // }
