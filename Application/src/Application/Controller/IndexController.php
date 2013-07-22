<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractController;
use Zend\View\Model\ViewModel as View;

class IndexController extends AbstractController
{
	protected $page=null;	
	
	public function getPage(){
		if($this->page==NULL){
			$route=$this->getEvent()->getRouteMatch()->getParam('page');
			if($route===NULL){
				$route='';
			}
			$page=$this->getServiceLocator()->get('pageMapper')->get(array('route'=>$route));
			if($page===null||(is_array($page)&&!count($page))){
				$this->page=FALSE;
			}
			else{
				$this->page=$page;
			}
		}
		
		$this->getEventManager()->trigger('got_page',$this,array('page'=>$this->page));
		
		return $this->page;
	}

	public function onDispatch(\Zend\Mvc\MvcEvent $e){
		$this->getPage();
		//$config=$this->getEvent()->getApplication()->getConfig();
		
		$view=new View();
		if(!$this->page){
			$view->setTemplate('error/404');
		}
		else{
			$view->setTemplate('layout/main');
			$blocks=$this->getServiceLocator()->get('blockMapper')->get();
			if($blocks){
				if(!is_array($blocks)){
					$blocks=array($blocks);
				}
				foreach($blocks as $block){
					if(file_exists(__DIR__.'/../../../view/pages/page_'.$this->page->id.'/block_'.$block->id.'.phtml')){
						$blockView=new View();
						$blockView->setTemplate('pages/page_'.$this->page->id.'/block_'.$block->id);
						$view->addChild($blockView,'block_'.$block->id);
					}
				}
				unset($block);
			}
		}
		$view->setTerminal(TRUE);
		$this->getEvent()->setResult($view);
		return $view;
	}
}
