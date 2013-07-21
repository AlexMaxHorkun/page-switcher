<?php
namespace Adminpanel\Controller;

use \Adminpanel\Form\EditTemplate as EditTemplateForm;
use \Zend\View\Model\ViewModel as View;

class Main extends \Zend\Mvc\Controller\AbstractActionController{
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
	
	function menuAction(){
		$view=new View(array('links'=>$this->getMenu()));
		$view->setTemplate('adminpanel/menu');
		return $view;
	}
	
	public function editTemplateAction(){
		$_tp='adminpanel/page-layout';
		$savedUrl=null;
		$form=$this->getServiceLocator()->get('templateForm');
		
		if($this->getRequest()->isPost()){
			$form->bind($this->request->getPost());
			file_put_contents(__DIR__.'/../../../view/'.$_tp.'.phtml',$form->get('text')->getValue());
			$savedUrl='adminpanel';
		}
		else{
			$form->get('text')->setValue(file_get_contents(__DIR__.'/../../../view/'.$_tp.'.phtml'));
		}
		
		return array('form'=>$form,'saved_url'=>$savedUrl);
	}
}
?>
