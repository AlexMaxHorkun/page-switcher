<?php
namespace AMH\Model;

class Model{
	const FIELD_SET_ONLYONCE=0001;
	const FIELD_SET_ONLYINCONSTRUCTOR=0002;
	const FIELD_SET_ANYWHERE=0003;
	
	protected $fields=array();
	
	protected function preConstruct(){}
	
	public function __construct(array $fields=array()){
		$this->preConstruct();
		foreach($fields as $field=>$val){
			if(isset($this->fields[$field])){
				$this->fields[$field]['value']=$val;
				$this->fields[$field]['wasSet']=TRUE;
			}
		}
	}
	
	public function getFieldNames(){
		return array_keys($this->fields);
	}
	
	protected function addField(array $options){
		if(!isset($options['name'])){
			throw new \ErrorException('Wrong argument given to '.get_class($this).'::'.__FUNCTION__.' - options must contain proper name');
			return;
		}
		
		if(isset($this->fields[$options['name']])){
			throw new \ErrorException('Field "'.$options['name'].'" in '.get_class($this).' already exists');
			return;
		}
		
		$name=$options['name'];
		$field=array('set'=>self::FIELD_SET_ANYWHERE,'value'=>null,'type'=>TRUE,'getCallback'=>null,'setCallback'=>null,'wasSet'=>FALSE);
		
		if(isset($options['set'])){
			switch($options['set']){
			case self::FIELD_SET_ONLYONCE:
			case self::FIELD_SET_ONLYINCONSTRUCTOR:
			case self::FIELD_SET_ANYWHERE:
				$field['set']=$options['set'];
			}
		}
		
		if(isset($options['type'])&&gettype($options['type'])=='string'){
			$field['type']=$options['type'];
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
			if($this->fields[$name]['getCallback']){
				return $this->fields[$name]['getCallback'][0]($this->fields[$name]['value']);
			}
			return $this->fields[$name]['value'];
		}
		else{
			throw new \Exception('Model '.get_class($this).' doesn\'t have field "'.$name.'"');
			return null;
		}
	}
	
	public function __set($name,$val){
		if(!isset($this->fields[$name])){
			throw new \Exception('Model '.get_class($this).' doesn\'t have field "'.$name.'"');
			return null;
		}
		
		if($this->fields[$name]['set']==self::FIELD_SET_ONLYINCONSTRUCTOR){
			throw new \Exception('Cannot set '.get_class($this).'.'.$name.', premission denied');
			return null;
		}
		
		if($this->fields[$name]['set']==self::FIELD_SET_ONLYONCE&&$this->fields[$name]['wasSet']){
			throw new \Exception('Cannot set '.get_class($this).'.'.$name.', premission denied');
			return null;
		}
		
		if($this->fields[$name]['type']!==TRUE){
			if(is_object($val))
				if(!($val instanceof $this->fields[$name]['type'])){
					throw new \Exception('Cannot set '.get_class($this).'.'.$name.' to '.$val.', value must be an instance of '.$this->fields[$name]['type']);
					return null;
				}
			else
				if(gettype($val)!=$this->fields[$name]['type']){
					throw new \Exception('Cannot set '.get_class($this).'.'.$name.' to '.$val.', value must be an instance of '.$this->fields[$name]['type']);
					return null;
				}
		}
		
		if($this->fields[$name]['setCallback']){
			$val=$this->fields[$name]['setCallback'][0]($val);
		}
		$this->fields[$name]['value']=$val;
		if(!$this->fields[$name]['wasSet'])
			$this->fields[$name]['wasSet']=TRUE;
	}
	
	public function __isset($name){
		return isset($this->fields[$name]);
	}
	
	public function __unset($name){
		if(!isset($this->fields[$name])){
			throw new \Exception('Model '.get_class($this).' doesn\'t have field "'.$name.'"');
			return null;
		}
		
		if(($this->fields[$name]['set']==self::FIELD_SET_ONLYONCE||$this->fields[$name]['set']==self::FIELD_SET_ONLYINCONSTRUCTOR)&&$this->fields[$name]['value']!==null){
			throw new \Exception('Cannot UNset '.get_class($this).'.'.$name.', premission denied');
			return null;
		}
		
		$this->fields[$name]['value']==null;
	}
	
	public function __toString(){
		return  'Instance of '.get_class($this);
	}
	
	public function toArray(){
		$arr=array();
		foreach($this->fields as $name=>$field){
			$arr[$name]=$field['value'];
		}
		return $arr;
	}
}
?>
