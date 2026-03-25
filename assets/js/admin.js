function toggleEdit(id) {
  const row = document.getElementById(id);
  if (!row) {
    return;
  }
  row.style.display = row.style.display === 'table-row' ? 'none' : 'table-row';
}

async function loadLiveStatus() {
  const target = document.getElementById('liveStatus');
  if (!target) {
    return;
  }

  try {
    const response = await fetch('status.php', { cache: 'no-store' });
    const data = await response.json();

    if (!data || data.ok !== true) {
      target.textContent = 'Live-Status konnte nicht geladen werden.';
      return;
    }

    target.textContent =
      'Player: ' + (data.player_running ? 'läuft' : 'steht') +
      ' | Apache: ' + (data.apache_running ? 'läuft' : 'steht') +
      ' | Aktivierte Folien: ' + data.enabled_slides +
      ' | Letzte Watchdog-Zeile: ' + (data.last_log_line || 'keine');
  } catch (error) {
    target.textContent = 'Live-Status konnte nicht geladen werden.';
  }
}

function bindColorFields() {
  document.querySelectorAll('.colorField').forEach((field) => {
    const text = field.querySelector('[data-color-text]');
    const picker = field.querySelector('[data-color-picker]');

    if (!text || !picker) {
      return;
    }

    const normalize = (value) => {
      const trimmed = String(value || '').trim();
      return /^#[0-9a-fA-F]{6}$/.test(trimmed) ? trimmed : null;
    };

    const syncFromText = () => {
      const normalized = normalize(text.value);
      if (normalized) {
        picker.value = normalized;
      }
    };

    const syncFromPicker = () => {
      text.value = picker.value;
    };

    text.addEventListener('input', syncFromText);
    picker.addEventListener('input', syncFromPicker);

    syncFromText();
  });
}

loadLiveStatus();
bindColorFields();
setInterval(loadLiveStatus, 15000);
