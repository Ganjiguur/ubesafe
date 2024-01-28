<?php
    use Cake\Utility\Inflector;
    $data = $formRows;
?>

<h2>
    <strong><?= $title ?></strong>
</h2>
<br>

<table border="1" style="width:100%">
<?php if($type == 'request') :?>
    <tr>
        <th style="width:50%;text-align:left"><?= __('Таны хүүхдийн нас:')?></th>
        <th style="width:50%;text-align:left"><?= $data['age'] ?></th>
    </tr>
    <tr>
        <th style="width:50%;text-align:left"><?= __('Таны хүүхдийн жин:')?></th>
        <th style="width:50%;text-align:left"><?= $data['weight'] ?></th>
    </tr>
    <tr>
        <th style="width:50%;text-align:left"><?= __('Таны хүүхдийн хүйс')?></th>
        <th style="width:50%;text-align:left"><?= $data['gender'] ?></th>
    </tr>
    <tr>
        <th style="width:50%;text-align:left"><?= __('Таны машины загвар, модел')?></th>
        <th style="width:50%;text-align:left"><?= $data['car-model'] ?></th>
    </tr>
    <tr>
        <th style="width:50%;text-align:left"><?= __('Таны овог нэр')?></th>
        <th style="width:50%;text-align:left"><?= $data['fullname'] ?></th>
    </tr>
    <tr>
        <th style="width:50%;text-align:left"><?= __('Утасны дугаар')?></th>
        <th style="width:50%;text-align:left"><?= $data['phone'] ?></th>
    </tr>
    <tr>
        <th style="width:50%;text-align:left"><?= __('Гэрийн хаяг')?></th>
        <th style="width:50%;text-align:left"><?= $data['address'] ?></th>
    </tr>
    <tr>
        <th style="width:50%;text-align:left"><?= __('Танай өрхийн сарын орлого хэд вэ?')?></th>
        <th style="width:50%;text-align:left"><?= $data['salary'] ?></th>
    </tr>
    <tr>
        <th style="width:50%;text-align:left"><?= __('Та хэдэн хүүхэдтэй вэ?')?></th>
        <th style="width:50%;text-align:left"><?= $data['child-number'] ?></th>
    </tr>
    <tr>
        <th style="width:50%;text-align:left"><?= __('Хамгаалах суудал хэрэглэхгүй байсан шалтгаан юу вэ?')?></th>
        <th style="width:50%;text-align:left"><?= $data['reason'] ?></th>
    </tr>
    <tr>
        <th style="width:50%;text-align:left"><?= __('Гарын үсэг')?></th>
        <th style="width:50%;text-align:left"><?= $data['signature'] ?></th>
    </tr>
<?php else :?>
    <tr>
        <th style="width:50%;text-align:left"><?= __('Таны нэр')?></th>
        <th style="width:50%;text-align:left"><?= $data['fullname'] ?></th>
    </tr>
    <tr>
        <th style="width:50%;text-align:left"><?= __('Таны утасны дугаар')?></th>
        <th style="width:50%;text-align:left"><?= $data['phone'] ?></th>
    </tr>
    <tr>
        <th style="width:50%;text-align:left"><?= __('Хамгаалах суудлыг нийт хэдэн жил ашигласан бэ?')?></th>
        <th style="width:50%;text-align:left"><?= $data['used-year'] ?></th>
    </tr>
    <tr>
        <th style="width:50%;text-align:left"><?= __('ТХамгаалах суудал нь ослын үеэр ашиглагдаж байсан уу?')?></th>
        <th style="width:50%;text-align:left"><?= $data['is-used'] ?></th>
    </tr>
    <tr>
        <th style="width:50%;text-align:left"><?= __('Хандивлах суудлын төрөл')?></th>
        <th style="width:50%;text-align:left"><?= $data['type'] ?></th>
    </tr>
    <tr>
        <th style="width:50%;text-align:left"><?= __('Суудлыг очиж авах хаяг')?></th>
        <th style="width:50%;text-align:left"><?= $data['address'] ?></th>
    </tr>
<?php endif;?>
</table>