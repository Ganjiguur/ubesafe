<?php 
require_once(ROOT . DS . 'plugins' . DS . "tools.php");
$newsColors = ['purple', 'orange', 'pink'];
?>

<div class="blue-background">
    <div class="uk-container uk-container-center">
        <div class="uk-grid margin-top-100 uk-grid-large" data-uk-grid-margin="">
            <div class="uk-width-1-1 uk-width-medium-1-2">
                <img src="img/logos.png">
            </div>
            <div class="about uk-width-1-1 uk-width-medium-1-2 uk-vertical-align">
                <div class="uk-vertical-align-middle uk-margin-bottom">
                    <h1 class="about-title margin-bottom-20">Төслийн тухай</h1>
                    <p class="about-text">Дэлхийн Эдийн Засгийн Форумын дэргэдэх "Глобал Шэйперс Улаанбаатар" нийгэмлэг,
                        EASST олон улсын байгууллагатай хамтран автомашины хүүхдийн суудлын хэрэглээг нэмэгдүүлэх "Амин
                        хариуцлага" төслийг 2019 онд хэрэгжүүлж байна.</p>
                </div>
            </div>
        </div>
        <div class="margin-top-100">
            <div class="uk-text-center about-dark-blue-title uk-margin-large-bottom">
                Tөслийн зорилго
            </div>
            <div class="uk-grid uk-grid-medium" data-uk-grid-match="{target:'div'}" data-uk-grid-margin>
                <div class="uk-width-1-1 uk-width-small-1-2 uk-width-medium-1-4">
                    <div class="about-card">
                        <img src="img/icons/about-icon-1.png" class="about-icon">
                        <div class="card-title">Мэдлэг <br>мэдээлэл түгээх
                        </div>
                        <div class="card-text">
                            Залуу эцэг эхчүүдэд автомашины хүүхдийн суудал зөв хэрэглэх тухай мэдлэг, мэдээлэл түгээнэ.
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-1 uk-width-small-1-2 uk-width-medium-1-4">
                    <div class="about-card">
                        <img src="img/icons/about-icon-2.png" class="about-icon">
                        <div class="card-title">Зориулалтын суудалд тээвэрлэхийг уриалах
                        </div>
                        <div class="card-text">
                            Автомашины ослоос үүдэн гарч буй хүүхдийн гэмтэл болон эндэгдлийн тоог бууруулна.
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-1 uk-width-small-1-2 uk-width-medium-1-4">
                    <div class="about-card">
                        <img src="img/icons/about-icon-3.png" class="about-icon">
                        <div class="card-title">Хамгааалалтын суудал хандивлах
                        </div>
                        <div class="card-text">
                            Хэрэглэхгүй байгаа хамгаалалтын суудлыг хэрэгцээтэй гэр бүлд хандивлах зорилгоор цуглуулна.
                        </div>
                    </div>
                </div>
              <div class="uk-width-1-1 uk-width-small-1-2 uk-width-medium-1-4">
                    <div class="about-card">
                        <img src="img/icons/about-icon-4.png" class="about-icon">
                        <div class="card-title">+300 автомашины хүүхдийн суудал
                        </div>
                        <div class="card-text">
                            Төслийн үр дүнд 400 хүүхэд, гэр бүл автомашины хүүхдийн суудалтай болох юм.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="margin-top-100">
            <iframe width="100%" class="youtube-video" src="<?= $this->AppView->getSiteMeta('youtube-video-link', false)?>">
            </iframe>
        </div>
        <div class="uk-grid margin-top-100" data-uk-slider="{infinite: false}">
            <h1 class="about-title uk-width-1-1 margin-bottom-20">Хүүхдийн суудал <br>ашиглах заавар</h1>
            <p class="uk-width-1-1 uk-width-medium-1-2 about-text margin-bottom-40">Автомашины хамгаалалтын суудал нь хүүхдийн нас, жингээс
                хамааран янз бүр байдаг учир та зориулалтын суудал худалдан авахын өмнө хүүхдийнхээ жинг эхлээд үзээрэй.
            </p>
            <div class="uk-width-1-1 uk-width-medium-1-2 slider-arrow">
                <a href="#" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous prev" data-uk-slider-item="previous"
                    draggable="false"></a>
                <a href="#" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next next" data-uk-slider-item="next"
                    draggable="false"></a>
            </div>
            <div class="uk-width-1-1 margin-bottom-100">
                <div class="uk-slidenav-position">
                    <div class="uk-slider-container">
                        <ul class="uk-slider uk-grid uk-grid-width-1-1 uk-grid-width-medium-1-4" style="margin-top: 60px">
                            <li data-slider-slide="1" class="slider-item">
                            <div class="image-bg">
                                <?= $this->Html->image('chair-1.png', ["class" => 'slider-image']) ?>  
                            </div>
                            <div class="slider-info">
                                <div class="age-outer-ring uk-text-center">
                                    <div class="age uk-vertical-align">
                                    <span class="uk-vertical-center uk-text-center">0</span>
                                    </div>
                                </div>  
                                <p class="age-limit"><?= $this->Html->image('icons/correct.png', ['style' => 'margin-right:10px;']); ?>0-9 сар</p>
                                <p class="weight-limit"><?= $this->Html->image('icons/correct.png', ['style' => 'margin-right:10px;']); ?>Нярайгаас 10 кг хүртэл</p>
                                <p class="description">Хараахан суух болоогүй байгаа бяцхан зорчигчид зориулагдсан “0” зэрэглэлийн суудал нь нялх хүүхдийн тэрэгний дээд ...</p>
                            </div>
                            </li>
                            <li data-slider-slide="2" class="slider-item">
                            <div class="image-bg">
                                <?= $this->Html->image('chair-2.png', ["class" => 'slider-image']) ?>  
                            </div>
                            <div class="slider-info">
                                <div class="age-outer-ring uk-text-center">
                                    <div class="age uk-vertical-align">
                                    <span class="uk-vertical-center uk-text-center">0+</span>
                                    </div>
                                </div>  
                                <p class="age-limit"><?= $this->Html->image('icons/correct.png', ['style' => 'margin-right:10px;']); ?>Нярайгаас 4 нас хүртэл</p>
                                <p class="weight-limit"><?= $this->Html->image('icons/correct.png', ['style' => 'margin-right:10px;']); ?>Нярайгаас 13 кг хүртэл</p>
                                <p class="description">Энэ суудлыг автомашинд аюулгүйн суудал гэдэг утгаар нь хэрэглээд зогсохгүй мөн аялалд явахдаа ... </p>
                            </div>
                            </li>
                            <li data-slider-slide="3" class="slider-item">
                            <div class="image-bg">
                                <?= $this->Html->image('chair-3.png', ["class" => 'slider-image']) ?>  
                            </div>
                            <div class="slider-info">
                                <div class="age-outer-ring uk-text-center">
                                    <div class="age uk-vertical-align">
                                    <span class="uk-vertical-center uk-text-center">1</span>
                                    </div>
                                </div>  
                                <p class="age-limit"><?= $this->Html->image('icons/correct.png', ['style' => 'margin-right:10px;']); ?>9 сар - 4 нас хүртэл</p>
                                <p class="weight-limit"><?= $this->Html->image('icons/correct.png', ['style' => 'margin-right:10px;']); ?>9-18 кг</p>
                                <p class="description">Ихэвчлэн зорчих хөдөлгөөний дагуу арын болон урд талын суудалд байрлуулах бөгөөд түшлэг нь хэд хэдэн байрлалын ...</p>
                            </div>
                            </li>
                            <li data-slider-slide="4" class="slider-item">
                            <div class="image-bg">
                                <?= $this->Html->image('chair-4.png', ["class" => 'slider-image']) ?>  
                            </div>
                            <div class="slider-info">
                                <div class="age-outer-ring uk-text-center">
                                    <div class="age uk-vertical-align">
                                    <span class="uk-vertical-center uk-text-center">2</span>
                                    </div>
                                </div>  
                                <p class="age-limit"><?= $this->Html->image('icons/correct.png', ['style' => 'margin-right:10px;']); ?>6 нас хүртэл</p>
                                <p class="weight-limit"><?= $this->Html->image('icons/correct.png', ['style' => 'margin-right:10px;']); ?>15-25 кг</p>
                                <p class="description">Хүүхдийг 3-4 нас хүртэл нь хүүхдийн хамгаалалтын суудлын бүсээр, харин үүнээс дээш насны хүүхдийг маш...</p>
                            </div>
                            </li>
                            <li data-slider-slide="5" class="slider-item">
                                <div class="image-bg">
                                    <?= $this->Html->image('chair-4.png', ["class" => 'slider-image']) ?>  
                                </div>
                                <div class="slider-info">
                                    <div class="age-outer-ring uk-text-center">
                                        <div class="age uk-vertical-align">
                                        <span class="uk-vertical-center uk-text-center">3</span>
                                        </div>
                                    </div>  
                                    <p class="age-limit"><?= $this->Html->image('icons/correct.png', ['style' => 'margin-right:10px;']); ?>12 нас хүртэл</p>
                                    <p class="weight-limit"><?= $this->Html->image('icons/correct.png', ['style' => 'margin-right:10px;']); ?>22-36 кг</p>
                                    <p class="description">Хүүхдийг 3-4 нас хүртэл нь хүүхдийн хамгаалалтын суудлын бүсээр, харин үүнээс дээш насны хүүхдийг маш...</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="uk-container uk-container-center margin-bottom-100">
  <h3 class="uk-text-center news-title margin-top-100">Мэдээ мэдээлэл</h3>
    <div class="uk-grid news uk-hidden-small">
        <?php foreach($news as $parentIndex => $item):  ?>
            <?php if($parentIndex % 2 == 0) : ?>
                <a class="uk-width-1-1 news-item uk-clearfix" href="<?= $this->Html->Url->build('/article/' . $item->slug); ?>">
                    <div class="uk-float-left">
                        <?= $this->Html->image('news-bg.png', ['class' => 'uk-cover-background', 'alt' => '""', 'style' => 'background-image:url(' . $item->cover_image  . ')']); ?>
                    </div>
                    <div class="uk-grid uk-float-right info right">
                        <div class="uk-width-4-5 info-text">
                            <div class="<?= $newsColors[$parentIndex];?> uk-margin-top">
                                <?php foreach($item->categories as $index => $hashtag) :?>
                                    <?php if($index < 3): ?>
                                        <span class="hashtag <?php echo ($parentIndex == 0) ? 'purple' : 'pink'; ?> ">#<?= $hashtag->name; ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <h1 class="title">
                            <?= getTrimmedSentences($item->title, 62);  ?>
                            </h1>
                            <p class="description">
                            <?= getTrimmedSentences($item->sub_title, 185);  ?>
                            </p>
                        </div>
                        <div class="uk-width-1-5">
                            <div class="uk-grid uk-grid-collapse" style="height:100%">
                                <div class="uk-width-1-5"></div>
                                <div class="next-right <?php echo ($parentIndex == 0) ? 'first' : 'third'; ?> uk-vertical-align uk-width-4-5">
                                <?= $this->Html->image('icons/next-' . $newsColors[$parentIndex] . '.png', ['class' => 'uk-vertical-align-middle news-icon']);?>"
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php  else : ?>
            <a class="uk-width-1-1 news-item uk-clearfix" href="<?= $this->Html->Url->build('/article/' . $item->slug); ?>">
                <div class="uk-float-right">
                    <?= $this->Html->image('news-bg.png', ['class' => 'uk-cover-background', 'alt' => '""', 'style' => 'background-image:url(' . $item->cover_image  . ')']); ?>
                </div>
                <div class="uk-grid uk-float-left info left">
                    <div class="uk-width-1-5" style="padding-left: 0px!important; padding-right: 35px!important;">
                        <div class="uk-grid uk-grid-collapse" style="height:100%">
                            <div class="next-left second uk-vertical-align uk-width-4-5">
                                <?= $this->Html->image('icons/prev-' . $newsColors[$parentIndex] . '.png', ['class' => 'uk-vertical-align-middle news-icon']);?>"
                            </div>
                            <div class="uk-width-1-5"></div>
                        </div>
                    </div>
                    <div class="uk-width-4-5 info-text"> 
                        <div class=" <?= $newsColors[$parentIndex];?> uk-margin-top">
                            <?php foreach($item->categories as $index => $hashtag) :?>
                                <?php if($index < 3): ?>
                                    <span class="hashtag orange">#<?= $hashtag->name; ?></span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <h1 class="title">
                        <?= getTrimmedSentences($item->title, 62);  ?>
                        </h1>
                        <p class="description">
                        <?= getTrimmedSentences($item->sub_title, 185);  ?>
                        </p>
                    </div>
                </div>
            </a>
            <?php  endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="uk-grid news-small uk-visible-small">
        <?php foreach($news as $parentIndex => $item):  ?>
            <?php if($parentIndex % 2 == 0) : ?>
                <a class="uk-width-1-1 news-item-small uk-margin-large-bottom" href="<?= $this->Html->Url->build('/article/' . $item->slug); ?>">
                    <div class="uk-grid info uk-grid-collapse">
                        <div class="uk-width-1-1">
                            <?= $this->Html->image('news-bg.png', ['class' => 'uk-cover-background', 'alt' => '""', 'style' => 'background-image:url(' . $item->cover_image  . ')']); ?>
                        </div>
                        <div class="uk-width-4-5 info-text uk-maring-bottom">
                            <div class="uk-margin-top small-hashtag">
                            <?php foreach($item->categories as $index => $hashtag) :?>
                                <?php if($index < 2): ?>
                                    <span class="hashtag <?php echo ($parentIndex == 0) ? 'purple' : 'orange'; ?> ">#<?= $hashtag->name; ?></span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            </div>
                            <h1 class="title">
                            <?= getTrimmedSentences($item->title, 35); ?>
                            </h1>
                            <p class="description">
                                <?= getTrimmedSentences($item->sub_title, 80); ?>
                            </p>
                        </div>
                        <div class="uk-width-1-5 next-right <?php echo ($parentIndex == 0) ? 'first' : 'third'; ?> uk-vertical-align">
                            <?= $this->Html->image('icons/next-' . $newsColors[$parentIndex] . '.png', ['class' => 'uk-vertical-align-middle news-icon']);?>"
                        </div>
                    </div>
                </a>
            <?php else : ?>
                <a class="uk-width-1-1 news-item-small uk-margin-large-bottom" href="<?= $this->Html->Url->build('/article/' . $item->slug); ?>">
                    <div class="uk-grid info uk-grid-collapse">
                        <div class="uk-width-1-1">
                            <?= $this->Html->image('news-bg.png', ['class' => 'uk-cover-background', 'alt' => '""', 'style' => 'background-image:url(' . $item->cover_image  . ')']); ?>
                        </div>
                        <div class="uk-width-1-5 next-left second uk-vertical-align">
                            <?= $this->Html->image('icons/prev-' . $newsColors[$parentIndex] . '.png', ['class' => 'uk-vertical-align-middle news-icon']);?>"
                        </div>
                        <div class="uk-width-4-5 info-text uk-maring-bottom">
                            <div class="uk-margin-top small-hashtag">
                            <?php foreach($item->categories as $index => $hashtag) :?>
                                <?php if($index < 2): ?>
                                    <span class="hashtag orange">#<?= $hashtag->name; ?></span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            </div>
                            <h1 class="title">
                            <?= getTrimmedSentences($item->title, 35); ?>
                            </h1>
                            <p class="description">
                                <?= getTrimmedSentences($item->sub_title, 80); ?>
                            </p>
                        </div>
                    </div>
                </a>
            <?php endif; ?>
        <?php endforeach;?>
    </div>
</div>