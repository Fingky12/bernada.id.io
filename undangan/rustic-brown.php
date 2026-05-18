<?php
require_once '../config/koneksi.php';
require_once '../config/ambil_data.php';

$galeri_fotos = $galeri_fotos ?? [];
$ada_galeri = $ada_galeri ?? false;
$tgl_countdown = $tgl_countdown ?? (($tgl_raw ?? date('Y-m-d')) . 'T' . ($ma ?? '09:00') . ':00+07:00');
$pria = $pria ?? 'Pengantin Pria';
$wanita = $wanita ?? 'Pengantin Wanita';
$tamu = $tamu ?? 'Tamu Undangan';
$tgl_full = $tgl_full ?? date('d F Y', strtotime($tgl_raw ?? date('Y-m-d')));
$ayah_pria = $ayah_pria ?? '';
$ibu_pria = $ibu_pria ?? '';
$ayah_wanita = $ayah_wanita ?? '';
$ibu_wanita = $ibu_wanita ?? '';
$ma = $ma ?? '09:00';
$sa = $sa ?? '11:00';
$mr = $mr ?? '12:00';
$sr = $sr ?? '14:00';
$lokasi = $lokasi ?? 'Alamat acara';
$maps = $maps ?? 'https://maps.google.com';

?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Undangan Pernikahan <?= $pria ?> & <?= $wanita ?></title>
  <meta property="og:title" content="Undangan Pernikahan <?= $pria ?> & <?= $wanita ?>" />
  <meta property="og:description" content="<?= $tgl_full ?> · <?= $lokasi ?>" />
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=EB+Garamond:ital,wght@0,400;0,500;1,400;1,500&display=swap" rel="stylesheet" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <style id="galeri-css-snippet">
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --b1: #2c1a0e;
      /* dark espresso */
      --b2: #5c3d1e;
      /* medium brown */
      --b3: #8b6340;
      /* warm brown */
      --b4: #c4a882;
      /* tan */
      --b5: #e8d5b7;
      /* light tan */
      --b6: #f5ede0;
      /* cream */
      --b7: #faf6f0;
      /* off white */
      --gold: #c9a227;
      --gold-l: #fdf3d0;
      --rust: #8b3a1a;
      --dark: #1a0f07;
      --gray: #6b5240;
      --white: #ffffff;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'EB Garamond', serif;
      background: var(--b7);
      color: var(--dark);
      overflow-x: hidden;
    }

    /* ══════════════════════
       COVER
    ══════════════════════ */
    .cover {
      min-height: 100vh;
      background: var(--b1);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 3rem 2rem;
      position: relative;
      overflow: hidden;
    }

    /* Wood grain texture overlay */
    .cover::before {
      content: '';
      position: absolute;
      inset: 0;
      background:
        repeating-linear-gradient(92deg,
          transparent 0px, transparent 3px,
          rgba(255, 255, 255, .012) 3px, rgba(255, 255, 255, .012) 4px),
        radial-gradient(ellipse at 20% 30%, rgba(140, 100, 60, .3) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 70%, rgba(92, 61, 30, .4) 0%, transparent 50%);
    }

    /* Rope/twine border */
    .cover-border {
      position: absolute;
      inset: 18px;
      border: 2px solid rgba(196, 168, 130, .25);
      pointer-events: none;
    }

    .cover-border::before {
      content: '';
      position: absolute;
      inset: 5px;
      border: 1px dashed rgba(196, 168, 130, .15);
    }

    /* Corner flourish */
    .flr {
      position: absolute;
      font-size: 1.8rem;
      opacity: .2;
      line-height: 1;
    }

    .flr-tl {
      top: 24px;
      left: 24px;
      transform: rotate(0deg);
    }

    .flr-tr {
      top: 24px;
      right: 24px;
      transform: rotate(90deg);
    }

    .flr-bl {
      bottom: 24px;
      left: 24px;
      transform: rotate(270deg);
    }

    .flr-br {
      bottom: 24px;
      right: 24px;
      transform: rotate(180deg);
    }

    .cover-stamp {
      font-family: 'Cinzel', serif;
      font-size: 10px;
      letter-spacing: .45em;
      text-transform: uppercase;
      color: var(--b4);
      margin-bottom: 1.75rem;
      position: relative;
    }

    .cover-stamp::before {
      content: '— ';
    }

    .cover-stamp::after {
      content: ' —';
    }

    .cover-monogram {
      font-family: 'Cinzel', serif;
      font-size: clamp(5rem, 16vw, 10rem);
      color: rgba(255, 255, 255, .05);
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -52%);
      letter-spacing: .1em;
      pointer-events: none;
    }

    .cover-name {
      font-family: 'Cinzel', serif;
      font-size: clamp(2.5rem, 8vw, 5.5rem);
      color: var(--white);
      line-height: 1.05;
      letter-spacing: .04em;
      position: relative;
    }

    .cover-amp {
      font-family: 'EB Garamond', serif;
      font-style: italic;
      font-size: clamp(1.8rem, 5vw, 3rem);
      color: var(--b4);
      display: block;
      margin: .4rem 0;
      position: relative;
    }

    .cover-name-w {
      font-family: 'Cinzel', serif;
      font-size: clamp(2.5rem, 8vw, 5.5rem);
      color: var(--b5);
      line-height: 1.05;
      letter-spacing: .04em;
      position: relative;
    }

    .cover-ornament {
      display: flex;
      align-items: center;
      gap: 10px;
      justify-content: center;
      margin: 1.5rem auto;
      color: var(--b4);
      font-size: .8rem;
      position: relative;
    }

    .cover-ornament::before,
    .cover-ornament::after {
      content: '';
      flex: 1;
      max-width: 70px;
      height: 1px;
      background: rgba(196, 168, 130, .35);
    }

    .cover-date {
      font-family: 'EB Garamond', serif;
      font-style: italic;
      font-size: clamp(1rem, 2.5vw, 1.3rem);
      color: rgba(255, 255, 255, .5);
      letter-spacing: .05em;
      margin-bottom: 2.5rem;
      position: relative;
    }

    .cover-to-lbl {
      font-family: 'Cinzel', serif;
      font-size: 9px;
      letter-spacing: .3em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, .3);
      margin-bottom: .4rem;
    }

    .cover-guest {
      font-family: 'EB Garamond', serif;
      font-style: italic;
      font-size: clamp(1.2rem, 3.5vw, 1.8rem);
      color: var(--white);
      margin-bottom: 2.75rem;
      position: relative;
    }

    .btn-open {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 13px 34px;
      background: transparent;
      color: var(--b5);
      border: 1px solid rgba(196, 168, 130, .4);
      font-family: 'Cinzel', serif;
      font-size: 11px;
      letter-spacing: .25em;
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
      background: rgba(255, 255, 255, .06);
      transform: translateX(-100%);
      transition: transform .4s;
    }

    .btn-open:hover::before {
      transform: translateX(0);
    }

    .btn-open:hover {
      border-color: rgba(196, 168, 130, .7);
      color: var(--white);
    }

    /* ══════════════════════
       CONTENT
    ══════════════════════ */
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
      max-width: 720px;
      margin: 0 auto;
      text-align: center;
    }

    .cinzel-title {
      font-family: 'Cinzel', serif;
      font-size: clamp(1.4rem, 3.5vw, 2.2rem);
      color: var(--b1);
      letter-spacing: .08em;
      margin-bottom: .4rem;
      line-height: 1.3;
    }

    .garamond-sub {
      font-family: 'EB Garamond', serif;
      font-style: italic;
      font-size: clamp(1rem, 2.5vw, 1.3rem);
      color: var(--b3);
      margin-bottom: 1.5rem;
    }

    .cinzel-label {
      font-family: 'Cinzel', serif;
      font-size: 10px;
      letter-spacing: .3em;
      text-transform: uppercase;
      color: var(--b4);
      margin-bottom: 1.5rem;
    }

    .rustic-rule {
      display: flex;
      align-items: center;
      gap: 14px;
      justify-content: center;
      margin: 1.5rem auto;
    }

    .rustic-rule::before,
    .rustic-rule::after {
      content: '';
      flex: 1;
      max-width: 80px;
      height: 1px;
      background: var(--b5);
    }

    .rustic-rule-center {
      color: var(--b4);
      font-size: .9rem;
      letter-spacing: .3em;
    }

    /* ══════════════════════
       PEMBUKA
    ══════════════════════ */
    .pembuka-section {
      background: var(--b6);
    }

    .ayat-frame {
      max-width: 540px;
      margin: 2rem auto 0;
      padding: 2.5rem 2rem;
      background: var(--white);
      border: 1px solid var(--b5);
      border-top: 4px solid var(--b3);
      position: relative;
    }

    .ayat-frame::before {
      content: '"';
      position: absolute;
      top: -28px;
      left: 2rem;
      font-family: 'EB Garamond', serif;
      font-size: 5rem;
      color: var(--b5);
      line-height: 1;
    }

    .ayat-frame p {
      font-family: 'EB Garamond', serif;
      font-style: italic;
      font-size: 15px;
      color: var(--gray);
      line-height: 2.1;
      margin-bottom: .75rem;
    }

    .ayat-frame cite {
      font-family: 'Cinzel', serif;
      font-size: 10px;
      letter-spacing: .15em;
      text-transform: uppercase;
      color: var(--b3);
    }

    /* ══════════════════════
       COUPLE
    ══════════════════════ */
    .couple-section {
      background: var(--white);
    }

    .couple-grid {
      display: grid;
      grid-template-columns: 1fr auto 1fr;
      gap: 2rem;
      align-items: center;
      margin: 2.5rem 0;
    }

    .couple-avatar {
      width: 115px;
      height: 115px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--b1), var(--b2));
      margin: 0 auto 1.25rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Cinzel', serif;
      font-size: 2.2rem;
      color: var(--b5);
      border: 4px solid var(--b6);
      box-shadow: 0 8px 32px rgba(44, 26, 14, .2);
    }

    .couple-card h3 {
      font-family: 'Cinzel', serif;
      font-size: 1.15rem;
      letter-spacing: .08em;
      color: var(--b1);
      margin-bottom: 6px;
    }

    .couple-card .sub {
      font-family: 'EB Garamond', serif;
      font-style: italic;
      font-size: 13px;
      color: var(--gray);
      line-height: 1.8;
    }

    .couple-card .ortu {
      font-family: 'EB Garamond', serif;
      font-size: 14px;
      color: var(--b2);
      margin-top: 3px;
    }

    .amp-rustic {
      font-family: 'EB Garamond', serif;
      font-style: italic;
      font-size: 3.5rem;
      color: var(--b5);
      line-height: 1;
      text-align: center;
    }

    /* ══════════════════════
       OUR STORY
    ══════════════════════ */
    .story-section {
      background: var(--b7);
    }

    .story-timeline {
      max-width: 560px;
      margin: 2.5rem auto 0;
      text-align: left;
    }

    .story-item {
      display: flex;
      gap: 1.5rem;
      margin-bottom: 2rem;
      position: relative;
    }

    .story-item:not(:last-child)::after {
      content: '';
      position: absolute;
      left: 20px;
      top: 44px;
      bottom: -2rem;
      width: 1px;
      background: var(--b5);
    }

    .story-dot {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--b2), var(--b3));
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      flex-shrink: 0;
      border: 3px solid var(--b6);
      box-shadow: 0 4px 12px rgba(44, 26, 14, .15);
    }

    .story-dot i {
      color: var(--b5);
    }

    .story-body {
      flex: 1;
    }

    .story-year {
      font-family: 'Cinzel', serif;
      font-size: 10px;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: var(--b3);
      margin-bottom: 4px;
    }

    .story-title {
      font-family: 'Cinzel', serif;
      font-size: 14px;
      letter-spacing: .06em;
      color: var(--b1);
      margin-bottom: 6px;
    }

    .story-desc {
      font-family: 'EB Garamond', serif;
      font-style: italic;
      font-size: 14px;
      color: var(--gray);
      line-height: 1.8;
    }

    /* ══════════════════════
       COUNTDOWN
    ══════════════════════ */
    .countdown-section {
      background: linear-gradient(160deg, var(--b1) 0%, var(--b2) 100%);
      position: relative;
      overflow: hidden;
    }

    .countdown-section::before {
      content: '';
      position: absolute;
      inset: 0;
      background:
        repeating-linear-gradient(90deg,
          transparent 0px, transparent 4px,
          rgba(255, 255, 255, .008) 4px, rgba(255, 255, 255, .008) 5px);
    }

    .countdown-section .cinzel-title {
      color: var(--b5);
      position: relative;
    }

    .countdown-section .cinzel-label {
      color: rgba(196, 168, 130, .45);
      position: relative;
    }

    .countdown-section .garamond-sub {
      color: rgba(255, 255, 255, .5);
      position: relative;
      font-size: 1.1rem;
    }

    .cd-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      max-width: 420px;
      margin: 2rem auto 0;
      position: relative;
    }

    .cd-box {
      background: rgba(255, 255, 255, .07);
      border: 1px solid rgba(196, 168, 130, .2);
      padding: 1.5rem .75rem;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .cd-box::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, transparent, var(--gold), transparent);
    }

    .cd-num {
      font-family: 'Cinzel', serif;
      font-size: 2.4rem;
      color: var(--white);
      line-height: 1;
      font-weight: 700;
    }

    .cd-lbl {
      font-family: 'Cinzel', serif;
      font-size: 9px;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: var(--b4);
      margin-top: 6px;
    }

    /* ══════════════════════
       DETAIL ACARA
    ══════════════════════ */
    .detail-section {
      background: var(--b6);
    }

    .event-cards {
      display: grid;
      gap: 1.25rem;
    }

    .event-card {
      background: var(--white);
      border: 1px solid var(--b5);
      padding: 2rem;
      text-align: left;
      position: relative;
      border-left: 4px solid var(--b3);
    }

    .event-card::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 0;
      height: 0;
      border-top: 40px solid var(--b6);
      border-left: 40px solid transparent;
    }

    .event-header {
      display: flex;
      gap: 14px;
      align-items: flex-start;
      margin-bottom: 1.25rem;
      padding-bottom: 1rem;
      border-bottom: 1px dashed var(--b5);
    }

    .event-icon {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, var(--b6), var(--b5));
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      flex-shrink: 0;
      border: 1px solid var(--b5);
    }

    .event-header h3 {
      font-family: 'Cinzel', serif;
      font-size: 14px;
      letter-spacing: .08em;
      color: var(--b1);
      margin-bottom: 4px;
    }

    .event-header p {
      font-family: 'EB Garamond', serif;
      font-style: italic;
      font-size: 13px;
      color: var(--b3);
    }

    .event-row {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      font-size: 14px;
      margin-bottom: .6rem;
    }

    .event-row .ico {
      color: var(--b3);
      font-size: 16px;
      flex-shrink: 0;
      margin-top: 2px;
    }

    .event-row strong {
      font-family: 'Cinzel', serif;
      font-size: 11px;
      letter-spacing: .06em;
      color: var(--dark);
      min-width: 70px;
    }

    .event-row span {
      font-family: 'EB Garamond', serif;
      color: var(--gray);
      line-height: 1.6;
    }

    .btn-maps {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      margin-top: 1.25rem;
      padding: 10px 22px;
      background: var(--b2);
      color: var(--white);
      text-decoration: none;
      font-family: 'Cinzel', serif;
      font-size: 10px;
      letter-spacing: .2em;
      text-transform: uppercase;
      transition: background .2s;
    }

    .btn-maps:hover {
      background: var(--b1);
    }

    /* ══════════════════════
       AMPLOP DIGITAL
    ══════════════════════ */
    .amplop-section {
      background: var(--white);
    }

    .amplop-intro {
      font-family: 'EB Garamond', serif;
      font-style: italic;
      font-size: 15px;
      color: var(--gray);
      line-height: 1.9;
      max-width: 500px;
      margin: 0 auto 2.5rem;
    }

    .amplop-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1.25rem;
      max-width: 560px;
      margin: 0 auto;
    }

    .amplop-card {
      background: var(--b7);
      border: 1px solid var(--b5);
      padding: 1.75rem 1.5rem;
      text-align: center;
      border-top: 3px solid var(--b3);
      position: relative;
      overflow: hidden;
    }

    .amplop-card::before {
      content: '';
      position: absolute;
      bottom: 0;
      right: 0;
      width: 60px;
      height: 60px;
      background: radial-gradient(circle, var(--b6) 0%, transparent 70%);
    }

    .amplop-bank-logo {
      width: 52px;
      height: 52px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--b2), var(--b3));
      margin: 0 auto 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      color: var(--white);
    }

    .amplop-card h3 {
      font-family: 'Cinzel', serif;
      font-size: 13px;
      letter-spacing: .1em;
      color: var(--b1);
      margin-bottom: .5rem;
    }

    .amplop-card .rekening {
      font-family: 'Cinzel', serif;
      font-size: 16px;
      color: var(--b2);
      letter-spacing: .08em;
      margin: .4rem 0;
      font-weight: 600;
    }

    .amplop-card .atas-nama {
      font-family: 'EB Garamond', serif;
      font-style: italic;
      font-size: 13px;
      color: var(--gray);
    }

    .btn-salin {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: 1rem;
      padding: 8px 18px;
      background: var(--b2);
      color: var(--white);
      border: none;
      cursor: pointer;
      font-family: 'Cinzel', serif;
      font-size: 10px;
      letter-spacing: .15em;
      text-transform: uppercase;
      transition: background .2s;
      width: 100%;
      justify-content: center;
    }

    .btn-salin:hover {
      background: var(--b1);
    }

    .btn-salin.copied {
      background: #2e8b57;
    }

    .amplop-note {
      font-family: 'EB Garamond', serif;
      font-style: italic;
      font-size: 13px;
      color: var(--b4);
      margin-top: 1.5rem;
    }

    /* ══════════════════════
       RSVP
    ══════════════════════ */
    .rsvp-section {
      background: var(--b6);
    }

    .rsvp-wrap {
      max-width: 480px;
      margin: 2rem auto 0;
      text-align: left;
    }

    .rsvp-field {
      margin-bottom: 1.1rem;
    }

    .rsvp-field label {
      display: block;
      font-family: 'Cinzel', serif;
      font-size: 10px;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: var(--b2);
      margin-bottom: 6px;
    }

    .rsvp-field input,
    .rsvp-field select,
    .rsvp-field textarea {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid var(--b5);
      font-size: 15px;
      font-family: 'EB Garamond', serif;
      color: var(--dark);
      background: var(--white);
      outline: none;
      transition: border-color .2s;
    }

    .rsvp-field input:focus,
    .rsvp-field select:focus,
    .rsvp-field textarea:focus {
      border-color: var(--b3);
      box-shadow: 0 0 0 3px rgba(92, 61, 30, .08);
    }

    .rsvp-field textarea {
      resize: vertical;
      min-height: 90px;
    }

    .btn-rsvp {
      width: 100%;
      padding: 14px;
      background: var(--b1);
      color: var(--white);
      border: none;
      font-family: 'Cinzel', serif;
      font-size: 11px;
      letter-spacing: .25em;
      text-transform: uppercase;
      cursor: pointer;
      transition: background .2s;
    }

    .btn-rsvp:hover {
      background: var(--b2);
    }

    .rsvp-success {
      display: none;
      text-align: center;
      padding: 2.5rem 1rem;
    }

    .rsvp-success .icon-check {
      width: 68px;
      height: 68px;
      background: linear-gradient(135deg, var(--b6), var(--b5));
      border: 2px solid var(--b4);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      margin: 0 auto 1.25rem;
      border-radius: 50%;
    }

    .rsvp-success h3 {
      font-family: 'Cinzel', serif;
      font-size: 1.2rem;
      letter-spacing: .08em;
      color: var(--b1);
      margin-bottom: .5rem;
    }

    .rsvp-success p {
      font-family: 'EB Garamond', serif;
      font-style: italic;
      font-size: 15px;
      color: var(--gray);
    }

    /* ══════════════════════
       PENUTUP
    ══════════════════════ */
    .closing-section {
      background: var(--b1);
      text-align: center;
      padding: 7rem 1.5rem;
      position: relative;
      overflow: hidden;
    }

    .closing-section::before {
      content: '';
      position: absolute;
      inset: 0;
      background:
        repeating-linear-gradient(92deg,
          transparent 0px, transparent 3px,
          rgba(255, 255, 255, .01) 3px, rgba(255, 255, 255, .01) 4px),
        radial-gradient(ellipse at 50% 100%, rgba(139, 99, 64, .3) 0%, transparent 60%);
    }

    .closing-bg-text {
      position: absolute;
      font-family: 'Cinzel', serif;
      font-size: 18vw;
      color: rgba(255, 255, 255, .025);
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      white-space: nowrap;
      pointer-events: none;
      letter-spacing: .1em;
    }

    .closing-section .cinzel-title {
      color: var(--b5);
      position: relative;
      font-size: clamp(1.2rem, 3vw, 1.8rem);
    }

    .closing-names {
      font-family: 'Cinzel', serif;
      font-size: clamp(1.5rem, 4vw, 2.5rem);
      color: var(--gold);
      letter-spacing: .1em;
      position: relative;
      margin: 1rem 0;
    }

    .closing-section p {
      font-family: 'EB Garamond', serif;
      font-style: italic;
      font-size: 15px;
      color: rgba(255, 255, 255, .5);
      line-height: 1.9;
      max-width: 460px;
      margin: 1.5rem auto;
      position: relative;
    }

    .closing-section .rustic-rule::before,
    .closing-section .rustic-rule::after {
      background: rgba(196, 168, 130, .25);
    }

    .closing-section .rustic-rule-center {
      color: rgba(196, 168, 130, .4);
    }

    .closing-brand {
      position: relative;
      margin-top: 3.5rem;
      font-family: 'Cinzel', serif;
      font-size: 10px;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, .18);
    }

    .closing-brand span {
      color: var(--b4);
    }

    /* ══════════════════════
       MUSIC BTN
    ══════════════════════ */
    .music-btn {
      position: fixed;
      bottom: 1.5rem;
      right: 1.5rem;
      z-index: 999;
      width: 48px;
      height: 48px;
      background: var(--b2);
      color: var(--b5);
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      cursor: pointer;
      box-shadow: 0 4px 20px rgba(44, 26, 14, .4);
      transition: transform .2s, background .2s;
    }

    .music-btn:hover {
      transform: scale(1.08);
      background: var(--b1);
    }

    /* ══════════════════════
       TOAST
    ══════════════════════ */
    .toast {
      position: fixed;
      bottom: 5rem;
      left: 50%;
      transform: translateX(-50%) translateY(20px);
      background: var(--b1);
      color: var(--b5);
      font-family: 'Cinzel', serif;
      font-size: 11px;
      letter-spacing: .15em;
      padding: 10px 22px;
      opacity: 0;
      transition: all .3s;
      pointer-events: none;
      white-space: nowrap;
      z-index: 9999;
    }

    .toast.show {
      opacity: 1;
      transform: translateX(-50%) translateY(0);
    }

    /* ── GALERI FOTO ── */
    .galeri-section {
      padding: 5rem 1.5rem;
    }

    /* Tentukan background sesuai tema:
      Merah Klasik : background: #fff;
      Navy Elegant : background: var(--white);
      Blush Pink   : background: var(--cream);
      Sage Garden  : background: var(--white);
      Rustic Brown : background: var(--b7);
    */

    .galeri-grid-foto {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      margin-top: 2rem;
      max-width: 680px;
      margin-left: auto;
      margin-right: auto;
    }

    /* Foto pertama lebih besar */
    .galeri-grid-foto .gf-item:first-child {
      grid-column: span 2;
      grid-row: span 2;
    }

    .gf-item {
      position: relative;
      border-radius: 8px;
      overflow: hidden;
      aspect-ratio: 1;
      background: #ffff;
      cursor: pointer;
    }

    .gf-item:first-child {
      aspect-ratio: auto;
      min-height: 200px;
    }

    .gf-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform 0.4s ease;
    }

    .gf-item:hover img {
      transform: scale(1.05);
    }

    .gf-caption-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(transparent, rgba(0, 0, 0, 0.6));
      padding: 1.5rem .75rem .75rem;
      opacity: 0;
      transition: opacity .3s;
    }

    .gf-item:hover .gf-caption-overlay {
      opacity: 1;
    }

    .gf-caption-overlay span {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.9);
      font-style: italic;
    }

    /* Lightbox */
    .lightbox-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.92);
      z-index: 9999;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      padding: 1rem;
    }

    .lightbox-overlay.show {
      display: flex;
    }

    .lightbox-img {
      max-width: 90vw;
      max-height: 80vh;
      border-radius: 8px;
      object-fit: contain;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }

    .lightbox-caption {
      color: rgba(255, 255, 255, 0.7);
      font-size: 14px;
      font-style: italic;
      margin-top: 1rem;
      text-align: center;
    }

    .lightbox-nav {
      position: fixed;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
      border: none;
      width: 44px;
      height: 44px;
      border-radius: 50%;
      font-size: 20px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background .2s;
    }

    .lightbox-nav:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    .lightbox-prev {
      left: 1rem;
    }

    .lightbox-next {
      right: 1rem;
    }

    .lightbox-close {
      position: fixed;
      top: 1rem;
      right: 1rem;
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
      border: none;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      font-size: 20px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .lightbox-counter {
      position: fixed;
      top: 1rem;
      left: 50%;
      transform: translateX(-50%);
      color: rgba(255, 255, 255, 0.5);
      font-size: 13px;
    }

    @media(max-width:560px) {
      .couple-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
      }

      .amp-rustic {
        font-size: 2.5rem;
      }

      .cd-grid {
        grid-template-columns: repeat(2, 1fr);
      }

      .amplop-grid {
        grid-template-columns: 1fr;
      }

      .galeri-grid-foto {
        grid-template-columns: repeat(2, 1fr);
      }

      .gf-item:first-child {
        grid-column: span 2;
      }
    }
  </style>
