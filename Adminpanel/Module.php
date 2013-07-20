<?php
namespace Adminpanel;

class Module{
	function init($mm){
	
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
