<?php
namespace Adminpanel\Controller;

use \Zend\View\Model\ViewModel as View;
use \Adminpanel\Model\Page as PageModel;

class Page extends ModelController{
	private $path_to_pages='/../../../../Application/view/pages';

	protected function getForm($action){
		$form=parent::getForm($action);
		if($action=='edit'&&$this->getModel()){
			$blocks=$this->getServiceLocator()->get('blockMapper')->get();
			foreach($blocks as $block){
				$file=__DIR__.$this->path_to_pages.'/page_'.$this->getModel()->id.'/block_'.$block->id.'.phtml';
				if(file_exists($file)){
					$form->get('block_'.$block->id)->setValue(file_get_contents($file));
				}
			}
		}
		return $form;
	}
	
	protected function getSuccessUrl($action){
		if($action=='add'){
			return $this->url()->fromRoute('adminpanel/seg',array('controller'=>'page'));
		}
		else
			return $this->url()->fromRoute('adminpanel/seg',array('controller'=>'page','action'=>'list'));
		
		return null;
	}

	protected function addProcess($data){
		$res=$this->getServiceLocator()->get('pageMapper')->insert(new PageModel((array)$data));
		if($res){
			$path=__DIR__.$this->path_to_pages.'/page_'.$res;
			mkdir($path);
			foreach($data as $block=>$text){
				if(preg_match('"^block_[0-9]+$"i',$block)){
					file_put_contents($path.'/'.$block.'.phtml',$text);
				}
			}
		}
		return (boolean)$res;
	}
	
	protected function editProcess($data){
		$res=$this->getServiceLocator()->get('pageMapper')->save(new PageModel((array)$data));
		if($res){
			$path=__DIR__.$this->path_to_pages.'/page_'.$data['id'];
			foreach($data as $block=>$text){
				if(preg_match('"^block_[0-9]+$"i',$block)){
					file_put_contents($path.'/'.$block.'.phtml',$text);
				}
			}
		}
		return (boolean)$res;
	}
	
	protected function deleteProcess($model){
		$res=(boolean)$this->getServiceLocator()->get('pageMapper')->delete($model);
		if($res){
			\AMH\File\File::deleteDirectory(__DIR__.$this->path_to_pages.'/page_'.$model->id);
		}
		return $res;
	}

	protected function getListColumns(){
		return array(
			'Название',
			'Статистика',
			'Действия',
		);
	}
	
	protected function getColumnTemplates(){
		return array(
			'"{:name}"',
			'<a href="'.$this->url()->fromRoute('adminpanel/seg',array('controller'=>'page','action'=>'stat')).'/{:id}">Посмотреть</a>',
			'<a href="'.$this->url()->fromRoute('adminpanel/seg',array('controller'=>'page','action'=>'edit')).'/{:id}">Изменить</a> 
			&nbsp; 
			<a href="'.$this->url()->fromRoute('adminpanel/seg',array('controller'=>'page','action'=>'delete')).'/{:id}">Удалить</a>',
		);
	}
	
	protected function getModelList(){
		return $this->getServiceLocator()->get('pageMapper')->get();
	}
	
	protected function getModel(){
		$id=$this->getEvent()->getRouteMatch()->getParam('id');
		if($id===null){
			return FALSE;
		}
		$filter=array('id'=>$id);		
		return $this->getServiceLocator()->get('pageMapper')->get($filter);
	}

	function menuAction(){
		$view=new View(array('links'=>array(
			(object)array(
				'href'=>$this->url()->fromRoute('adminpanel/seg',array(
					'controller'=>'page',
					'action'=>'add')
				),
				'title'=>'Добавить страницу',
			),
			(object)array(
				'href'=>$this->url()->fromRoute('adminpanel/seg',array(
					'controller'=>'page',
					'action'=>'list')
				),
				'title'=>'Список страниц',
			),
		)));
		$view->setTemplate('adminpanel/menu');
		return $view;
	}
}
?>
