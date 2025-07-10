<?php
session_start();
if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kalender Booking (Admin)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="../gambar/logo.png" />
  <style>
    body {
      font-family: 'Quicksand', Arial, sans-serif;
      background: linear-gradient(135deg, #e3f0ff 0%, #f8fdff 100%);
      min-height: 100vh;
      overflow-x: hidden;
    }
    .glass-admin {
      background: rgba(255,255,255,0.85);
      box-shadow: 0 8px 32px 0 rgba(0,150,255,0.10);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border-radius: 20px;
      border: 1px solid rgba(0,150,255,0.10);
    }
    .navbar {
      background: rgba(0, 150, 255, 0.10) !important;
      backdrop-filter: blur(8px);
      border-bottom: 1px solid rgba(0,150,255,0.10);
    }
    .navbar-brand {
      font-weight: bold;
      letter-spacing: 2px;
      font-size: 1.5rem;
      color: #0077c2 !important;
    }
    .btn-glass-admin {
      background: rgba(255,255,255,0.18) !important;
      color: #039be5 !important;
      border: 1.5px solid #81d4fa !important;
      border-radius: 30px;
      font-weight: 600;
      font-size: 1.05rem;
      box-shadow: 0 2px 12px rgba(3,155,229,0.10);
      transition: background 0.2s, color 0.2s, box-shadow 0.2s;
      backdrop-filter: blur(4px);
      opacity: 0.92;
    }
    .btn-glass-admin:hover, .btn-glass-admin:focus {
      background: rgba(3,155,229,0.12) !important;
      color: #fff !important;
      box-shadow: 0 6px 24px rgba(3,155,229,0.18);
      opacity: 1;
    }
    .glass-content-admin {
      background: rgba(255,255,255,0.85);
      border-radius: 20px;
      box-shadow: 0 8px 32px 0 rgba(0,150,255,0.10);
      padding: 32px 24px;
      margin-top: 32px;
      margin-bottom: 32px;
    }
    .dashboard-title {
      color: #0077c2;
      font-weight: 700;
      letter-spacing: 1px;
      margin-bottom: 30px;
      margin-top: 30px;
      text-shadow: 0 2px 8px rgba(0,150,255,0.10);
    }
    #calendar {
      background: rgba(255,255,255,0.95);
      border-radius: 16px;
      box-shadow: 0 2px 12px rgba(0,150,255,0.08);
      padding: 18px;
      margin-top: 18px;
    }
    /* Loader overlay */
    #loader-overlay {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,150,255,0.10);
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: opacity 0.4s;
      opacity: 1;
      pointer-events: all;
    }
    #loader-overlay.hide {
      opacity: 0;
      pointer-events: none;
    }
    .lds-ring {
      display: inline-block;
      position: relative;
      width: 80px;
      height: 80px;
    }
    .lds-ring div {
      box-sizing: border-box;
      display: block;
      position: absolute;
      width: 64px;
      height: 64px;
      margin: 8px;
      border: 8px solid #fff;
      border-radius: 50%;
      animation: lds-ring 1.2s cubic-bezier(0.5,0,0.5,1) infinite;
      border-color: #039be5 transparent transparent transparent;
    }
    .lds-ring div:nth-child(1) { animation-delay: -0.45s; }
    .lds-ring div:nth-child(2) { animation-delay: -0.3s; }
    .lds-ring div:nth-child(3) { animation-delay: -0.15s; }
    @keyframes lds-ring {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
</head>
<body>
<div id="loader-overlay">
  <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
</div>
<nav class="navbar navbar-expand-lg navbar-light glass-admin shadow-sm sticky-top mb-4">
  <div class="container">
    <a href="../dashboard/dashboard_admin.php" class="btn btn-glass-admin me-3">← Kembali</a>
    <a class="navbar-brand" href="#">Kalender Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link btn-glass-admin" href="../dashboard/dashboard_admin.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link btn-glass-admin" href="../admin/manajemen_pemesanan.php">Pemesanan</a></li>
        <li class="nav-item"><a class="nav-link btn-glass-admin" href="../lapangan/lapangan_admin.php">Lapangan</a></li>
        <li class="nav-item"><a class="nav-link btn-glass-admin active" href="#">Kalender</a></li>
        <li class="nav-item"><a class="nav-link btn-danger" href="../auth/logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
  <h2 class="dashboard-title">Kalender Jadwal Booking Lapangan</h2>
  <div class="glass-content-admin">
    <div id="calendar"></div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
window.addEventListener('DOMContentLoaded', function() {
  setTimeout(function() {
    document.getElementById('loader-overlay').classList.add('hide');
  }, 900);
});
const links = document.querySelectorAll('a[href]:not([target])');
links.forEach(link => {
  link.addEventListener('click', function(e) {
    const href = link.getAttribute('href');
    if (href && !href.startsWith('#') && !href.startsWith('javascript')) {
      document.getElementById('loader-overlay').classList.remove('hide');
    }
  });
});
document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,listMonth'
    },
    events: 'fetch_events.php',
    eventTimeFormat: {
      hour: '2-digit',
      minute: '2-digit',
      hour12: false
    }
  });
  calendar.render();
});
</script>
</body>
</html>
