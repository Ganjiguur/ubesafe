<?php
require_once(ROOT . DS . 'plugins' . DS . "tools.php");

echo $this->Html->script('/cms/js/xepOnline.jqPlugin', ['block' => true]);
?>

<style>
  .fr-view .uk-table th {
    padding: 5px;
    font-size: 12px;
    color: #444; 
    border: 1px solid rgb(221, 221, 221); 
    background-color: #f4f4f4;
    font-weight: 400;
    text-transform: inherit;
  }

  .fr-view .uk-table td {
    padding: 8px 8px 5px 8px;
  }
  .uk-label {
    font-weight: 400;
    text-transform: uppercase;
    font-size: 12px;
    color: #222;
    
    display: inline-block;
  }
</style>

<div id="page_content">
  <div id="page_heading" class="print_hidden" data-uk-sticky="{ top: 48, media: 960 }">
    <div class="uk-clearfix" data-uk-margin>
      <div class="uk-float-left">
        <h1><?= __('Ажилд орохыг хүсэгчийн анкет') ?></h1>
      </div>
      <div class="uk-float-right">
        <?= $this->Html->link('<i class="uk-icon-angle-left uk-icon-small"></i> &nbsp;' . __('Буцах'), ['action' => 'index'], ['class' => 'md-btn md-btn-flat md-btn-wave', 'escape' => false]) ?>

        <a href="#" onclick="return xepOnline.Formatter.Format('print-area');" class="md-btn">
          <?= __("Хэвлэх") ?>
        </a>
      </div>
    </div>
  </div>
  <div id="page_content_inner" class="main-print">
    <?= $this->Flash->render() ?>
    <div class="md-card">
      <div class="md-card-content fr-view" id="print-area"> 
        <h2 class="visible-print uk-hidden"><?= __('Ажилд орохыг хүсэгчийн анкет') ?></h2>
        <table class="uk-table" style="width: 100%">
          <tbody>
            <tr>
              <td colspan="2" >
                <h4 class="uk-text-uppercase uk-margin-remove"><?= __("Хувийн мэдээлэл") ?></h4>
              </td>
              <td >
                <h4 class="uk-text-uppercase uk-margin-remove"><?= "Цээж зураг" ?></h4>
              </td>
            </tr>
            <tr>
              <td>
                <span for="" class="uk-label">Овог:</span>
                <strong><?= h($anket->lastName) ?></strong>

              </td>
              <td width="40%">
                <span for="" class="uk-label">Хүйс:</span>
                <strong><?= h($anket->genderLocale) ?></strong>
              </td>
              <td width="20%" rowspan="8" style="border-left: 1px solid #ccc">
                <img src="<?= $anket->photo ?>">
              </td>
            </tr>
            <tr>
              <td>
                <span for="" class="uk-label">Өөрийн нэр:</span>
                <strong><?= h($anket->firstName) ?></strong>
              </td>
              <td>
                <span for="" class="uk-label">Нас:</span>
                <strong><?= h($anket->age) ?></strong>
              </td>
            </tr>
            <tr>
              <td>
                <span for="" class="uk-label">Үндэс угсаа:</span>
                <strong><?= h($anket->nationality) ?></strong>
              </td>
              <td>
                <span for="" class="uk-label">Төрсөн он/сар/өдөр:</span>
                <strong><?= h($anket->birthDate) ?></strong>
              </td>
            </tr>
            <tr>
              <td>
                <span for="" class="uk-label">Регистрийн дугаар:</span>
                <strong><?= h($anket->registrationNumber) ?></strong>
              </td>
              <td>
                <span for="" class="uk-label">Төрсөн газар:</span>
                <strong><?= h($anket->birthPlace) ?></strong>
              </td>
            </tr>
            <tr>
              <td>
                <span for="" class="uk-label">Мэргэжил:</span>
                <strong>
                  <?= h($anket->profession) ?>
                </strong> 
              </td>
              <td>
                <span for="" class="uk-label">Оршин суугаа хаяг:</span>
                <strong>
                  <?= h($anket->currentAddress) ?>
                </strong> 
              </td>
            </tr>            
            <tr>
              <td>
                <span for="" class="uk-label">Гар утас:</span>
                <strong><?= h($anket->cellPhone) ?></strong>

              </td>
              <td>
                <span for="" class="uk-label">Гэрийн утас:</span>
                <strong><?= h($anket->homePhone) ?></strong>
              </td>
            </tr>
            <tr>
              <td>
                <span for="" class="uk-label">Ажлын утас:</span>
                <strong><?= h($anket->contactPhone) ?></strong>

              </td>
              <td>
                <span for="" class="uk-label">Имэйл хаяг:</span>
                <strong><?= h($anket->contactEmail) ?></strong>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <span for="" class="uk-label">Яаралтай үед дамжуулан холбоо барих хүний нэр, утасны дугаар:</span>
                <br>
                <strong>
                  <?= h($anket->emergency) ?>
                </strong> 
              </td>
            </tr>
          </tbody>
        </table>
        <table class="uk-table" style="width: 100%">
          <tbody>
            <tr>
              <td colspan="2" >
                <h4 class="uk-text-uppercase uk-margin-remove">АЛБАН ТУШААЛ</h4>
              </td>
            </tr>
            <tr>
              <td style="width:50%">
                <span for="" class="uk-label"> Таны сонирхож буй ажлын байр:</span>
                <br><strong><?= h($anket->wantedJob) ?></strong>
              </td>
              <td>
                <span for="" class="uk-label">Таны хүсч буй цалин хүлээлт:</span><br>
                <strong><?= h($anket->wantedSalary['min']) ?></strong> - <strong><?= h($anket->wantedSalary['max']) ?></strong>
              </td>
            </tr>
            <tr>
              <td>
                <span for="" class="uk-label">Энэ ажлыг сонирхож буй үндэслэл шалтгаанаа тайлбарлан бичнэ үү:</span>
                <br><strong><?= h($anket->moreDetails) ?></strong> 
              </td>
              <td>
                <span for="" class="uk-label">Ажилд орох боломжтой өдөр:</span><br>
                <strong><?= h($anket->possibleEmploymentDate) ?></strong>
              </td>
            </tr>
          </tbody>
        </table>
        
        <table class="uk-table" style="width: 100%">
          <tbody>
            <tr>
              <td >
                <h4 class="uk-text-uppercase uk-margin-remove">Боловсрол</h4>
              </td>
            </tr>
            <tr>
              <td>
                <span for="" class="uk-label">Төгссөн сургуулийн мэдээлэл:</span>
                <div class="uk-overflow-container" style="padding-top:10px">
                  <table class="uk-table uk-table-hover"> 
                    <thead>
                      <tr>
                        <th>Хаана ямар сургууль төгссөн</th>
                        <th>Элссэн он</th>
                        <th>Төгссөн он</th>
                        <th>Сурлагын үнэлгээ</th>
                        <th>Эзэмшсэн мэргэжил, мэргэжил Боловсролын зэрэг, цол</th>
                        <th>Диплом, гэрчилгээний дугаар</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($anket->schools as $school) : ?>
                        <tr>
                          <td><strong><?= h($school["name"]) ?></strong></td>
                          <td><strong><?= h($school["start_year"]) ?></strong></td>
                          <td><strong><?= h($school["left_year"]) ?></strong></td>
                          <td><strong><?= h($school["gpa"]) ?></strong></td>
                          <td><strong><?= h($school["profession"]) ?></strong></td>
                          <td><strong><?= h($school["diploma"]) ?></strong></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <span for="" class="uk-label" >Гадаад хэлний мэдлэг:</span>
                <div class="uk-overflow-container" style="padding-top:10px">
                  <table class="uk-table uk-table-hover"> 
                    <thead>
                      <tr>
                        <th><?= __("Гадаад хэл") ?></th>
                        <th><?= __("Үзсэн хугацаа") ?></th>
                        <th><?= __("Ярьсаныг ойлгох") ?></th>
                        <th><?= __("Өөрөө ярих") ?></th>
                        <th><?= __("Уншиж ойлгох") ?></th>
                        <th><?= __("Бичиж орчуулах") ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($anket->languages as $language) : ?>
                        <tr>
                          <td><strong><?= h($language["name"]) ?></strong></td>
                          <td><strong><?= h($language["duration"]) ?></strong></td>
                          <td><strong><?= $anket->skillLocale(h($language["listening"])) ?></strong></td>
                          <td><strong><?= $anket->skillLocale(h($language["speaking"])) ?></strong></td>
                          <td><strong><?= $anket->skillLocale(h($language["reading"])) ?></strong></td>
                          <td><strong><?= $anket->skillLocale(h($language["writing"])) ?></strong></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <span for="" class="uk-label" >Таны мэргэжлээрээ болон бусад чиглэлээр хамрагдаж байсан сургалтууд:</span>
                <div class="uk-overflow-container" style="padding-top:10px">
                  <table class="uk-table uk-table-hover"> 
                    <thead>
                      <tr>
                        <th><?= __("Хаана") ?></th>
                        <th><?= __("Хэдэн онд") ?></th>
                        <th><?= __("Ямар чиглэлээр") ?></th>
                        <th><?= __("Үргэлжилсэн хугацаа") ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($anket->courses as $course) : ?>
                        <tr>
                          <td><strong><?= h($course["name"]) ?></strong></td>
                          <td><strong><?= h($course["start_year"]) ?></strong></td>
                          <td><strong><?= h($course["type"]) ?></strong></td>
                          <td><strong><?= h($course["duration"]) ?></strong></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <span for="" class="uk-label">Компьютер дээр ажиллах чадвар:</span>
                <div class="uk-overflow-container" style="padding-top:10px">
                  <table class="uk-table uk-table-hover"> 
                    <thead>
                      <tr>
                        <th>Компьютерийн програмын нэр</th>
                        <th>Эзэмшсэн байдал</th>
                        <th>Тайлбар</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tbody>
                      <?php foreach ($anket->computerKnowledge as $item) : ?>
                        <tr>
                          <td><strong><?= h($item["name"]) ?></strong></td>
                          <td><strong><?= $anket->knowledgeLocale(h($item["skill"])) ?></strong></td>
                          <td><strong><?= h($item["comment"]) ?></strong></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <table class="uk-table" style="width: 100%">
          <tbody>
            <tr>
              <td >
                <h4 class="uk-text-uppercase uk-margin-remove">Ажил хөдөлмөрийн туршлага</h4>
              </td>
            </tr>
            <tr>
              <td>
                <span class="uk-label"><?= __("Таны ажлын туршлага: /одоо эрхэлж буй ажлаасаа эхлэн бөглөнө үү/") ?></span>
                <div class="uk-overflow-container" style="padding-top:10px">
                  <table class="uk-table uk-table-hover"> 
                    <thead>
                      <tr>
                        <th><?= __("Байгууллагын нэр") ?></th>
                        <th><?= __("Орсон огноо") ?></th>
                        <th><?= __("Гарсан огноо") ?></th>
                        <th><?= __("Эрхэлж байсан албан тушаал") ?></th>
                        <th><?= __("Цалингийн хэмжээ") ?></th>
                        <th><?= __("Ажлаас гарсан шалтгаан") ?></th>
                        <th><?= __("Удирдах албан тушаалтны нэр, утасны дугаар") ?></th>
                        <th><?= __("Таны эрхэлж буй ажлын үндсэн чиг үүргүүд") ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($anket->jobs as $job) : ?>
                        <tr>
                          <td><strong><?= h($job["organizationName"]) ?></strong></td>
                          <td><strong><?= h($job["startDate"]) ?></strong></td>
                          <td><strong><?= h($job["endDate"]) ?></strong></td>
                          <td><strong><?= h($job["position"]) ?></strong></td>
                          <td><strong><?= h($job["salary"]) ?></strong></td>
                          <td><strong><?= h($job["quitReason"]) ?></strong></td>
                          <td><strong><?= h($job["executives"]) ?></strong></td>
                          <td><strong><?= h($job["comment"]) ?></strong></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <span for="" class="uk-label" > Таны мэдлэг чадварын талаар сайн мэдэх 2 хүний тухай мэдээлэл:</span>
                <div class="uk-overflow-container" style="padding-top:10px">
                  <table class="uk-table uk-table-hover"> 
                    <thead>
                      <tr>
                        <th><?= __("Нэр") ?></th>
                        <th><?= __("Байгууллагын нэр") ?></th>
                        <th><?= __("Албан тушаал") ?></th>
                        <th><?= __("Холбоо барих утас") ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($anket->mentions as $mention) : ?>
                        <tr>
                          <td><strong><?= h($mention["name"]) ?></strong></td>
                          <td><strong><?= h($mention["work"]) ?></strong></td>
                          <td><strong><?= h($mention["position"]) ?></strong></td>
                          <td><strong><?= h($mention["contact"]) ?></strong></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <span for="" class="uk-label" >Таны авч байсан шагнал урамшуулал</span>
                <div class="uk-overflow-container" style="padding-top:10px">
                  <table class="uk-table uk-table-hover"> 
                    <thead>
                      <tr>
                        <th><?= __("Шагналын нэр") ?></th>
                        <th><?= __("Хаанаас авсан	") ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($anket->awards as $item) : ?>
                        <tr>
                          <td><strong><?= h($item["name"]) ?></strong></td>
                          <td><strong><?= h($item["comment"]) ?></strong></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <span for="" class="uk-label" >Та үндсэн мэргэжлээс гадна ямар чиглэлийн ажил хийх сонирхолтой вэ?</span>
                <?php if(!empty($anket->possibleAlternativePosition[0])): ?>
                  <br><strong><?= h($anket->possibleAlternativePosition[0]) ?></strong>
                <?php endif; ?>
                <?php if(!empty($anket->possibleAlternativePosition[1])): ?>
                  <br><strong><?= h($anket->possibleAlternativePosition[1]) ?></strong>
                <?php endif; ?>
                <?php if(!empty($anket->possibleAlternativePosition[2])): ?>
                  <br><strong><?= h($anket->possibleAlternativePosition[2]) ?></strong>
                <?php endif; ?>
                <?php if(!empty($anket->possibleAlternativePosition[3])): ?>
                <br><strong><?= h($anket->possibleAlternativePosition[3]) ?></strong>
              <?php endif; ?>
              </td>
            </tr>
          </tbody>
        </table>
        <table class="uk-table" style="width: 100%">
          <tbody>
            <tr>
              <td >
                <h4 class="uk-text-uppercase uk-margin-remove">Таны урлаг спортын авъяас</h4>
              </td>
            </tr>
            <tr>
              <td>
                <span for="" class="uk-label" >Таны урлаг спортын авъяас</span>
                <div class="uk-overflow-container" style="padding-top:10px">
                  <table class="uk-table uk-table-hover"> 
                    <thead>
                      <tr>
                        <th><?= __("Ямар төрлөөр") ?></th>
                        <th><?= __("Зэрэг") ?></th>
                        <th><?= __("Шагналтай эсэх") ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($anket->sports as $item) : ?>
                        <tr>
                          <td><strong><?= h($item["name"]) ?></strong></td>
                          <td><strong><?= h($item["level"]) ?></strong></td>
                          <td><strong><?= h($item["awards"]) ?></strong></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <table class="uk-table" style="width: 100%">
          <tbody>
            <tr>
              <td >
                <h4 class="uk-text-uppercase uk-margin-remove">Гэр бүлийн байдал</h4>
              </td>
            </tr>
            <tr>
              <td colspan="3">
                <span for="" class="uk-label" >Гэр бүлийн мэдээлэл: /Зөвхөн хамт амьдарч байгаа хүмүүсийг бичнэ./</span>
                <div class="uk-overflow-container" style="padding-top:10px">
                  <table class="uk-table uk-table-hover"> 
                    <thead>
                      <tr>
                        <th width="120"><?= __("Таны юу болох") ?></th>
                        <th width="120"><?= __("Овог нэр") ?></th>
                        <th width="80"><?= __("Төрсөн он") ?></th>
                        <th><?= __("Төрсөн аймаг, сум, хот") ?></th>
                        <th><?= __("Одоо эрхэлж буй ажил, албан тушаал") ?></th>
                        <th width="100"><?= __("Утасны дугаар") ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($anket->familyMembers as $familyMember) : ?>
                        <tr>
                          <td><strong><?= h($familyMember["relation"]) ?></strong></td>
                          <td><strong><?= h($familyMember["fullName"]) ?></strong></td>
                          <td><strong><?= h($familyMember["birthday"]) ?></strong></td>
                          <td><strong><?= h($familyMember["birthplace"]) ?></strong></td>
                          <td><strong><?= h($familyMember["work"]) ?></strong></td>
                          <td><strong><?= h($familyMember["phone"]) ?></strong></td>
                        </tr>
                      <?php endforeach ?>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="3">
                <span for="" class="uk-label" >Ураг төрлийн байдал: /Өрх тусгаарласан ах, дүүс, өөрийн болон хадам эцэг, эх эгч нарыг оруулан бичнэ./</span>
                <div class="uk-overflow-container" style="padding-top:10px">
                  <table class="uk-table uk-table-hover"> 
                    <thead>
                      <tr>
                        <th width="120"><?= __("Таны юу болох") ?></th>
                        <th width="120"><?= __("Овог нэр") ?></th>
                        <th width="80"><?= __("Төрсөн он") ?></th>
                        <th><?= __("Төрсөн аймаг, сум, хот") ?></th>
                        <th><?= __("Одоо эрхэлж буй ажил, албан тушаал") ?></th>
                        <th width="100"><?= __("Утасны дугаар") ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($anket->relatives as $item) : ?>
                        <tr>
                          <td><strong><?= h($item["relation"]) ?></strong></td>
                          <td><strong><?= h($item["fullName"]) ?></strong></td>
                          <td><strong><?= h($item["birthday"]) ?></strong></td>
                          <td><strong><?= h($item["birthplace"]) ?></strong></td>
                          <td><strong><?= h($item["work"]) ?></strong></td>
                          <td><strong><?= h($item["phone"]) ?></strong></td>
                        </tr>
                      <?php endforeach ?>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="3">
                <span for="" class="uk-label" >Таны хамаатан садан манай компанид ажилладаг бол дараахь хүснэгтийг бөглөнө./</span>
                <div class="uk-overflow-container" style="padding-top:10px">
                  <table class="uk-table uk-table-hover"> 
                    <thead>
                      <tr>
                        <th width="120"><?= __("Таны юу болох") ?></th>
                        <th width="120"><?= __("Овог нэр") ?></th>
                        <th width="80"><?= __("Төрсөн он") ?></th>
                        <th><?= __("Төрсөн аймаг, сум, хот") ?></th>
                        <th><?= __("Одоо эрхэлж буй ажил, албан тушаал") ?></th>
                        <th width="100"><?= __("Утасны дугаар") ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($anket->relativeWorkHere as $item) : ?>
                        <tr>
                          <td><strong><?= h($item["relation"]) ?></strong></td>
                          <td><strong><?= h($item["fullName"]) ?></strong></td>
                          <td><strong><?= h($item["birthday"]) ?></strong></td>
                          <td><strong><?= h($item["birthplace"]) ?></strong></td>
                          <td><strong><?= h($item["work"]) ?></strong></td>
                          <td><strong><?= h($item["phone"]) ?></strong></td>
                        </tr>
                      <?php endforeach ?>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <table class="uk-table" style="width: 100%">
          <tbody>
            <tr>
              <td  colspan="2">
                <h4 class="uk-text-uppercase uk-margin-remove">Дипломын хуулбар</h4>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <?php if (!empty($anket->diploma)): ?>
                  <a href="<?= $anket->diploma ?>" target="_blank">Файл 1 (нээж үзэх)</a>
                <?php endif; ?>
                <?php if (!empty($anket->diploma2)): ?>
                  <br>
                  <a href="<?= $anket->diploma2 ?>" target="_blank">Файл 2 (нээж үзэх)</a>
                <?php endif; ?>
                <?php if (!empty($anket->diploma3)): ?>
                  <br>
                  <a href="<?= $anket->diploma3 ?>" target="_blank">Файл 3 (нээж үзэх)</a>
                <?php endif; ?>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>