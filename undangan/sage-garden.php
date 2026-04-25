<?php
$kode = $_GET['kode'] ?? '';
$tamu = $_GET['to']   ?? 'Tamu Undangan';
$data = [];
if ($kode) {
  require_once '../config/koneksi.php';
  $stmt = $pdo->prepare("SELECT * FROM undangan WHERE kode_undangan = ?");
  $stmt->execute([$kode]);
  $data = $stmt->fetch() ?: [];
}
$pria    = $data['nama_pria']     ?? 'Hendra';
$wanita  = $data['nama_wanita']   ?? 'Ayu';
$tgl_raw = $data['tanggal_nikah'] ?? '2026-09-12';
$wm      = $data['waktu_mulai']   ?? '09:00';
$ws      = $data['waktu_selesai'] ?? '13:00';
$lokasi  = $data['lokasi']        ?? 'The Botanica Garden, Malang';
$maps    = $data['link_maps']     ?? 'https://maps.google.com';
$tgl_obj = new DateTime($tgl_raw);
$bulan_id = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$hari_id = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$tgl_full = $hari_id[(int)$tgl_obj->format('w')] . ', ' . $tgl_obj->format('j') . ' ' . $bulan_id[(int)$tgl_obj->format('n')] . ' ' . $tgl_obj->format('Y');
$tgl_countdown = $tgl_obj->format('Y-m-d') . 'T' . $wm . ':00';
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Undangan Pernikahan <?= $pria ?> & <?= $wanita ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Lato:ital,wght@0,300;0,400;0,700;1,300;1,400&display=swap" rel="stylesheet" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
<style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --s1: #2d4a3e;
      --s2: #3d6b44;
      --s3: #7a9e7e;
      --s4: #b8d4bc;
      --s5: #e8f2e9;
      --cream: #faf8f4;
      --ivory: #f5f0e8;
      --brown: #5c4a32;
      --dark: #1e2d1f;
      --gray: #5a6b5c;
      --white: #ffffff;
      --gold: #a8895a;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'Lato', sans-serif;
      background: var(--cream);
      color: var(--dark);
      overflow-x: hidden;
    }

    /* COVER */
    .cover {
      min-height: 100vh;
      background: var(--s1);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 3rem 2rem;
      position: relative;
      overflow: hidden;
    }

    .cover::before {
      content: '';
      position: absolute;
      inset: 0;
      background:
        radial-gradient(ellipse at 10% 20%, rgba(122, 158, 126, .25) 0%, transparent 50%),
        radial-gradient(ellipse at 90% 80%, rgba(61, 107, 68, .2) 0%, transparent 50%);
    }

    .leaf-tl,
    .leaf-tr,
    .leaf-bl,
    .leaf-br {
      position: absolute;
      font-size: clamp(3rem, 8vw, 6rem);
      opacity: .12;
      pointer-events: none;
    }

    .leaf-tl {
      top: 3%;
      left: 2%;
      transform: rotate(-30deg);
    }

    .leaf-tr {
      top: 3%;
      right: 2%;
      transform: rotate(30deg) scaleX(-1);
    }

    .leaf-bl {
      bottom: 3%;
      left: 2%;
      transform: rotate(20deg);
    }

    .leaf-br {
      bottom: 3%;
      right: 2%;
      transform: rotate(-20deg) scaleX(-1);
    }

    .cover-frame {
      position: absolute;
      inset: 20px;
      border: 1px solid rgba(184, 212, 188, .2);
      pointer-events: none;
    }

    .cover-frame::before {
      content: '';
      position: absolute;
      inset: 6px;
      border: 1px solid rgba(184, 212, 188, .1);
    }

    .corner {
      position: absolute;
      width: 28px;
      height: 28px;
    }

    .corner::before,
    .corner::after {
      content: '';
      position: absolute;
      background: rgba(184, 212, 188, .35);
    }

    .corner::before {
      width: 100%;
      height: 1px;
      top: 0;
      left: 0;
    }

    .corner::after {
      width: 1px;
      height: 100%;
      top: 0;
      left: 0;
    }

    .corner-tl {
      top: 20px;
      left: 20px;
    }

    .corner-tr {
      top: 20px;
      right: 20px;
      transform: scaleX(-1);
    }

    .corner-bl {
      bottom: 20px;
      left: 20px;
      transform: scaleY(-1);
    }

    .corner-br {
      bottom: 20px;
      right: 20px;
      transform: scale(-1);
    }

    .cover-monogram {
      font-family: 'Libre Baskerville', serif;
      font-size: clamp(5rem, 18vw, 11rem);
      color: rgba(255, 255, 255, .07);
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -52%);
      letter-spacing: .05em;
      pointer-events: none;
    }

    .cover-eyebrow {
      font-family: 'Lato', sans-serif;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .35em;
      text-transform: uppercase;
      color: var(--s4);
      margin-bottom: 2rem;
      position: relative;
    }

    .cover-name {
      font-family: 'Libre Baskerville', serif;
      font-size: clamp(2.8rem, 9vw, 6rem);
      color: var(--white);
      line-height: 1.1;
      margin-bottom: .4rem;
      position: relative;
    }

    .cover-name-wanita {
      font-family: 'Libre Baskerville', serif;
      font-size: clamp(2.8rem, 9vw, 6rem);
      color: var(--s4);
      line-height: 1.1;
      margin-bottom: .25rem;
      position: relative;
    }

    .cover-amp {
      font-family: 'Libre Baskerville', serif;
      font-style: italic;
      font-size: clamp(1.4rem, 4vw, 2.2rem);
      color: var(--s3);
      display: block;
      margin: .3rem 0;
      position: relative;
    }

    .cover-rule {
      width: 100px;
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--s4), transparent);
      margin: 1.5rem auto;
      position: relative;
    }

    .cover-rule::before {
      content: '✦';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: var(--s4);
      font-size: 10px;
      background: var(--s1);
      padding: 0 8px;
    }

    .cover-date {
      font-family: 'Lato', sans-serif;
      font-size: clamp(.85rem, 2vw, 1rem);
      font-weight: 300;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, .55);
      margin-bottom: 2.5rem;
      position: relative;
    }

    .cover-to-label {
      font-family: 'Lato', sans-serif;
      font-size: 11px;
      letter-spacing: .15em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, .35);
      margin-bottom: .4rem;
    }

    .cover-guest {
      font-family: 'Libre Baskerville', serif;
      font-style: italic;
      font-size: clamp(1.1rem, 3.5vw, 1.6rem);
      color: var(--white);
      margin-bottom: 2.75rem;
      position: relative;
    }

    .btn-open {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 14px 36px;
      background: transparent;
      color: var(--white);
      border: 1.5px solid rgba(184, 212, 188, .5);
      border-radius: 2px;
      font-family: 'Lato', sans-serif;
      font-weight: 700;
      font-size: 12px;
      letter-spacing: .2em;
      text-transform: uppercase;
      cursor: pointer;
      transition: all .3s;
      position: relative;
      overflow: hidden;
    }

    .btn-open::before {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(255, 255, 255, .08);
      transform: scaleX(0);
      transform-origin: left;
      transition: transform .3s;
    }

    .btn-open:hover::before {
      transform: scaleX(1);
    }

    .btn-open:hover {
      border-color: rgba(184, 212, 188, .8);
    }

    .content {
      display: none;
    }

    .content.show {
      display: block;
    }

    section {
      padding: 5.5rem 1.5rem;
    }

    .sec-inner {
      max-width: 700px;
      margin: 0 auto;
      text-align: center;
    }

    .baskerville-heading {
      font-family: 'Libre Baskerville', serif;
      font-size: clamp(1.6rem, 4vw, 2.4rem);
      color: var(--s1);
      margin-bottom: .5rem;
      line-height: 1.3;
    }

    .baskerville-italic {
      font-family: 'Libre Baskerville', serif;
      font-style: italic;
      font-size: clamp(2rem, 5vw, 3rem);
      color: var(--s2);
      margin-bottom: .5rem;
    }

    .lato-label {
      font-family: 'Lato', sans-serif;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .25em;
      text-transform: uppercase;
      color: var(--s3);
      margin-bottom: 1.5rem;
    }

    .sage-rule {
      display: flex;
      align-items: center;
      gap: 12px;
      justify-content: center;
      margin: 1.25rem auto;
    }

    .sage-rule::before,
    .sage-rule::after {
      content: '';
      flex: 1;
      max-width: 80px;
      height: 1px;
      background: var(--s4);
    }

    .sage-rule-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--s3);
      position: relative;
    }

    .sage-rule-dot::before,
    .sage-rule-dot::after {
      content: '';
      position: absolute;
      width: 3px;
      height: 3px;
      border-radius: 50%;
      background: var(--s4);
      top: 50%;
      transform: translateY(-50%);
    }

    .sage-rule-dot::before {
      right: 10px;
    }

    .sage-rule-dot::after {
      left: 10px;
    }

    .pembuka-section {
      background: var(--ivory);
    }

    .ayat-box {
      max-width: 520px;
      margin: 2rem auto 0;
      padding: 2rem;
      border: 1px solid var(--s4);
      border-radius: 2px;
      background: var(--s5);
      position: relative;
    }

    .ayat-box::before {
      content: '"';
      position: absolute;
      top: -20px;
      left: 24px;
      font-family: 'Libre Baskerville', serif;
      font-size: 4rem;
      color: var(--s4);
      line-height: 1;
    }

    .ayat-box p {
      font-family: 'Libre Baskerville', serif;
      font-style: italic;
      font-size: 14px;
      color: var(--gray);
      line-height: 2;
      margin-bottom: .5rem;
    }

    .ayat-box cite {
      font-family: 'Lato', sans-serif;
      font-size: 11px;
      letter-spacing: .1em;
      color: var(--s3);
      text-transform: uppercase;
    }

    .couple-section {
      background: var(--white);
    }

    .couple-grid {
      display: grid;
      grid-template-columns: 1fr 80px 1fr;
      gap: 1.5rem;
      align-items: center;
      margin: 2.5rem 0;
    }

    .couple-avatar {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--s1), var(--s2));
      margin: 0 auto 1.25rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Libre Baskerville', serif;
      font-size: 2.2rem;
      color: var(--white);
      border: 3px solid var(--s5);
      box-shadow: 0 8px 32px rgba(45, 74, 62, .15);
    }

    .couple-card h3 {
      font-family: 'Libre Baskerville', serif;
      font-size: 1.4rem;
      color: var(--s1);
      margin-bottom: 6px;
    }

    .couple-card .putra-putri {
      font-family: 'Lato', sans-serif;
      font-size: 12px;
      font-weight: 300;
      color: var(--gray);
      line-height: 1.8;
    }

    .couple-card .ortu {
      font-family: 'Lato', sans-serif;
      font-size: 13px;
      color: var(--brown);
      margin-top: 4px;
    }

    .amp-center {
      font-family: 'Libre Baskerville', serif;
      font-style: italic;
      font-size: 3rem;
      color: var(--s4);
      text-align: center;
      line-height: 1;
    }

    .countdown-section {
      background: var(--s1);
      position: relative;
      overflow: hidden;
    }

    .countdown-section::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(ellipse at 0% 100%, rgba(122, 158, 126, .2) 0%, transparent 50%);
    }

    .cd-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1rem;
      max-width: 420px;
      margin: 2rem auto 0;
      position: relative;
    }

    .cd-box {
      background: rgba(255, 255, 255, .06);
      border: 1px solid rgba(184, 212, 188, .2);
      border-radius: 4px;
      padding: 1.5rem .75rem;
      text-align: center;
    }

    .cd-num {
      font-family: 'Libre Baskerville', serif;
      font-size: 2.5rem;
      color: var(--white);
      line-height: 1;
      font-weight: 700;
    }

    .cd-lbl {
      font-family: 'Lato', sans-serif;
      font-size: 10px;
      font-weight: 700;
      letter-spacing: .15em;
      text-transform: uppercase;
      color: var(--s4);
      margin-top: 6px;
    }

    .detail-section {
      background: var(--ivory);
    }

    .event-cards {
      display: grid;
      gap: 1.25rem;
    }

    .event-card {
      background: var(--white);
      border: 1px solid var(--s4);
      border-radius: 2px;
      padding: 2rem;
      text-align: left;
      border-left: 4px solid var(--s2);
    }

    .event-card-header {
      display: flex;
      align-items: flex-start;
      gap: 1rem;
      margin-bottom: 1.25rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--s5);
    }

    .event-icon {
      width: 48px;
      height: 48px;
      border-radius: 4px;
      background: var(--s5);
      color: var(--s2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      flex-shrink: 0;
    }

    .event-card-header h3 {
      font-family: 'Libre Baskerville', serif;
      font-size: 1.1rem;
      color: var(--s1);
      margin-bottom: 3px;
    }

    .event-card-header p {
      font-family: 'Lato', sans-serif;
      font-size: 12px;
      color: var(--s3);
      letter-spacing: .05em;
    }

    .event-detail-row {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      font-size: 14px;
      margin-bottom: .6rem;
    }

    .event-detail-row .icon {
      color: var(--s3);
      font-size: 26px;
      flex-shrink: 0;
      margin-top: 1px;
    }

    .event-detail-row strong {
      font-family: 'Lato', sans-serif;
      font-weight: 700;
      color: var(--dark);
      min-width: 70px;
      font-size: 13px;
    }

    .event-detail-row span {
      font-family: 'Lato', sans-serif;
      font-weight: 300;
      color: var(--gray);
      line-height: 1.6;
    }

    .btn-maps {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      margin-top: 1.25rem;
      padding: 10px 20px;
      background: var(--s2);
      color: var(--white);
      border-radius: 2px;
      text-decoration: none;
      font-family: 'Lato', sans-serif;
      font-weight: 700;
      font-size: 12px;
      letter-spacing: .1em;
      text-transform: uppercase;
      transition: background .2s;
    }

    .btn-maps:hover {
      background: var(--s1);
    }

    .galeri-section {
      background: var(--white);
    }

    .galeri-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      margin-top: 2rem;
    }

    .galeri-item {
      aspect-ratio: 1;
      background: linear-gradient(135deg, var(--s5), var(--s4));
      border-radius: 2px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--s3);
      font-size: 2rem;
    }

    .galeri-item:first-child {
      grid-column: span 2;
      grid-row: span 2;
      aspect-ratio: auto;
      min-height: 200px;
    }

    .galeri-note {
      font-family: 'Lato', sans-serif;
      font-size: 12px;
      color: var(--s3);
      margin-top: 1rem;
      font-style: italic;
      font-weight: 300;
    }

    .rsvp-section {
      background: var(--s5);
    }

    .rsvp-wrap {
      max-width: 460px;
      margin: 2rem auto 0;
      text-align: left;
    }

    .rsvp-field {
      margin-bottom: 1.1rem;
    }

    .rsvp-field label {
      display: block;
      font-family: 'Lato', sans-serif;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .15em;
      text-transform: uppercase;
      color: var(--s2);
      margin-bottom: 6px;
    }

    .rsvp-field input,
    .rsvp-field select,
    .rsvp-field textarea {
      width: 100%;
      padding: 12px 16px;
      border: 1.5px solid var(--s4);
      border-radius: 2px;
      font-size: 14px;
      font-family: 'Lato', sans-serif;
      font-weight: 300;
      color: var(--dark);
      background: var(--white);
      outline: none;
      transition: border-color .2s;
    }

    .rsvp-field input:focus,
    .rsvp-field select:focus,
    .rsvp-field textarea:focus {
      border-color: var(--s2);
      box-shadow: 0 0 0 3px rgba(61, 107, 68, .1);
    }

    .rsvp-field textarea {
      resize: vertical;
      min-height: 90px;
    }

    .btn-rsvp {
      width: 100%;
      padding: 14px;
      background: var(--s1);
      color: var(--white);
      border: none;
      border-radius: 2px;
      font-family: 'Lato', sans-serif;
      font-weight: 700;
      font-size: 12px;
      letter-spacing: .2em;
      text-transform: uppercase;
      cursor: pointer;
      transition: background .2s;
    }

    .btn-rsvp:hover {
      background: var(--s2);
    }

    .rsvp-success {
      display: none;
      text-align: center;
      padding: 2.5rem 1rem;
    }

    .rsvp-success .checkmark {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      background: var(--s5);
      border: 2px solid var(--s3);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      margin: 0 auto 1.25rem;
    }

    .rsvp-success h3 {
      font-family: 'Libre Baskerville', serif;
      font-size: 1.4rem;
      color: var(--s1);
      margin-bottom: .5rem;
    }

    .rsvp-success p {
      font-family: 'Lato', sans-serif;
      font-weight: 300;
      font-size: 14px;
      color: var(--gray);
    }

    .doa-section {
      background: var(--ivory);
    }

    .doa-box {
      max-width: 520px;
      margin: 2rem auto 0;
      padding: 2.5rem 2rem;
      border: 1px solid var(--s4);
      background: var(--white);
      border-radius: 2px;
      border-top: 4px solid var(--s2);
    }

    .doa-box p {
      font-family: 'Libre Baskerville', serif;
      font-style: italic;
      font-size: 15px;
      color: var(--gray);
      line-height: 2;
      text-align: center;
    }

    .doa-box cite {
      display: block;
      margin-top: 1rem;
      font-family: 'Lato', sans-serif;
      font-size: 11px;
      font-style: normal;
      font-weight: 700;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--s3);
      text-align: center;
    }

    .closing-section {
      background: var(--s1);
      text-align: center;
      padding: 7rem 1.5rem;
      position: relative;
      overflow: hidden;
    }

    .closing-section::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(ellipse at 50% 100%, rgba(122, 158, 126, .2) 0%, transparent 60%);
    }

    .closing-leaf-bg {
      position: absolute;
      font-size: 18rem;
      opacity: .04;
      pointer-events: none;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .closing-section p {
      font-family: 'Lato', sans-serif;
      font-weight: 300;
      font-size: 14px;
      color: rgba(255, 255, 255, .55);
      line-height: 1.9;
      max-width: 440px;
      margin: 1.5rem auto;
      position: relative;
    }

    .closing-brand {
      position: relative;
      margin-top: 3rem;
      font-family: 'Lato', sans-serif;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .15em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, .2);
    }

    .closing-brand span {
      color: var(--s4);
    }

    .closing-section .sage-rule::before,
    .closing-section .sage-rule::after {
      background: rgba(184, 212, 188, .3);
    }

    .closing-section .sage-rule-dot {
      background: var(--s4);
    }

    .closing-section .sage-rule-dot::before,
    .closing-section .sage-rule-dot::after {
      background: rgba(184, 212, 188, .4);
    }

    .music-btn {
      position: fixed;
      bottom: 1.5rem;
      right: 1.5rem;
      z-index: 999;
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: var(--s2);
      color: var(--white);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      cursor: pointer;
      border: none;
      box-shadow: 0 4px 20px rgba(45, 74, 62, .35);
      transition: transform .2s, background .2s;
    }

    .music-btn:hover {
      transform: scale(1.1);
      background: var(--s1);
    }

    .music-btn.playing {
      animation: pulse-m 2s ease-in-out infinite;
    }

    @keyframes pulse-m {

      0%,
      100% {
        box-shadow: 0 4px 20px rgba(45, 74, 62, .35)
      }

      50% {
        box-shadow: 0 4px 28px rgba(45, 74, 62, .6)
      }
    }

    @media(max-width:560px) {
      .couple-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
      }

      .amp-center {
        font-size: 2rem;
      }

      .cd-grid {
        grid-template-columns: repeat(2, 1fr);
      }

      .galeri-grid {
        grid-template-columns: repeat(2, 1fr);
      }

      .galeri-item:first-child {
        grid-column: span 2;
      }
    }
  </style>
