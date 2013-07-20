<?php
namespace Adminpanel\Controller;

use \Zend\View\Model\ViewModel as View;

abstract class Controller extends \Zend\Mvc\Controller\AbstractActionController{
	protected $name='AbstractAdminpanelController';
	
	abstract protected function getMenu();
	
	public function getName(){
		return $this->name;
	}
	
	public function menuAction(){
		$view=new View(array('links'=>$this->getMenu()));
		$view->setTemplate('adminpanel/menu');
		return $view;
	}
}
?>