</head>

<body>

  <!-- ══ COVER ══ -->
  <div class="cover" id="cover">
    <div class="cover-border"></div>
    <span class="flr flr-tl">❧</span>
    <span class="flr flr-tr">❧</span>
    <span class="flr flr-bl">❧</span>
    <span class="flr flr-br">❧</span>
    <div class="cover-monogram"><?= strtoupper(substr($pria, 0, 1) . substr($wanita, 0, 1)) ?></div>
    <div class="cover-stamp">The Wedding Of</div>
    <div class="cover-name"><?= strtoupper($pria) ?></div>
    <div class="cover-amp">&amp;</div>
    <div class="cover-name-w"><?= strtoupper($wanita) ?></div>
    <div class="cover-ornament">
      <span>✦</span><span>✦</span><span>✦</span>
    </div>
    <div class="cover-date"><?= $tgl_full ?></div>
    <div class="cover-to-lbl">Kepada Yang Terhormat</div>
    <div class="cover-guest"><?= htmlspecialchars($tamu) ?></div>
    <button class="btn-open" onclick="bukaUndangan()"><i class='bx bx-envelope'></i> &nbsp; Buka Undangan</button>
  </div>

  <!-- ══ CONTENT ══ -->
  <div class="content" id="mainContent">

    <!-- PEMBUKA -->
    <section class="pembuka-section">
      <div class="sec-inner">
        <div class="garamond-sub" style="font-size:clamp(1.5rem,4vw,2.2rem)">Bismillahirrahmanirrahim</div>
        <div class="cinzel-label">Dengan Nama Allah Yang Maha Pengasih Lagi Maha Penyayang</div>
        <div class="rustic-rule">
          <div class="rustic-rule-center">✦</div>
        </div>
        <div class="ayat-frame">
          <p>Dan di antara tanda-tanda kebesaran-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan dijadikan-Nya di antaramu rasa kasih dan sayang.</p>
          <cite>— QS. Ar-Rum : 21</cite>
        </div>
      </div>
    </section>

    <!-- COUPLE -->
    <section class="couple-section">
      <div class="sec-inner">
        <div class="cinzel-title">Mempelai</div>
        <div class="cinzel-label">Yang Berbahagia</div>
        <div class="rustic-rule">
          <div class="rustic-rule-center">✦</div>
        </div>
        <div class="couple-grid">
          <div class="couple-card">
            <div class="couple-avatar"><?= strtoupper(substr($pria, 0, 1)) ?></div>
            <h3><?= strtoupper($pria) ?></h3>
            <div class="sub">Putra dari</div>
            <div class="ortu"><?= $ayah_pria ?> &amp; <?= $ibu_pria ?></div>
          </div>
          <div class="amp-rustic">&amp;</div>
          <div class="couple-card">
            <div class="couple-avatar" style="background:linear-gradient(135deg,var(--b2),var(--b3))"><?= strtoupper(substr($wanita, 0, 1)) ?></div>
            <h3><?= strtoupper($wanita) ?></h3>
            <div class="sub">Putri dari</div>
            <div class="ortu"><?= $ayah_wanita ?> &amp; <?= $ibu_wanita ?></div>
          </div>
        </div>
      </div>
    </section>

    <!-- OUR STORY -->
    <section class="story-section">
      <div class="sec-inner">
        <div class="cinzel-title">Our Story</div>
        <div class="cinzel-label">Perjalanan Cinta Kami</div>
        <div class="rustic-rule">
          <div class="rustic-rule-center">✦</div>
        </div>
        <div class="story-timeline">
          <div class="story-item">
            <div class="story-dot"><i class='bx bx-map-alt'></i></div>
            <div class="story-body">
              <div class="story-year">Pertemuan Pertama</div>
              <div class="story-title">Takdir Mempertemukan Kami</div>
              <div class="story-desc">Siapa sangka pertemuan sederhana di sebuah tempat bisa mengubah seluruh cerita hidup kami. Dari seorang asing menjadi orang yang paling berarti.</div>
            </div>
          </div>
          <div class="story-item">
            <div class="story-dot"><i class='bx bxs-message-dots'></i></div>
            <div class="story-body">
              <div class="story-year">Mengenal Lebih Dalam</div>
              <div class="story-title">Dari Teman Menjadi Lebih</div>
              <div class="story-desc">Setiap percakapan membawa kami semakin dekat. Tawa, cerita, dan mimpi perlahan menjadi milik bersama yang tak ingin kami lepaskan.</div>
            </div>
          </div>
          <div class="story-item">
            <div class="story-dot"><i class='bx bx-male-female'></i></div>
            <div class="story-body">
              <div class="story-year">Lamaran</div>
              <div class="story-title">Satu Pertanyaan, Satu Jawaban</div>
              <div class="story-desc">Dengan segenap keberanian dan doa, sebuah pertanyaan terlantun. Dan jawaban "iya" itu menjadi momen paling bersejarah dalam hidup kami.</div>
            </div>
          </div>
          <div class="story-item">
            <div class="story-dot"><i class='bx bxs-leaf'></i></div>
            <div class="story-body">
              <div class="story-year"><?= $tgl_full ?></div>
              <div class="story-title">Hari Yang Ditunggu</div>
              <div class="story-desc">Dan kini tibalah saatnya. Kami mengundangmu untuk menyaksikan dan mendoakan dimulainya babak baru dalam perjalanan cinta kami.</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- COUNTDOWN -->
    <section class="countdown-section">
      <div class="sec-inner">
        <div class="cinzel-title">Menuju Hari Bahagia</div>
        <div class="cinzel-label" style="color:rgba(196,168,130,.45);position:relative">Hitung Mundur</div>
        <div class="garamond-sub" style="position:relative"><?= $tgl_full ?></div>
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

    <!-- DETAIL ACARA -->
    <section class="detail-section">
      <div class="sec-inner">
        <div class="cinzel-title">Rangkaian Acara</div>
        <div class="cinzel-label">Informasi Pernikahan</div>
        <div class="rustic-rule">
          <div class="rustic-rule-center">✦</div>
        </div>
        <div class="event-cards">
          <div class="event-card">
            <div class="event-header">
              <div class="event-icon"><i class='bx bx-male-female'></i></div>
              <div>
                <h3>Akad Nikah</h3>
                <p>Prosesi Ijab Kabul</p>
              </div>
            </div>
            <div class="event-row"><span class="ico"><i class='bx bx-calendar'></i></span><strong>Tanggal</strong><span><?= $tgl_full ?></span></div>
            <div class="event-row"><span class="ico"><i class='bx bx-time'></i></span><strong>Waktu</strong><span><?= $ma ?> – <?= $sa ?> WIB</span></div>
            <div class="event-row"><span class="ico"><i class='bx bx-map-pin'></i></span><strong>Lokasi</strong><span><?= $lokasi ?></span></div>
            <a href="<?= $maps ?>" target="_blank" class="btn-maps"><i class='bx bx-map'></i> Buka Google Maps</a>
          </div>
          <div class="event-card">
            <div class="event-header">
              <div class="event-icon"><i class='bx bxs-leaf'></i></div>
              <div>
                <h3>Resepsi Pernikahan</h3>
                <p>Syukuran &amp; Jamuan Tamu</p>
              </div>
            </div>
            <div class="event-row"><span class="ico"><i class='bx bx-calendar'></i></span><strong>Tanggal</strong><span><?= $tgl_full ?></span></div>
            <div class="event-row"><span class="ico"><i class='bx bx-time'></i></span><strong>Waktu</strong><span><?= $mr ?> – <?= $sr ?> WIB</span></div>
            <div class="event-row"><span class="ico"><i class='bx bx-map-pin'></i></span><strong>Lokasi</strong><span><?= $lokasi ?></span></div>
            <a href="<?= $maps ?>" target="_blank" class="btn-maps"><i class='bx bx-map'></i> Buka Google Maps</a>
          </div>
        </div>
      </div>
    </section>


      <?php
      // Ensure variables exist to avoid undefined variable notices when this snippet
      // is included standalone.
      if (!isset($galeri_fotos) || !is_array($galeri_fotos)) {
        $galeri_fotos = [];
      }
      if (!isset($ada_galeri)) {
        $ada_galeri = !empty($galeri_fotos);
      }
      ?>
    <!-- SECTION GALERI FOTO (PHP) -->
    <?php if ($ada_galeri): ?>
      <section class="galeri-section">
        <div class="sec-inner">
          <!--
        Sesuaikan class heading dengan tema:
        - Merah Klasik : <div class="ornament"> + <div class="sec-label">
        - Navy Elegant : <div class="cinzel-title"> + <div class="gold-line">
        - Blush Pink   : <div class="script-title">
        - Sage Garden  : <div class="baskerville-heading"> + <div class="lato-label">
        - Rustic Brown : <div class="cinzel-title"> + <div class="cinzel-label">
        -->
          <div class="cinzel-title">Galeri</div>
          <div class="cinzel-label">Momen Berharga Kami</div>
          <div class="galeri-grid-foto">
            <?php foreach ($galeri_fotos as $idx => $gf): ?>
              <div class="gf-item" onclick="bukaLightbox(<?= $idx ?>)">
                <img
                  src="<?= htmlspecialchars($gf['path_file']) ?>"
                  alt="Foto <?= $idx + 1 ?>"
                  loading="lazy" />
                <?php if ($gf['caption']): ?>
                  <div class="gf-caption-overlay">
                    <span><?= htmlspecialchars($gf['caption']) ?></span>
                  </div>
                <?php endif ?>
              </div>
            <?php endforeach ?>
          </div>
        </div>
      </section>
    <?php endif ?>
        
    <!-- LIGHTBOX -->
    <div class="lightbox-overlay" id="lightbox" onclick="tutupLightbox(event)">
      <span class="lightbox-counter" id="lbCounter"></span>
      <img class="lightbox-img" id="lbImg" src="" alt=""/>
      <div class="lightbox-caption" id="lbCaption"></div>
      <button class="lightbox-nav lightbox-prev" onclick="lbNav(-1);event.stopPropagation()">‹</button>
      <button class="lightbox-nav lightbox-next" onclick="lbNav(1);event.stopPropagation()">›</button>
      <button class="lightbox-close" onclick="tutupLightbox()">×</button>
    </div>

    <!-- AMPLOP DIGITAL -->
    <section class="amplop-section">
      <div class="sec-inner">
        <div class="cinzel-title">Amplop Digital</div>
        <div class="cinzel-label">Hadiah &amp; Tanda Kasih</div>
        <div class="rustic-rule">
          <div class="rustic-rule-center">✦</div>
        </div>
        <p class="amplop-intro">Doa restu Bapak/Ibu/Saudara/i adalah hadiah terindah bagi kami. Namun bagi yang ingin memberikan tanda kasih, kami menyediakan rekening berikut.</p>
        <div class="amplop-grid">
          <div class="amplop-card">
            <div class="amplop-bank-logo"><i class='bx bx-credit-card'></i></div>
            <h3>Bank BCA</h3>
            <div class="rekening" id="norek-bca">1234567890</div>
            <div class="atas-nama">a.n. <?= $pria ?></div>
            <button class="btn-salin" id="btn-bca" onclick="salinRekening('1234567890','btn-bca')">
              <i class='bx bx-copy'></i> &nbsp; Salin Nomor
            </button>
          </div>
          <div class="amplop-card">
            <div class="amplop-bank-logo"><i class='bx bx-credit-card'></i></div>
            <h3>DANA/GoPay/OVO</h3>
            <div class="rekening" id="norek-gopay">0812-3456-789</div>
            <div class="atas-nama">a.n. <?= $wanita ?></div>
            <button class="btn-salin" id="btn-gopay" onclick="salinRekening('08123456789','btn-gopay')">
              <i class='bx bx-copy'></i> &nbsp; Salin Nomor
            </button>
          </div>
        </div>
        <p class="amplop-note">* Konfirmasi transfer via WhatsApp ke nomor admin</p>
      </div>
    </section>

    <!-- RSVP -->
    <section class="rsvp-section">
      <div class="sec-inner">
        <div class="cinzel-title">Konfirmasi Kehadiran</div>
        <div class="cinzel-label">RSVP</div>
        <div class="rustic-rule">
          <div class="rustic-rule-center">✦</div>
        </div>
        <p style="font-family:'EB Garamond',serif;font-style:italic;font-size:15px;color:var(--gray);margin-bottom:.5rem">
          Mohon konfirmasi kehadiran Anda paling lambat 7 hari sebelum acara
        </p>
        <div class="rsvp-wrap" id="rsvpForm">
          <div class="rsvp-field">
            <label>Nama Lengkap</label>
            <input type="text" id="rsvpNama" value="<?= htmlspecialchars($tamu) ?>" placeholder="Nama lengkap kamu..." />
          </div>
          <div class="rsvp-field">
            <label>Jumlah Tamu</label>
            <select id="rsvpJml">
              <option>1 orang</option>
              <option>2 orang</option>
              <option>3 orang</option>
              <option>4+ orang</option>
            </select>
          </div>
          <div class="rsvp-field">
            <label>Konfirmasi Kehadiran</label>
            <select id="rsvpHadir">
              <option value="hadir">✔️ Insya Allah Hadir</option>
              <option value="tidak">❌ Berhalangan Hadir</option>
              <option value="mungkin">❓ Mungkin Hadir</option>
            </select>
          </div>
          <div class="rsvp-field">
            <label>Ucapan &amp; Doa</label>
            <textarea id="rsvpUcapan" placeholder="Tuliskan ucapan dan doa terbaik untuk kedua mempelai..."></textarea>
          </div>
          <button class="btn-rsvp" onclick="kirimRSVP()">Kirim Konfirmasi</button>
        </div>
        <div class="rsvp-success" id="rsvpSuccess">
          <div class="icon-check">✓</div>
          <h3>Terima Kasih</h3>
          <p>Konfirmasi dan ucapanmu telah kami terima dengan senang hati.<br>Sampai jumpa di hari bahagia kami.</p>
        </div>
      </div>
    </section>

    <!-- PENUTUP -->
    <section class="closing-section">
      <div class="sec-inner" style="position:relative">
        <div class="closing-bg-text"><?= strtoupper(substr($pria, 0, 1) . substr($wanita, 0, 1)) ?></div>
        <div class="cinzel-label" style="color:rgba(196,168,130,.35);position:relative">Dengan Segenap Cinta</div>
        <div class="closing-names"><?= strtoupper($pria) ?> &amp; <?= strtoupper($wanita) ?></div>
        <div class="rustic-rule" style="position:relative">
          <div class="rustic-rule-center" style="color:rgba(196,168,130,.3)">✦</div>
        </div>
        <p>Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu. Atas segala kehadiran dan doa yang tulus, kami mengucapkan terima kasih yang sebesar-besarnya.</p>
        <p style="font-family:'Cinzel',serif;font-style:normal;font-size:13px;color:rgba(255,255,255,.4);letter-spacing:.1em;margin-top:2rem"><?= $tgl_full ?></p>
        <div class="closing-brand">Dibuat dengan ♥ oleh <span>Bernada.ID</span></div>
      </div>
    </section>

  </div><!-- end content -->

  <button class="music-btn" id="musicBtn" onclick="toggleMusic()"><i class='bx bx-music'></i></button>
  <audio id="bgMusic" loop>
    <source src="../audio/wedding-music.mp3" type="audio/mpeg" />
  </audio>
  <div class="toast" id="toast">Nomor berhasil disalin!</div>

  <script>
    // Buka undangan
    function bukaUndangan() {
      document.getElementById('cover').style.display = 'none';
      document.getElementById('mainContent').classList.add('show');
      document.getElementById('bgMusic').play().catch(() => {});
    }

    // Toggle musik
    let playing = false;

    function toggleMusic() {
      const m = document.getElementById('bgMusic');
      const btn = document.getElementById('musicBtn');
      if (playing) {
        m.pause();
        btn.innerHTML = "<i class='bx bx-music' ></i>";
        playing = false;
      } else {
        m.play().catch(() => {});
        btn.textContent = "⏸";
        playing = true;
      }
    }

    const target = new Date("<?= $tgl_countdown ?>").getTime();

    function updateCountdown() {
      const now = Date.now();
      const diff = target - now;

      if (diff <= 0) {
        document.getElementById('cd-h').textContent = '00';
        document.getElementById('cd-j').textContent = '00';
        document.getElementById('cd-m').textContent = '00';
        document.getElementById('cd-s').textContent = '00';
        return;
      }

      const h = Math.floor(diff / 86400000);
      const j = Math.floor((diff % 86400000) / 3600000);
      const m = Math.floor((diff % 3600000) / 60000);
      const s = Math.floor((diff % 60000) / 1000);

      document.getElementById('cd-h').textContent = String(h).padStart(2, '0');
      document.getElementById('cd-j').textContent = String(j).padStart(2, '0');
      document.getElementById('cd-m').textContent = String(m).padStart(2, '0');
      document.getElementById('cd-s').textContent = String(s).padStart(2, '0');
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();

    // Salin rekening
    function salinRekening(nomor, btnId) {
      navigator.clipboard.writeText(nomor).then(() => {
        const btn = document.getElementById(btnId);
        const ori = btn.innerHTML;
        btn.innerHTML = '✓ &nbsp; Tersalin!';
        btn.classList.add('copied');
        const toast = document.getElementById('toast');
        toast.classList.add('show');
        setTimeout(() => {
          btn.innerHTML = ori;
          btn.classList.remove('copied');
          toast.classList.remove('show');
        }, 2500);
      });
    }

    // RSVP
    function kirimRSVP() {
      const nama = document.getElementById('rsvpNama').value.trim();
      if (!nama) {
        alert('Mohon isi nama kamu terlebih dahulu.');
        return;
      }
      const jml = document.getElementById('rsvpJml').value;
      const hadir = document.getElementById('rsvpHadir').value === 'hadir' ? 'Insya Allah Hadir' : document.getElementById('rsvpHadir').value === 'mungkin' ? 'Mungkin Hadir' : 'Berhalangan Hadir';
      const ucapan = document.getElementById('rsvpUcapan').value.trim();
      const pesan = encodeURIComponent(
        `Assalamu'alaikum \n\nSaya ${nama} ingin mengkonfirmasi kehadiran di pernikahan ${`<?= $pria ?>`} & ${`<?= $wanita ?>`}.\n\n` +
        `Kehadiran   : ${status}\nJumlah Tamu : ${jml}` +
        (ucapan ? `\n\nUcapan & Doa :\n"${ucapan}"` : '')
      );
      window.open(`https://wa.me/6281939195110?text=${pesan}`, '_blank');
      document.getElementById('rsvpForm').style.display = 'none';
      document.getElementById('rsvpSuccess').style.display = 'block';
    }


    const lbFotos = <?= json_encode(array_map(fn($f) => [
                      'src'     => $f['path_file'],
                      'caption' => $f['caption'] ?? ''
                    ], $galeri_fotos)) ?>;
    let lbIdx = 0;

    function bukaLightbox(idx) {
      lbIdx = idx;
      tampilLb();
      document.getElementById('lightbox').classList.add('show');
      document.body.style.overflow = 'hidden';
    }

    function tampilLb() {
      const f = lbFotos[lbIdx];
      document.getElementById('lbImg').src = f.src;
      document.getElementById('lbCaption').textContent = f.caption;
      document.getElementById('lbCounter').textContent = (lbIdx + 1) + ' / ' + lbFotos.length;
    }

    function lbNav(dir) {
      lbIdx = (lbIdx + dir + lbFotos.length) % lbFotos.length;
      tampilLb();
    }

    function tutupLightbox(e) {
      if (e && e.target !== document.getElementById('lightbox') && !e.target.classList.contains('lightbox-close')) return;
      document.getElementById('lightbox').classList.remove('show');
      document.body.style.overflow = '';
    }
    // Keyboard navigation
    document.addEventListener('keydown', e => {
      if (!document.getElementById('lightbox').classList.contains('show')) return;
      if (e.key === 'ArrowLeft') lbNav(-1);
      if (e.key === 'ArrowRight') lbNav(1);
      if (e.key === 'Escape') tutupLightbox({
        target: document.getElementById('lightbox')
      });
    });
  </script>
</body>

</html>