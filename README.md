experimental model
```php
<?php
use EvolutionCMS\Custom\DLSiteContent;

$out = '';

$out .= '<h1>Вместе с родителям &parents=0&depth=3&showParent=1 (начиная с корня сайта)</h1>';
$result = DLSiteContent::depth(0, 3, 1)->published()->orderBy('menuindex', 'asc')->get();
foreach ($result as $item) {
    $out .= $item->pagetitle . '<br>';
}
$out .= '<hr><hr>';


$out .= '<h1>Только конечные ресурсы (для родителей с id 2, 4 - &parents=2,4&depth=3)</h1>';
$result = DLSiteContent::depth('2,4', 3)->published()->orderBy('menuindex', 'asc')->get();
foreach ($result as $item) {
    $out .= $item->pagetitle . '<br>';
}
$out .= '<hr>';


$out .= '<h1>Сортировка и фильтр по ТВ</h1>';
$result = DLSiteContent::withTVs(['price', 'titl'])->published()->where('parent', 0)->where('tv_price.value', '>', '150')->orderBy('tv_price.value', 'desc')->orderBy('pagetitle', 'asc')->get();
foreach ($result as $item) {
    $out .= $item->pagetitle . '<br>';
}
$out .= '<hr>';


/** можно но сложно, лучше использовать следующий метод
$prefix = $modx->getDatabase()->getConfig('prefix');
$out .= '<h1>Сортировка и фильтрация по ТВ с приведением типа (пока так, нужен $modx, префикс ну и что-то еще..)</h1>';
$result = DLSiteContent::withTVs(['price'])->published()->where('parent', 0)->whereRaw("CAST(" . $prefix . "tv_price.value AS DECIMAL(10,2)) > 150")->orderByRaw("CAST(" . $prefix . "tv_price.value AS DECIMAL(10,2)) ASC")->orderBy('pagetitle', 'desc')->get();
foreach ($result as $item) {
    $out .= $item->pagetitle . '<br>';
}
$out .= '<hr>';
**/


$out .= '<h1>Сортировка и фильтрация по ТВ с приведением типа (и без него)</h1>';
$result = DLSiteContent::withTVs(['price', 'brand'])->published()->where('parent', 0)->tvFilter("tv:price:>:150:UNSIGNED;tv:price:<:600:UNSIGNED;tv:brand:in:а,б,в;tv:brand:!null;")->tvOrderBy("price asc UNSIGNED, brand asc")->orderBy('pagetitle', 'asc')->get();
foreach ($result as $item) {
    $out .= $item->pagetitle . '<br>';
}
$out .= '<hr>';


$out .= '<h1>Фильтрация и сортировка по ТВ с приведением типа (и без него) с учетом значений по-умолчанию</h1>';
$result = DLSiteContent::withTVs(['price:d', 'brand'])->published()->where('parent', 0)->tvFilter("tvd:price:>:150:UNSIGNED;tv:brand:in:а,б,в")->tvOrderBy("price:d asc UNSIGNED, brand asc")->orderBy('pagetitle', 'asc')->get();
foreach ($result as $item) {
    $out .= $item->id . ' - ' . $item->pagetitle . '<br>';
}
$out .= '<hr>';


$out .= '<h1>Работаем с getTvList (TV передаются в отдельном массиве)</h1>';
$result = DLSiteContent::where('parent', 0)->published()->get();
$tvs = DLSiteContent::getTvList($result, ['price', 'brand']);
foreach ($result as $item) {
    $out .= $item->id . ' - ' . $item->pagetitle . '<br>';
    if (!empty($tvs[$item->id])) {
        foreach ($tvs[$item->id] as $name => $value) {
            $out .= $name . ': ' . $value . '<br>';
        }
    }
    $out .= '<hr>';
}


$out .= '<h1>Работаем с tvList внутри массива (рекомендуемый метод)</h1>';
$result = DLSiteContent::where('parent', 0)->published()->get();
$result = DLSiteContent::tvList($result, 'price,brand');
foreach ($result as $item) {
    $out .= $item['id'] . ' - ' . $item['pagetitle'] . '<br>';
    if (!empty($item['tvs'])) {
        foreach ($item['tvs'] as $name => $value) {
            $out .= $name . ': ' . $value . '<br>';
        }
}
$out .= '<hr>';
}

return $out;

```
