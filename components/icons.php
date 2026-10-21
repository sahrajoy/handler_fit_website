<?php
// Kleine Inline-SVG-Icons (statt externer Icon-Fonts)

function icon(string $name, int $size = 22): string
{
    $paths = [
        'instagram' => '<rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4.2"/><circle cx="17.6" cy="6.4" r="1" fill="currentColor" stroke="none"/>',
        'mail' => '<rect x="2.5" y="4.5" width="19" height="15" rx="2"/><path d="m3 6 9 7 9-7"/>',
        'calendar' => '<rect x="3" y="4.5" width="18" height="16.5" rx="2"/><path d="M3 9.5h18M8 2.5v4M16 2.5v4"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'pin' => '<path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/>',
        'users' => '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 20c.5-3.5 3.2-5.5 6.5-5.5s6 2 6.5 5.5"/><path d="M16 4.8a3.5 3.5 0 0 1 0 6.4M18 14.8c2 .6 3.2 2.4 3.5 5.2"/>',
        'check' => '<path d="m4.5 12.5 5 5 10-11"/>',
        'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
    ];
    return '<svg class="icon" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
        . $paths[$name] . '</svg>';
}

// Icons aus assets/images (Noun Project). Der Urheberhinweis im Bild wird per CSS abgeschnitten,
// die Namensnennung steht stattdessen im Impressum.
function noun_icon(string $loc, string $file, string $alt = ''): string
{
    return '<span class="noun-icon"><img src="' . $loc . 'assets/images/' . $file . '" alt="' . e($alt) . '" loading="lazy"></span>';
}
