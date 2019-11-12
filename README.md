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

$out .= '<h1>Вместе с родителями (showParent=1)</h1>';
$result = DLSiteContent::depth(0, 3, 1)->published()->orderBy('menuindex', 'asc')->get();
foreach ($result as $item) {
    $out .= $item->pagetitle . '<br>';
}
$out .= '<hr><hr>';
return $out
```
