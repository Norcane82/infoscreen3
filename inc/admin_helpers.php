<?php
declare(strict_types=1);

function admin_slide_type_label(array $item): string
{
    $type = strtolower((string)($item['type'] ?? ''));
    if ($type === 'image' && (($item['sourceType'] ?? '') === 'pdf')) {
        return 'PDF-Seite';
    }

    $map = [
        'clock' => 'Uhr',
        'image' => 'Bild',
        'video' => 'Video',
        'website' => 'Webseite',
        'pdf' => 'PDF',
    ];

    return $map[$type] ?? $type;
}

function admin_color_field(string $label, string $textName, string $pickerName, string $value): string
{
    $safeLabel = h($label);
    $safeTextName = h($textName);
    $safePickerName = h($pickerName);
    $safeValue = h($value);

    return <<<HTML
    <div>
      <label>{$safeLabel}</label>
      <div class="colorField">
        <input type="text" name="{$safeTextName}" value="{$safeValue}" data-color-text>
        <input type="color" name="{$safePickerName}" value="{$safeValue}" data-color-picker>
      </div>
    </div>
HTML;
}
