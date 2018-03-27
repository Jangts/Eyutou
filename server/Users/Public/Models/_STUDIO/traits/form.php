<?php
namespace PM\_STUDIO\traits;

use Lib\models\DocumentElementModel;

trait form {
	public static $inputs = [],$selectOptions =[];

	public static function __loadFormInputs(){
        return static::$inputs;
    }

    public static function buildForm($data, $method = 'POST', $action = ''){
		$inputs = self::__loadFormInputs();
		$form = new DocumentElementModel('form.tangram-form');
		$group = new DocumentElementModel('div');
		$group->setAttr('class', 'inputs-section');
		foreach($inputs as $meta){
			if(isset($data[$meta['field_name']])){
				$value = $data[$meta['field_name']];
			}else{
				if(isset($meta['default_value'])){
					$value = $meta['default_value'];
				}else{
					$value = '';
				}
			}
			$inputs = new DocumentElementModel('div.tangram-inputs', '<label class="input-label">'.$meta['display_name'].'</label>');
			$input = '';
			switch($meta['input_type']){
				case 'hide':
				$inputs->setAttr('data-type', 'hidden');
				$input = new DocumentElementModel('input', $value);
				// $input->addClass('hide-item');
				$input->setAttr('hidden', 'hidden');
				$input->setAttr('name', $meta['field_name']);
				// input可以直接用初始内容来补全value
				$input->setAttr('value', $value);
				break;

				case 'select':
				if(isset(static::$selectOptions[$meta['field_name']])){
					$content = '';
					foreach(static::$selectOptions[$meta['field_name']] as $option){
						if(strval($option[0]) === strval($value)){
							$content .= '<option value ="'.$option[0].'" selected="selected">'.$option[1].'</option>';
						}else{
							$content .= '<option value ="'.$option[0].'">'.$option[1].'</option>';
						}
					}
				}else{
					$content = '<option value ="'.$value.'" selected="selected">'.$value.'</option>';
				}
				$input = new DocumentElementModel('select', $content);
				$input->addClass('sel-items');
				$input->setAttr('name', $meta['field_name']);
				break;

				case 'radio':
				if(isset(static::$selectOptions[$meta['field_name']])){
					$content = '';
					foreach(static::$selectOptions[$meta['field_name']] as $option){
						if(($option[0] === $value)||($option[0] == $value)){
							$content .= '<label class="radio-label"><input name="'.$meta['field_name'].'" type="radio" value ="'.$option[0].'" checked="checked">'.$option[1].'</label>';
						}else{
							$content .= '<label class="radio-label"><input name="'.$meta['field_name'].'" type="radio" value ="'.$option[0].'">'.$option[1].'</label>';
						}
					}
				}else{
					$content = '<input name="'.$meta['field_name'].'" type="radio" value ="'.$value.'" checked="checked"><label>'.$value.'</label>';
				}
				$input = new DocumentElementModel('div', $content);
				$input->addClass('radios');
				break;

				case 'textarea':
				$input = new DocumentElementModel('textarea', $value);
				$input->addClass('text-area');
				$input->setAttr('name', $meta['field_name']);
				break;

				case 'longtext':
				$input = new DocumentElementModel('textarea', $value);
				$input->addClass('long-item');
				$input->setAttr('name', $meta['field_name']);
				break;

				case 'hightext':
				$input = new DocumentElementModel('textarea', $value);
				$input->addClass('high-item');
				$input->setAttr('name', $meta['field_name']);
				break;

				case 'editor':
				$input = new DocumentElementModel('div');
				$input->addClass('custom-items');
				$textarea = new DocumentElementModel('textarea', $value);
				$textarea->addClass('edit-text-area');
				$textarea->setAttr('name', $meta['field_name']);
				$input->appendContent($textarea);
				break;

				case 'ueditor':
				$input = new DocumentElementModel('div');
				$id = 'ueditor'.uniqid();
				$input->addClass('custom-items');
				$textarea = new DocumentElementModel('textarea', $value);
				$textarea->addClass('uedit-text-area');
				$textarea->addClass('high-edit-text-area');
				$textarea->setAttr('name', $meta['field_name']);
				$textarea->setAttr('data-ueditor-id', $id);
				$textarea->setAttr('hidden', 'hidden');
				$ueditor = new DocumentElementModel('div');
				$ueditor->addClass('uedit-workspace');
				$ueditor->setAttr('id', $id);
				$input->appendContent($textarea);
				$input->appendContent($ueditor);
				break;

				case 'datetime':
				case 'fulldate':
				case 'timeofday':
				$input = new DocumentElementModel('input');
				$input->addClass($meta['input_type'].'-item');
				$input->setAttr('readonly', 'readonly');
				$input->setAttr('name', $meta['field_name']);
				$input->setAttr('value', $value);
				break;

				case 'counter':
				$input = new DocumentElementModel('input');
				$input->addClass('num-item');
				$input->setAttr('name', $meta['field_name']);
				$input->setAttr('type', 'number');
				$input->setAttr('value', $value);
				break;

				case 'image':
				case 'avatar':
				case 'avatar1x1':
				case 'figure':
				case 'figure4x3':
				case 'figure3x4':
				case 'photo':
				case 'photo3x2':
				case 'photo2x3':
				case 'banner':
				case 'banner100':
				$input = new DocumentElementModel('figure.figure-items');

				$item = new DocumentElementModel('div.pic-uploader');
				$item->setAttr('data-subtype', $meta['input_type']);
				if($value){
					$item->setAttr('data-reset-src', $value);
				}else{
					$item->setAttr('data-reset-src', __aurl__);
				}
				$hidden = new DocumentElementModel('input');
				$hidden->setAttr('name', $meta['field_name']);
				$hidden->addClass('pic-src');
				$hidden->setAttr('value', $value);
				$item->appendElement($hidden);
				$input->appendElement($item);
				break;
				
				case 'video':
				case 'video4x3':
				case 'video16x9':
				$input = new DocumentElementModel('div.video-item');

				$item = new DocumentElementModel('div.pic-uploader');
				$item->setAttr('data-subtype', $meta['input_type']);
				if($value){
					$item->setAttr('data-reset-src', $value);
				}else{
					$item->setAttr('data-reset-src', __aurl__);
				}
				$hidden = new DocumentElementModel('input');
				$hidden->setAttr('name', $meta['field_name']);
				$hidden->addClass('pic-src');
				$hidden->setAttr('value', $value);
				$item->appendElement($hidden);
				$input->appendElement($item);
				break;

				default:
				$input = new DocumentElementModel('input');
				$input->addClass('text-item');
				// input可以自动补全type
				$input->setAttr('name', $meta['field_name']);
				$input->setAttr('value', $value);
			}
			$inputs->appendContent($input);
			$inputs->addClass('input-section');
			$group->appendElement($inputs);
		}
		$form->appendElement($group);
		$form->setAttr('name', 'myform');
		$form->setAttr('method', $method);
		$form->setAttr('action', $action);
		return $form->str();
	}
}