</head>

<body>

  <div class="cover" id="cover">
    <span class="leaf-tl">🌿</span><span class="leaf-tr">🌿</span>
    <span class="leaf-bl">🍃</span><span class="leaf-br">🍃</span>
    <div class="cover-frame"></div>
    <div class="corner corner-tl"></div>
    <div class="corner corner-tr"></div>
    <div class="corner corner-bl"></div>
    <div class="corner corner-br"></div>
    <div class="cover-monogram"><?= strtoupper(substr($pria, 0, 1) . substr($wanita, 0, 1)) ?></div>
    <div class="cover-eyebrow">The Wedding Of</div>
    <div class="cover-name"><?= $pria ?></div>
    <div class="cover-amp">&amp;</div>
    <div class="cover-name-wanita"><?= $wanita ?></div>
    <div class="cover-rule"></div>
    <div class="cover-date"><?= $tgl_full ?></div>
    <div class="cover-to-label">Kepada Yang Terhormat</div>
    <div class="cover-guest"><?= htmlspecialchars($tamu) ?></div>
    <button class="btn-open" onclick="bukaUndangan()"><span>✉</span> Buka Undangan</button>
  </div>

  <div class="content" id="mainContent">
    <section class="pembuka-section">
      <div class="sec-inner">
        <div class="baskerville-italic">Bismillahirrahmanirrahim</div>
        <div class="lato-label">Dengan Nama Allah Yang Maha Pengasih Lagi Maha Penyayang</div>
        <div class="sage-rule">
          <div class="sage-rule-dot"></div>
        </div>
        <div class="ayat-box">
          <p>Dan di antara tanda-tanda kebesaran-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya.</p>
          <cite>— QS. Ar-Rum : 21</cite>
        </div>
      </div>
    </section>

    <section class="couple-section">
      <div class="sec-inner">
        <div class="baskerville-heading">Mempelai</div>
        <div class="lato-label">Yang Berbahagia</div>
        <div class="sage-rule">
          <div class="sage-rule-dot"></div>
        </div>
        <div class="couple-grid">
          <div class="couple-card">
            <div class="couple-avatar"><?= strtoupper(substr($pria, 0, 1)) ?></div>
            <h3><?= $pria ?></h3>
            <div class="putra-putri">Putra dari</div>
            <div class="ortu">Bapak &amp; Ibu</div>
          </div>
          <div class="amp-center">&amp;</div>
          <div class="couple-card">
            <div class="couple-avatar" style="background:linear-gradient(135deg,var(--s2),var(--s3))"><?= strtoupper(substr($wanita, 0, 1)) ?></div>
            <h3><?= $wanita ?></h3>
            <div class="putra-putri">Putri dari</div>
            <div class="ortu">Bapak &amp; Ibu</div>
          </div>
        </div>
      </div>
    </section>

    <section class="countdown-section">
      <div class="sec-inner">
        <div class="baskerville-heading" style="color:var(--s4);position:relative">Menuju Hari Bahagia</div>
        <div class="lato-label" style="color:rgba(184,212,188,.5);position:relative">Hitung Mundur</div>
        <div class="baskerville-italic" style="font-size:1.1rem;color:rgba(255,255,255,.6);position:relative"><?= $tgl_full ?></div>
        <div class="cd-grid">
          <div class="cd-box">
            <div class="cd-num" id="cd-h">00</div>
            <div class="cd-lbl">Hari</div>
          </div>
          <div class="cd-box">
            <div class="cd-num" id="cd-j">00</div>
            <div class="cd-lbl">Jam</div>
          </div>
          <div class="cd-box">
            <div class="cd-num" id="cd-m">00</div>
            <div class="cd-lbl">Menit</div>
          </div>
          <div class="cd-box">
            <div class="cd-num" id="cd-s">00</div>
            <div class="cd-lbl">Detik</div>
          </div>
        </div>
      </div>
    </section>

    <section class="detail-section">
      <div class="sec-inner">
        <div class="baskerville-heading">Rangkaian Acara</div>
        <div class="lato-label">Informasi Pernikahan</div>
        <div class="sage-rule">
          <div class="sage-rule-dot"></div>
        </div>
        <div class="event-cards">
          <div class="event-card">
            <div class="event-card-header">
              <div class="event-icon">💍</div>
              <div>
                <h3>Akad Nikah</h3>
                <p>Prosesi Ijab Kabul</p>
              </div>
            </div>
            <div class="event-detail-row"><span class="icon"><i class='bx bx-calendar-check' ></i></span><strong>Tanggal</strong><span><?= $tgl_full ?></span></div>
            <div class="event-detail-row"><span class="icon"><i class='bx bx-time'></i></span><strong>Waktu</strong><span><?= $wm ?> WIB – Selesai</span></div>
            <div class="event-detail-row"><span class="icon"><i class='bx bx-map' ></i></span><strong>Lokasi</strong><span><?= $lokasi ?></span></div>
            <a href="<?= $maps ?>" target="_blank" class="btn-maps"><i class='bx bx-map' ></i> Buka Google Maps</a>
          </div>
          <div class="event-card">
            <div class="event-card-header">
              <div class="event-icon">🌿</div>
              <div>
                <h3>Resepsi Pernikahan</h3>
                <p>Syukuran &amp; Jamuan Tamu</p>
              </div>
            </div>
            <div class="event-detail-row"><span class="icon"><i class='bx bx-calendar-check' ></i></span><strong>Tanggal</strong><span><?= $tgl_full ?></span></div>
            <div class="event-detail-row"><span class="icon"><i class='bx bx-time'></i></span><strong>Waktu</strong><span><?= $wm ?> – <?= $ws ?> WIB</span></div>
            <div class="event-detail-row"><span class="icon"><i class='bx bx-map' ></i></span><strong>Lokasi</strong><span><?= $lokasi ?></span></div>
            <a href="<?= $maps ?>" target="_blank" class="btn-maps"><i class='bx bx-map' ></i> Buka Google Maps</a>
          </div>
        </div>
      </div>
    </section>

    <section class="galeri-section">
      <div class="sec-inner">
        <div class="baskerville-heading">Galeri Foto</div>
        <div class="lato-label">Momen Berharga Kami</div>
        <div class="sage-rule">
          <div class="sage-rule-dot"></div>
        </div>
        <div class="galeri-grid">
          <div class="galeri-item">🌿</div>
          <div class="galeri-item">🍃</div>
          <div class="galeri-item">🌾</div>
          <div class="galeri-item">🌸</div>
          <div class="galeri-item">🌿</div>
        </div>
        <p class="galeri-note">* Foto pre-wedding akan ditampilkan di sini</p>
      </div>
    </section>

    <section class="rsvp-section">
      <div class="sec-inner">
        <div class="baskerville-heading">Konfirmasi Kehadiran</div>
        <div class="lato-label">RSVP</div>
        <div class="sage-rule">
          <div class="sage-rule-dot"></div>
        </div>
        <p style="font-family:'Lato',sans-serif;font-weight:300;font-size:14px;color:var(--gray);margin-bottom:.5rem">Mohon konfirmasi kehadiran paling lambat 7 hari sebelum acara</p>
        <div class="rsvp-wrap" id="rsvpForm">
          <div class="rsvp-field"><label>Nama Lengkap</label><input type="text" id="rsvpNama" value="<?= htmlspecialchars($tamu) ?>" placeholder="Nama kamu..." /></div>
          <div class="rsvp-field"><label>Jumlah Tamu</label>
            <select id="rsvpJml">
              <option>1 orang</option>
              <option>2 orang</option>
              <option>3 orang</option>
              <option>4+ orang</option>
            </select>
          </div>
          <div class="rsvp-field"><label>Kehadiran</label>
            <select id="rsvpHadir">
              <option value="hadir"><i class='bx bx-check' ></i> Insya Allah Hadir</option>
              <option value="tidak"><i class='bx bx-x' ></i> Berhalangan Hadir</option>
              <option value="mungkin"><i class='bx bx-confused' ></i> Belum Dapat Dipastikan</option>
            </select>
          </div>
          <div class="rsvp-field"><label>Ucapan &amp; Doa</label><textarea id="rsvpUcapan" placeholder="Tuliskan ucapan dan doa terbaik..."></textarea></div>
          <button class="btn-rsvp" onclick="kirimRSVP()">Kirim Konfirmasi</button>
        </div>
        <div class="rsvp-success" id="rsvpSuccess">
          <div class="checkmark">✓</div>
          <h3>Terima Kasih</h3>
          <p>Konfirmasi dan ucapanmu sudah kami terima.<br>Sampai jumpa di hari bahagia kami.</p>
        </div>
      </div>
    </section>

    <section class="doa-section">
      <div class="sec-inner">
        <div class="baskerville-heading">Doa &amp; Harapan</div>
        <div class="lato-label">Untuk Kedua Mempelai</div>
        <div class="sage-rule">
          <div class="sage-rule-dot"></div>
        </div>
        <div class="doa-box">
          <p>Semoga pernikahan ini menjadi ladang pahala, sakinah mawaddah wa rahmah, serta menjadi keluarga yang diridhai Allah SWT hingga akhir hayat.</p>
          <cite>— Doa dari keluarga besar</cite>
        </div>
      </div>
    </section>

    <section class="closing-section">
      <div class="sec-inner" style="position:relative">
        <div class="closing-leaf-bg">🌿</div>
        <div class="lato-label" style="color:rgba(184,212,188,.4);position:relative">Dengan Sepenuh Hati</div>
        <div class="baskerville-italic" style="position:relative"><?= $pria ?> &amp; <?= $wanita ?></div>
        <div class="sage-rule" style="position:relative">
          <div class="sage-rule-dot"></div>
        </div>
        <p>Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu.</p>
        <div class="closing-brand">Dibuat dengan ♥ oleh <span>Bernada.ID</span></div>
      </div>
    </section>
  </div>

  <button class="music-btn" id="musicBtn" onclick="toggleMusic()"><i class='bx bx-music' ></i></button>
  <audio id="bgMusic" loop>
    <source src="../audio/wedding-music.mp3" type="audio/mpeg" />
  </audio>

  <script>
    function bukaUndangan() {
      document.getElementById('cover').style.display = 'none';
      document.getElementById('mainContent').classList.add('show');
      document.getElementById('bgMusic').play().catch(() => {});
      document.getElementById('musicBtn').classList.add('playing');
    }
    let playing = false;

    function toggleMusic() {
      const m = document.getElementById('bgMusic'),
        btn = document.getElementById('musicBtn');
      if (playing) {
        m.pause();
        btn.innerHTML = '<i class="bx bx-music"></i>';
        btn.classList.remove('playing');
        playing = false;
      } else {
        m.play().catch(() => {});
        btn.textContent = '⏸';
        btn.classList.add('playing');
        playing = true;
      }
    }

    function updateCountdown() {
      const diff = new Date('<?= $tgl_countdown ?>').getTime() - Date.now();
      if (diff <= 0) return;
      document.getElementById('cd-h').textContent = String(Math.floor(diff / 864e5)).padStart(2, '0');
      document.getElementById('cd-j').textContent = String(Math.floor(diff % 864e5 / 36e5)).padStart(2, '0');
      document.getElementById('cd-m').textContent = String(Math.floor(diff % 36e5 / 6e4)).padStart(2, '0');
      document.getElementById('cd-s').textContent = String(Math.floor(diff % 6e4 / 1e3)).padStart(2, '0');
    }
    setInterval(updateCountdown, 1000);
    updateCountdown();

    function kirimRSVP() {
      const nama = document.getElementById('rsvpNama').value.trim();
      if (!nama) {
        alert('Mohon isi nama kamu.');
        return;
      }
      const jml = document.getElementById('rsvpJml').value;
      const hadir = document.getElementById('rsvpHadir');
      const ucapan = document.getElementById('rsvpUcapan').value.trim();
      const status = hadir.options[hadir.selectedIndex].text.replace(/^[^\s]+\s+/, '');
      const p = encodeURIComponent(`Assalamu'alaikum 🌿\n\nSaya ${nama} konfirmasi kehadiran di pernikahan <?= $pria ?> & <?= $wanita ?>.\n\nKehadiran : ${status}\nJumlah Tamu : ${jml}` + (ucapan ? `\n\nUcapan :\n"${ucapan}"` : ''));
      window.open(`https://wa.me/6281939195110?text=${p}`, '_blank');
      document.getElementById('rsvpForm').style.display = 'none';
      document.getElementById('rsvpSuccess').style.display = 'block';
    }
  </script>
</body>

</html>