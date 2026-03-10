<?php
function renderTab($menus)
{
    $html = '';
    foreach ($menus as $menu) {
        $active = $menu['active'] ? 'active' : '';
        $html .= '<li class="' . $active . '"><a href="' . $menu['url'] . '">' . $menu['nama'] . '</a></li>';
    }
    return $html;
}

function getActiveTab($menus)
{
    foreach ($menus as $menu) {
        if ($menu['active']) {
            return $menu['nama'];
        }
    }

    return 'Tab tidak ditemukan';
}

function renderContent($menus, $context)
{
    foreach ($menus as $menu) {
        if ($menu['active']) {
            return $context->render($menu['view']);
        }
    }

    return 'Tab tidak ditemukan';
}

return [
    [
        'tab' => 1,
        'nama' => 'Rekap Monitoring Rotary Sengon',
        'active' => $tab === 1,
        'url' => '?tab=1',
        'view' => 'rotary'
    ],[
        'tab' => 2,
        'nama' => 'Rekap Monitoring Sengon',
        'active' => $tab === 2,
        'url' => '?tab=2',
        'view' => 'io'
    ]
];