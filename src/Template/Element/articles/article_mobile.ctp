<?php foreach($news as $item):  ?>
            <a class="uk-width-1-1 news-item-small uk-margin-large-bottom" href="<?= $this->Html->Url->build('/article/' . $item->slug); ?>">
                <div class="uk-grid info uk-grid-collapse">
                    <div class="uk-width-1-1">
                        <?= $this->Html->image('news-bg.png', ['class' => 'uk-cover-background', 'alt' => '""', 'style' => 'background-image:url(' . $item->cover_image  . ')']); ?>
                    </div>
                    <div class="uk-width-4-5 info-text uk-maring-bottom">
                        <div class="uk-margin-top small-hashtag">
                        <?php foreach($item->categories as $index => $hashtag) :?>
                                <span class="hashtag">#<?= $hashtag->name; ?></span>
                        <?php endforeach; ?>
                        </div>
                        <h1 class="title">
                        <?= getTrimmedSentences($item->title, 35); ?>
                        </h1>
                        <p class="description">
                            <?= getTrimmedSentences($item->sub_title, 80); ?>
                        </p>
                    </div>
                    <div class="uk-width-1-5 next-right uk-vertical-align">
                        <?= $this->Html->image('icons/next-blue.png', ['class' => 'uk-vertical-align-middle']);?>"
                    </div>
                </div>
            </a>
    <?php endforeach;?>