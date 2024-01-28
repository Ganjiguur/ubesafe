<?php

namespace App\Controller\Traits;

use Cake\Mailer\Email;
use Cake\Utility\Inflector;

trait FormSubmitTrait {

  public function handleFormSubmit() {
    $this->loadModel('FormRecords');
    $formRecord = $this->FormRecords->newEntity();
    $this->set('formRecord', $formRecord);
    if (!$this->request->is('post')) {
      return;
    }
    
    $requestData = $this->request->getData();
    $errorMsg = __('Хүсэлт алдаатай байна. Та мэдээллээ шалгаад дахин илгээнэ үү.');

    $captchaResult = $this->_validateRecaptcha('Users.reCaptcha.formSubmit');
    if (!$captchaResult) {
      $this->returnError($errorMsg);
      return;
    }
    
    if(empty($this->request->data['form_id'])) {
      $this->returnError($errorMsg);
      return;
    }
    $fid = $this->request->data['form_id'];

    $this->loadModel('Forms');
    $form = $this->Forms->find('all', ['site' => 'all',])
            ->where(['Forms.id' => $fid, 'Forms.active' => true])
            ->first();
    if(empty($form)) {
      $this->returnError($errorMsg);
      return;
    }

    $data = $this->validateFormFields($form);
    if(isset($data['error'])) {
      $this->returnError($data['error']);
      return;
    }

    $successMsg = !empty($form->success_msg) ? $form->success_msg : __("Баярлалаа.");
    $formRecord = $this->FormRecords->patchEntity($formRecord, $data);
    if ($this->FormRecords->save($formRecord)) {
        $this->_sendFormEmail($form, $formRecord);
        $this->Flash->success($successMsg, ['key' => 'customform']);
        $this->set("showForm", true);
    } else {
      $this->Flash->error($errorMsg, ['key' => 'customform']);
    }
    $this->set('formRecord', $formRecord);
  }
  
  private function returnError($error) {
    $this->Flash->error($error, ['key' => 'customform']);
    $formRecord = $this->FormRecords->newEntity($this->request->getData());
    $this->set('formRecord', $formRecord);
  }
  
  private function validateFormFields($form) {
      $error = ['error' => __('Та шаардлагатай талбаруудыг бүрэн бөглөнө үү.')];
      $requestData = $this->request->getData();
      $fields = $form->fields ? : [[]];
      $requestData['client_ip'] = $this->request->clientIp();
      //validate required fields
      foreach ($fields as $field) {
        if(in_array($field['type'], ['seperator']) || empty($field['required'])) { 
            continue; 
        }

        $label = Inflector::slug(mb_strtolower(h($field['label'])), '_');
        
        if($field['type'] == "register") {
          if(empty($requestData['reg_letter1_' . $label]) || empty($requestData['reg_letter2_' . $label]) || empty($requestData['reg_numbers_' . $label])) {
            return $error;
          } 
          $requestData['data'][$label] = $requestData['reg_letter1_' . $label] . $requestData['reg_letter2_' . $label] . $requestData['reg_numbers_' . $label];
          unset($requestData['data']['reg_letter1_' . $label]);
          unset($requestData['data']['reg_letter2_' . $label]);
          unset($requestData['data']['reg_numbers_' . $label]);
        }

        if($field['type'] !== "file" && empty($requestData['data'][$label])) {
          return $error;
        }

        if($field['type'] === "file" && empty($_FILES['data-' . $label])) {
          return $error;
        }
      }
      
      //validate and upload file field data
      $uploadedFiles = [];
      foreach ($fields as $field) {
        if($field['type'] !== "file") { 
            continue; 
        }
        $label = Inflector::slug(mb_strtolower(h($field['label'])), '_');
        if(empty($_FILES['data-' . $label]) || empty($_FILES['data-' . $label]['size'])) { 
            continue; 
        }
        
        $file = $_FILES['data-' . $label];
        $result = [];
        if($this->FileManager->validateFile($file['tmp_name'], $field['option'])) {
          if($field['option'] == "image") {
            $result = $this->FileManager->processImage($file, true, 'client_requests');                
          } else {
              $result = $this->FileManager->processFile($file, 'client_requests', true);
          }
        }

        if(isset($result['success']) && $result['success']) {
            $requestData['data'][$label] = $result['link'];
            $uploadedFiles[] = $result['link'];
        } else {
            $this->deleteFormFiles($uploadedFiles);
            return ['error' => __("Хавсралт файлыг хуулахад алдаа гарлаа. Та хамгийн ихдээ 5MB хэмжээтэй файл хавсаргах боломжтой.")];
        }
      }
      
      return $requestData;
    }
    
    private function deleteFormFiles($files){
        foreach ($files as $file) {
            $this->FileManager->deleteFile($file);
        }
    }
    
    protected function _sendFormEmail($form, $formData) {
        if(empty($form->email)) {
            return;
        }
        
        $to = explode(',', str_replace(' ', '', $form->email));
                
        $email = new Email('default');
        $email->template('formdata', 'default');
        $email->viewVars(['form' => $form]);
        $email->viewVars(['formdata' => $formData]);
        $email->emailFormat("html");
        $email->from(['no-reply@ckbank.mn' => "CKbank"])
            ->to($to)
            ->subject("New request from www.ckbank.mn")
            ->send();
    }
}

