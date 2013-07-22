<?php
namespace Adminpanel;

use \Zend\View\Model\ViewModel as View;

class Module{
	function onBootstrap(\Zend\Mvc\MvcEvent $me){
		$me->getApplication()->getEventManager()->attach(
			'render',
			function($e){				
				$cnt=explode('\\',$e->getRouteMatch()->getParam('controller'));
				if($cnt[0]=='Adminpanel'){
				$view=$e->getViewModel();
				$navi=array();
				$cnt=explode('\\',$e->getRouteMatch()->getParam('controller'));
				$cnt=strtolower($cnt[2]);
				$act=$e->getRouteMatch()->getParam('action');
				if($cnt!='main'||($cnt=='main'&&$act!='menu')){
					$navi[]=(object)array('title'=>'Админка','href'=>array('adminpanel',array('controller'=>'main')));
				}
				if($cnt=='block'&&$act!='menu'){
					$navi[]=(object)array('title'=>'Блоки','href'=>array('adminpanel/seg',array('controller'=>'block')));
				}
				if($cnt=='page'&&$act!='menu'){
					$navi[]=(object)array('title'=>'Страницы','href'=>array('adminpanel/seg',array('controller'=>'page')));
				}
				switch($cnt){
				case 'main':$name='Админка'; break;
				case 'block':$name='Блоки'; break;
				case 'page':$name='Страницы'; break;
				}
				
				$naviView=new View(array('links'=>$navi));
				$naviView->setTemplate('adminpanel/navi');
				
				$layout=new View(array('name'=>$name));
				$layout->setTemplate('adminpanel/layout');
				$layout->addChild($view,'content');
				$layout->addChild($naviView,'navi');
				$e->setViewModel($layout);
				}
			}
		);
		
		$me->getApplication()->getEventManager()->getSharedManager()->attach(
			'Application\Controller\IndexController',
			'got_page',
			function($e){
				$page=$e->getParam('page');
				if($page){
					$e->getTarget()->getServiceLocator()->get('statMapper')->insert(
						new \Adminpanel\Model\Statistic(array(
							'pageId'=>$page->id,
							'ip'=>$_SERVER['REMOTE_ADDR'],
							'date'=>date('Y-m-d'),
							'time'=>date('H:i'),
						))
					);
				}
			}
		);
	}
	
	function getConfig(){
		return include __DIR__.'/config/config.php';
	}
	
	function getServiceConfig(){
		return array(
			'factories'=>array(
				'pageMapper'=>function($sm){
					return new Mapper\Page($sm->get('pdo'),array('getArrayOnly'=>FALSE));
				},
				'blockMapper'=>function($sm){
					return new Mapper\Block($sm->get('pdo'),array('getArrayOnly'=>FALSE));
				},
				'statMapper'=>function($sm){
					return new Mapper\Statistic($sm->get('pdo'),array(
						'getArrayOnly'=>FALSE,
						'fieldsMap'=>array(
							'page_id'=>'pageId',
						),
					));
				},
				
				'pdo'=>function($sm){
					$pdo=new \PDO('mysql:host=localhost;dbname=atask','root','4837570Mind');
					$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
					return $pdo;
				},
				
				'templateForm'=>function($sm){
					$form=new \Zend\Form\Form;
					$form->add(array('name'=>'text','type'=>'textarea','options'=>array('label'=>'Шаблон')));
					$form->add(array('name'=>'submit','type'=>'submit','attributes'=>array('value'=>'Сохранить')));
					return $form;
				},
				
				'blockAddForm'=>function($sm){
					$form=new \Zend\Form\Form('block-add-form');
					$form->setLabel('Добавление блока');
					$form->add(array('name'=>'name','type'=>'text','options'=>array('label'=>'Название блока')));
					$form->add(array('name'=>'id','type'=>'hidden'));
					$form->add(array('name'=>'submit','type'=>'submit','attributes'=>array('value'=>'Сохранить')));
					
					$filter=new \Zend\InputFilter\Factory;
					$form->setInputFilter($filter->createInputFilter(array(
						'name'=>array(
							'name'=>'name',
							'required'=>TRUE,
							'validators'=>array(
								array(
									'name'=>'not_empty',
								),
								array(
									'name'=>'string_length',
									'options'=>array(
										'min'=>2,
									),
								),
								array(
									'name'=>'regex',
									'options'=>array(
										'pattern'=>'"^[A-я0-9_-]+$"i',
									),
								),
							),
						),
					)));
					
					return $form;
				},
				'blockEditForm'=>function($sm){
					$form=$sm->get('blockAddForm');
					$form->setLabel('Редактирование блока');
					return $form;
				},
				'pageAddForm'=>function($sm){
					$form=new \Zend\Form\Form('page-add-form');
					$form->setLabel('Добавление страницы');
					$form->add(array('name'=>'name','type'=>'text','options'=>array('label'=>'Название страницы')));
					$form->add(array('name'=>'route','type'=>'text','options'=>array('label'=>'Путь к странице /')));
					$form->add(array('name'=>'id','type'=>'hidden'));
					
					$blocks=$sm->get('blockMapper')->get();
					if($blocks&&is_array($blocks)){
						foreach($blocks as $block){
							$form->add(array('name'=>'block_'.$block->id,'type'=>'textarea','options'=>array(
								'label'=>'Блок "'.$block->name.'"',
							)));
						}
					}
					
					$form->add(array('name'=>'submit','type'=>'submit','attributes'=>array('value'=>'Сохранить')));
					
					$filter=new \Zend\InputFilter\Factory;
					$form->setInputFilter($filter->createInputFilter(array(
						'name'=>array(
							'name'=>'name',
							'required'=>TRUE,
							'validators'=>array(
								array(
									'name'=>'not_empty',
								),
								array(
									'name'=>'string_length',
									'options'=>array(
										'min'=>2,
									),
								),
								array(
									'name'=>'regex',
									'options'=>array(
										'pattern'=>'"^[A-я0-9_-]+$"i',
									),
								),
							),
						),
						'route'=>array(
							'name'=>'route',
							'required'=>FALSE,
							'validators'=>array(
								array(
									'name'=>'regex',
									'options'=>array(
										'pattern'=>'"^[A-z0-9_-]*$"i',
									),
								),
							),
						),
					)));
					
					return $form;
				},
				'pageEditForm'=>function($sm){
					$form=$sm->get('pageAddForm');
					$form->setLabel('Редактирование страницы');
					return $form;
				},
			),
		);
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
