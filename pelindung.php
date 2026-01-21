<?php
/**
 * WordPress Root Protection Script
 * Jalankan via cron
 * Pastikan file ini berada di ROOT WordPress
 */

$root = __DIR__;

/* FILE CORE YANG DIIZINKAN */
$allowedFiles = [
    '.htaccess',
    'index.php',
    'wp-activate.php',
    'wp-blog-header.php',
    'wp-comments-post.php',
    'wp-config.php',
    'wp-config-sample.php',
    'wp-cron.php',
    'wp-links-opml.php',
    'wp-load.php',
    'wp-login.php',
    'wp-mail.php',
    'wp-settings.php',
    'wp-signup.php',
    'wp-trackback.php',
    'xmlrpc.php',
    'pelindung.php',
    'google76491ea2c634a8c7.html',
    'ads.txt',
    'dul.php'
];

/* FOLDER CORE YANG DIIZINKAN */
$allowedDirs = [
    'wp-admin',
    'wp-content',
    'wp-includes',
    '.well-known'
];

/* FUNGSI HAPUS FOLDER REKURSIF */
function deleteDir($dir) {
    if (!is_dir($dir)) return;
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            deleteDir($path);
        } else {
            @unlink($path);
        }
    }
    @rmdir($dir);
}

/* SCAN ROOT */
$items = scandir($root);

foreach ($items as $item) {
    if ($item === '.' || $item === '..') continue;

    $path = $root . '/' . $item;

    /* FILE */
    if (is_file($path)) {
        if (!in_array($item, $allowedFiles)) {
            @unlink($path);
        }
    }

    /* DIRECTORY */
    if (is_dir($path)) {
        if (!in_array($item, $allowedDirs)) {
            deleteDir($path);
        }
    }
}



/* ==========================
   MODE BROWSER (HTML)
========================== */
if (php_sapi_name() !== 'cli') {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>WordPress Protection</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-white">

  <div class="min-h-screen flex items-center justify-center">
    <div class="text-center space-y-6">

      <!-- ICON LOCK -->
      <div class="flex justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-emerald-400"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.5 10.5V7.5a4.5 4.5 0 10-9 0v3M6 10.5h12
                   a1.5 1.5 0 011.5 1.5v7.5
                   A1.5 1.5 0 0118 21H6
                   a1.5 1.5 0 01-1.5-1.5V12
                   A1.5 1.5 0 016 10.5z"/>
        </svg>
      </div>

      <!-- TITLE -->
      <h1 class="text-3xl md:text-4xl font-bold tracking-widest text-emerald-400">
        AUTO REMOVE
      </h1>

      <!-- SUBTITLE -->
      <p class="text-lg md:text-xl uppercase tracking-[0.25em] text-slate-300">
        Non WordPress Core
      </p>

      <!-- INFO -->
      <p class="text-sm text-slate-500 max-w-md mx-auto">
        This script is executed via <span class="text-slate-300">CRON / CLI</span>.
        Browser access is <span class="text-red-400">read-only</span>.
      </p>

      <!-- FOOTER -->
      <div class="text-xs text-slate-600 pt-6">
        <?= htmlspecialchars(basename(__FILE__)) ?> • Protected Environment
      </div>

    </div>
  </div>

</body>
</html>
<?php
exit;
}
