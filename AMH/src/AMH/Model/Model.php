<?php
namespace AMH\Model;

class Model{
	const FIELD_SET_ONLYONCE=0001;
	const FIELD_SET_ONLYINCONSTRUCTOR=0002;
	const FIELD_SET_ANYWHERE=0003;
	

	private $fields=array();
	
	public function __construct(array $fields){
		foreach($fields as $field=>$val){
			if(isset($this->fields[$field])){
				$this->fields[$fieled]=$val;
			}
		}
	}
	
	private function addField(array $options){
		if(!isset($options['name'])){
			throw new \ErrorException('Wrong argument given to '.__CLASS__.'::'.__FUNCTION__.' - options must contain proper name');
			return;
		}
		
		if(isset($this->fields[$options['name']])){
			throw new \ErrorException('Field "'.$options['name'].'" in '.__CLASS__.' already exists');
			return;
		}
		
		$name=$options['name'];
		$field=(object)array('set'=>self::FIELD_SET_ANYWHERE,'value'=>null,'type'=>TRUE,'getCallback'=>null,'setCallback'=>null);
		
		if(isset($options['set'])){
			switch($options['set']){
			case self::FIELD_SET_ONLYONCE:
			case self::FIELD_SET_ONLYINCONSTRUCTOR:
			case self::FIELD_SET_ANYWHERE:
				$field->set=$options['set'];
			}
		}
		
		if(isset($options['type'])&&gettype($options['type'])=='string'){
			$field->type=$options['type'];
		}
		
		if(isset($options['getCallback'])){
			$field->getCallback[0]=$options['getCallback'];
		}
		if(isset($options['setCallback'])){
			$field->setCallback[0]=$options['setCallback'];
		}
		
		$this->fields[$name]=$field;
	}
	
	public function __get($name){
		if(isset($this->fields[$name])){
			if($this->fields[$name]->getCallback){
				return $this->fields[$name]->getCallback[0]($this->fields[$name]->value);
			}
			return $this->fields[$name]->value;
		}
		else{
			throw new \Exception('Model '.__CLASS__.' doesn\'t have field "'.$name.'"');
			return null;
		}
	}
	
	public function __set($name,$val){
		if(!isset($this->fields[$name])){
			throw new \Exception('Model '.__CLASS__.' doesn\'t have field "'.$name.'"');
			return null;
		}
		
		if(($this->fields[$name]->set==self::FIELD_SET_ONLYONCE||$this->fields[$name]->set==self::FIELD_SET_ONLYINCONSTRUCTOR)&&$this->fields[$name]->value!==null){
			throw new \Exception('Cannot set '.__CLASS__.'.'.$name.', premission denied');
			return null;
		}
		
		if($this->fields[$name]->type!==TRUE){
			if(!($val instanceof $this->fields[$name]->type)){
				throw new \Exception('Cannot set '.__CLASS__.'.'.$name.' to '.$val.', value must be an instance of '.$this->fields[$name]->type);
				return null;
			}
		}
		
		if($this->fields[$name]->setCallback){
			$val=$this->fields[$name]->setCallback[0]($val);
		}
		$this->fields[$name]->value=$val;
	}
	
	public function __isset($name){
		return isset($this->fields[$name]);
	}
	
	public function __unset($name){
		if(!isset($this->fields[$name])){
			throw new \Exception('Model '.__CLASS__.' doesn\'t have field "'.$name.'"');
			return null;
		}
		
		if(($this->fields[$name]->set==self::FIELD_SET_ONLYONCE||$this->fields[$name]->set==self::FIELD_SET_ONLYINCONSTRUCTOR)&&$this->fields[$name]->value!==null){
			throw new \Exception('Cannot UNset '.__CLASS__.'.'.$name.', premission denied');
			return null;
		}
		
		$this->fields[$name]->value==null;
	}
	
	public function __toString(){
		return  'Instance of '.__CLASS__;
	}
}
?>
