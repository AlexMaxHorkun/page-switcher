<?php
namespace Adminpanel\Controller;

use \Zend\View\Model\ViewModel as View;
use \Adminpanel\Model\Block as BlockModel;

class Block extends ModelController{
	protected function getSuccessUrl($action){
		if($action=='add'){
			return $this->url()->fromRoute('adminpanel/seg',array('controller'=>'block'));
		}
		else
			return $this->url()->fromRoute('adminpanel/seg',array('controller'=>'block','action'=>'list'));
		
		return null;
	}

	protected function addProcess($data){
		return (boolean)$this->getServiceLocator()->get('blockMapper')->insert(new BlockModel((array)$data));
	}
	
	protected function editProcess($data){
		return (boolean)$this->getServiceLocator()->get('blockMapper')->save(new BlockModel((array)$data));
	}
	
	protected function deleteProcess($model){
		return (boolean)$this->getServiceLocator()->get('blockMapper')->delete($model);
	}

	protected function getListColumns(){
		return array(
			'Название',
			'Действия',
		);
	}
	
	protected function getColumnTemplates(){
		return array(
			'"{:name}"',
			'<a href="'.$this->url()->fromRoute('adminpanel/seg',array('controller'=>'block','action'=>'edit')).'/{:id}">Изменить</a> 
			&nbsp; 
			<a href="'.$this->url()->fromRoute('adminpanel/seg',array('controller'=>'block','action'=>'delete')).'/{:id}">Удалить</a>',
		);
	}
	
	protected function getModelList(){
		return $this->getServiceLocator()->get('blockMapper')->get();
	}
	
	protected function getModel(){
		$id=$this->getEvent()->getRouteMatch()->getParam('id');
		if($id===null){
			return FALSE;
		}
		$filter=array('id'=>$id);		
		return $this->getServiceLocator()->get('blockMapper')->get($filter);
	}

	function menuAction(){
		$view=new View(array('links'=>array(
			(object)array(
				'href'=>$this->url()->fromRoute('adminpanel/seg',array(
					'controller'=>'block',
					'action'=>'add')
				),
				'title'=>'Добавить блок',
			),
			(object)array(
				'href'=>$this->url()->fromRoute('adminpanel/seg',array(
					'controller'=>'block',
					'action'=>'list')
				),
				'title'=>'Список блоков',
			),
		)));
		$view->setTemplate('adminpanel/menu');
		return $view;
	}
}
?>
