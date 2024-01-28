<?php
    use Cake\Utility\Inflector;
    
    $data = $formdata->data;
    
    $pos = isset($formdata->source) ? mb_strpos($formdata->source, ' - ') : false;
    $sourceLink = '#';
    $sourceName = ' - ';
    if($pos !== false) {
      $sourceLink = mb_substr($formdata->source, 0, $pos);
      $sourceName = mb_substr($formdata->source, $pos + 3);
    }
?>

<?php echo "Хүсэлтийн мэдээлэл" ?>

<?= "Формын нэр:" ?> <?= $form->name ?>
<?= "Хүсэлт ирсэн хуудас:" ?> <?= $sourceName . ' (' . $sourceLink . ')' ?>

<?= $form->title ?>
<?= $form->description ?>


<?php foreach ($form->fields as $field): ?>

<?php        
if($field['type'] == "seperator") {
    echo "" . $field['label'] . "";
    
} else {
    echo "" . $field['label'] . "";

    $name = Inflector::slug(mb_strtolower(h($field['label'])), '_');
    if (empty($data[$name])) {
            continue;
        }
    switch ($field['type']) {
        case "string":
        case "text":
        case "number":
        case "email":
        case "date":
        case "register":
        case "option":
            echo $data[$name];
            break;
        case "multiple":   
            $data[$name] = (is_array($data[$name]) ? $data[$name] : explode(',', $data[$name]));
            foreach ($data[$name] as $val) {
                echo $val . "";
            }
            break;
        case "boolean":
            echo $data[$name] == 'no' ? "Үгүй" : "Тийм";
            break;
        case "file":
            if ($field['option'] == 'image') {
                echo $data[$name];
            } else {
                echo $data[$name]; 
            }
            echo empty($data[$name]) ? "Үгүй" : "Тийм";
            break;
    default:break;
    } 
}

?>

<?php endforeach; ?>


=====================================
<?= "Чингис Хаан Банк" ?>