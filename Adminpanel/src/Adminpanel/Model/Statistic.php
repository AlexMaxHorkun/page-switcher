<?php
namespace Adminpanel\Model;

use AMH\Model\Model as AMHModel;

class Statistic extends AMHModel{
	protected function preConstruct(){
		$this->addField(array('name'=>'pageId','set'=>AMHModel::FIELD_SET_ONLYINCONSTRUCTOR,'type'=>'int'));
		$this->addField(array('name'=>'ip','set'=>AMHModel::FIELD_SET_ONLYINCONSTRUCTOR,'type'=>'string'));
		$this->addField(array('name'=>'date','set'=>AMHModel::FIELD_SET_ONLYINCONSTRUCTOR,'type'=>'string'));
		$this->addField(array('name'=>'time','set'=>AMHModel::FIELD_SET_ONLYINCONSTRUCTOR,'type'=>'string'));
	}
}
?>