<?php
namespace Adminpanel\Controller;

class Main extends Controller{
	protected function getMenu(){
		return array(
			(object)array(
				'href'=>$this->url()->fromRoute('adminpanel/seg',array('controller'=>'main','action'=>'edit-template')),
				'title'=>'Редактировать гланый шаблон страницы'
			),
			(object)array(
				'href'=>$this->url()->fromRoute('adminpanel/seg',array('controller'=>'block')),
				'title'=>'Блоки'
			),
			(object)array(
				'href'=>$this->url()->fromRoute('adminpanel/seg',array('controller'=>'page')),
				'title'=>'Страницы'
			),
		);
	}
}
?>