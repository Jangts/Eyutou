<?php
namespace PM\_2\traits;

use Lib\models\DocumentElementModel;

trait form {
	public static $inputs = [],$selectOptions =[];

	public static function __loadFormInputs(){
        return static::$inputs;
    }

    public static function buildForm($data, $method = 'POST', $action = ''){
		$inputs = self::__loadFormInputs();
		$form = new DocumentElementModel('form');
		$group = new DocumentElementModel('div');
		$group->setAttr('class', 'input-section-group');
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
			$div = new DocumentElementModel('div', '<label class="input-label">'.$meta['display_name'].'</label>');
			$input = '';
			switch($meta['input_type']){
				case 'hide':
				$input = new DocumentElementModel('input', $value);
				$input->addClass('hide-item');
				$input->setAttr('hidden', 'hidden');
				$input->setAttr('name', $meta['field_name']);
				// input可以直接用初始内容来补全value
				// $input->setAttr('value', $value);
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
							$content .= '<label><input name="'.$meta['field_name'].'" type="radio" value ="'.$option[0].'" checked="checked">'.$option[1].'</label>';
						}else{
							$content .= '<label><input name="'.$meta['field_name'].'" type="radio" value ="'.$option[0].'">'.$option[1].'</label>';
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
				$input->addClass('edit-item');
				$textarea = new DocumentElementModel('textarea', $value);
				$textarea->addClass('edit-text-area');
				$textarea->setAttr('name', $meta['field_name']);
				$input->appendContent($textarea);
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
				$hidden = new DocumentElementModel('input');
				$hidden->setAttr('hidden', 'hidden');
				$hidden->setAttr('name', $meta['field_name']);
				$hidden->addClass('img-url');
				$hidden->setAttr('value', $value);
				$file = new DocumentElementModel('input');
				$file->setAttr('hidden', 'hidden');
				$file->addClass('img-file');
				$file->setAttr('type', 'file');
				// $file->setAttr('multiple', 'multiple');
				$input = new DocumentElementModel('figure', $hidden);
				$input->appendElement($file);
				if($value){
					$input->appendContent('<img class="img-show" src="'.$value.'" data-reset-src="'.$value.'"/><i class="image-button"></i>');
				}else{
					$input->appendContent('<img class="img-show" src="'.__aurl__.'uploads/files/defaultpic" data-reset-src="'.__aurl__.'uploads/files/defaultpic"/><i class="image-button"></i>');
				}
				$input->addClass('img-item');
				break;

				default:
				$input = new DocumentElementModel('input');
				$input->addClass('text-item');
				// input可以自动补全type
				$input->setAttr('name', $meta['field_name']);
				$input->setAttr('value', $value);
			}
			$div->appendContent($input);
			$div->addClass('input-section');
			$group->appendElement($div);
		}
		$form->appendElement($group);
		$form->setAttr('name', 'myform');
		$form->setAttr('method', $method);
		$form->setAttr('action', $action);
		return $form->str();
	}
}