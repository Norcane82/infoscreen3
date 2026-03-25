<?php

declare(strict_types=1);

require_once __DIR__ . '/inc/bootstrap.php';

$state = read_json_file(HEALTH_FILE, [
    'last_restart' => 0,
    'restarts' => [],
    'fallback_active' => false,
    'consecutive_failures' => 0,
    'last_action' => 'none',
    'requested_view' => 'fallback',
    'reload_requested_at' => 0,
]);

$flag = __DIR__ . '/cache/fallback_active.flag';
$text = is_file($flag)
    ? trim((string)file_get_contents($flag))
    : 'Fallback aktiv';

$buildTs = (string) max(
    @filemtime(__FILE__) ?: 0,
    @filemtime(__DIR__ . '/assets/css/fallback.css') ?: 0,
    @filemtime(__DIR__ . '/assets/runtime_sync.js') ?: 0,
    time()
);

?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Infoscreen2 Fallback</title>
  <link rel="stylesheet" href="assets/css/fallback.css?v=<?= htmlspecialchars($buildTs, ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
  <div class="wrap">
    <h1>Infoscreen Sicherheitsmodus</h1>
    <p>Der Watchdog hat den Player gestoppt oder in den Fallback gesetzt.</p>
    <p>Grund: <strong><?= htmlspecialchars($text, ENT_QUOTES, 'UTF-8') ?></strong></p>
    <p>Letzte Aktion: <strong><?= htmlspecialchars((string)($state['last_action'] ?? 'none'), ENT_QUOTES, 'UTF-8') ?></strong></p>
    <p>Consecutive Failures: <strong><?= (int)($state['consecutive_failures'] ?? 0) ?></strong></p>
    <p>Neustarts in 30 Minuten: <strong><?= count((array)($state['restarts'] ?? [])) ?></strong></p>
    <small>Bitte Ursache prüfen und danach den Watchdog zurücksetzen.</small>
    <p style="margin-top:24px;"><a href="admin.php">Zur Verwaltung</a></p>
  </div>

  <script>
  window.APP_RUNTIME = {
    currentView: 'fallback',
    statusUrl: 'status.php',
    reloadRequestedAt: <?= (int)($state['reload_requested_at'] ?? 0) ?>
  };
  </script>
  <script src="assets/runtime_sync.js?v=<?= htmlspecialchars($buildTs, ENT_QUOTES, 'UTF-8') ?>"></script>
</body>
</html>
