experimental model
```php
<?php
use EvolutionCMS\Custom\DLSiteContent;

$out = '';

$out .= '<h1>Только конечные ресурсы</h1>';
$result = DLSiteContent::depth(0, 3)->published()->orderBy('menuindex', 'asc')->get();
foreach ($result as $item) {
    $out .= $item->pagetitle . '<br>';
}
$out .= '<hr><hr>';

$out .= '<h1>Вместе с родителям &parents=0&depth=3&showParent=1 (начиная с корня сайта)</h1>';
$result = DLSiteContent::depth(0, 3, 1)->published()->orderBy('menuindex', 'asc')->get();
foreach ($result as $item) {
    $out .= $item->pagetitle . '<br>';
    //$out .= print_r($item->myprop, true);
}
$out .= '<hr><hr>';

$out .= '<h1>Только конечные ресурсы (для родителей с id 2, 4 - &parents=2,4&depth=3)</h1>';
$result = DLSiteContent::depth('2,4', 3)->published()->orderBy('menuindex', 'asc')->get();
foreach ($result as $item) {
    $out .= $item->pagetitle . '<br>';
    //$out .= print_r($item->myprop, true);
}
$out .= '<hr>';

$out .= '<h1>Сортировка и фильтр по ТВ</h1>';
$result = DLSiteContent::withTVs(['price', 'title'])->published()->where('parent', 0)->where('tv_price.value', '>', '150')->orderBy('tv_price.value', 'desc')->orderBy('pagetitle', 'asc')->get();
foreach ($result as $item) {
    $out .= $item->pagetitle . '<br>';
    //$out .= print_r($item, true);
}
$out .= '<hr>';

return $out;

```
