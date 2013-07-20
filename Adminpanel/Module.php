<?php
namespace Adminpanel;

use \Zend\View\Model\ViewModel as View;

class Module{
	function init($mm){
		/*$mm->getEventManager()->getSharedManager()->attach(
			'application',
			\Zend\Mvc\MvcEvent::EVENT_RENDER,
			function($e){
				print_r(get_class_methods($e));
				$view=$e->getViewModel();
				$layout=new View(array('navi'=>'<ZAGLUSHKA !!!!>','name'=>$e->getController()->getName()));
				$layout->setTemplate('adminpanel/layout');
				$layout->addChild($view,'content');
				$e->setViewModel($layout);
			}
		);*/
	}
	
	function getConfig(){
		return include __DIR__.'/config/config.php';
	}
	
	function getServiceConfig(){
		return array();
	}
	
	function getAutoloaderConfig(){
		return array(
			'Zend\Loader\StandardAutoloader'=>array(
				'namespaces'=>array(
					'Adminpanel'=>__DIR__.'/src/'.__NAMESPACE__,
				),
			),
		);
	}
}
?>
