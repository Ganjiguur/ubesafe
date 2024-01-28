<section class="footer blue-background">
    <div class="uk-container uk-container-center margin-bottom-100">
    <div class="uk-grid footer-request-grid data-uk-grid-match">
        <div class="uk-width-1-1 uk-width-medium-1-2 uk-text-center">
            <h1 class="about-dark-blue-title uk-text-center margin-bottom-40"><?= __('Хүүхдийн суудал <br> хандивлах'); ?></h1>
            <p class="about-text uk-text-center" style="padding: 0px 50px 0px 50px;"><?= __('Өмнө нь хэргэлсэн, одоо хэрэглэдэггүй автомашины хүүхдийн суудлыг хэрэгтэй хүмүүст хандивлах аянд таныг урьж байна.'); ?>
            </p>
            <a target="_blank" target="_blank" href="#send-donate" data-uk-modal class="uk-button uk-button-large donate-btn uk-text-center">
                <?= $this->Html->image("/img/icons/donate-btn.png", ['class' => 'btn-request-logo']) ?><?= __('Хандивлах')?>
            </a>
        </div>
        <div class="uk-visible-small margin-bottom-100" class=" uk-width-1-1">
        </div>
        <div class="uk-width-1-1 uk-width-medium-1-2 uk-text-center">
            <h1 class="about-dark-blue-title uk-text-center margin-bottom-40"><?= __('Хамгаалалтын <br> суудал авах'); ?></h1>
            <p class="about-text uk-text-center" style="padding: 0px 50px 0px 50px;">
            <?= __('Залуу эцэг эхчүүд гэр бүлүүд хүүхдээ аюулгүй тээвэрлэхэд туслах хамгаалалтын суудал авах хүсэлтээ гаргана уу.') ?></p>
            <a target="_blank" href="#send-request" data-uk-modal class="uk-button uk-button-large request-btn uk-text-center">
                <?= $this->Html->image("/img/icons/request-btn.png", ['class' => 'btn-request-logo']) ?><?= __('Хүсэлт илгээх')?>
            </a>
        </div>
    </div>
    </div>
    <div class="curve-back uk-cover-background" style="background-image: url('<?= $this->Url->image('/img/footer-bg.png')?>')">
			<div class="uk-container uk-container-center uk-text-center">
                <?= $this->Html->image("/img/footer-logo.png", ['class' => 'footer-logo']) ?>
                <?= $this->Html->image("/img/easst.png", ['class' => 'footer-logo-easst']) ?>
                <?= $this->Html->image("/img/police.png", ['class' => 'footer-logo-police']) ?>
               <div class="uk-text-center">
					<div class="footer-menu">
						<a class="" href="<?= $this->Url->build('/home')?>"><?= __("Нүүр")?></a>
						<a class="" href="<?= $this->Url->build('/about')?>"><?= __("Төслийн тухай")?></a>
						<a class="uk-hidden-small" href="" ><?= __("Ашиглах заавар")?></a>
						<a class="" href="<?= $this->Url->build('/news'); ?>"><?= __("Мэдээ")?></a>
					</div>
				</div>
				<div class="footer-social uk-text-center">
					<a target="_blank" href="<?= $this->AppView->getSiteMeta('facebook', false); ?>"><?= $this->Html->image("/img/icons/facebook.png" , ['class' => 'social-icon']) ?></a>
					<a target="_blank" href="<?= $this->AppView->getSiteMeta('twitter', false); ?>"><?= $this->Html->image("/img/icons/twitter.png" , ['class' => 'social-icon']) ?></a>
					<a target="_blank" href=" <?=$this->AppView->getSiteMeta('youtube', false); ?>"><?= $this->Html->image("/img/icons/youtube.png" , ['class' => 'social-icon']) ?></a>
				</div>
				<div class="uk-text-center copyright">
					<?= __("© Амин хариуцлага 2019. Бүх эрх хуулиар хамгаалагдсан."); ?>
				</div>
			</div>
    </div>
</section>