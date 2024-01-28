<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
?>

<div class="uk-alert uk-margin-top <?= h($class) ?>" data-uk-alert>
    <a href="" class="uk-alert-close uk-close"></a>
    <p><?= h($message) ?></p>
</div>