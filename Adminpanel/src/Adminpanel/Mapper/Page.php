<?php
namespace Adminpanel\Mapper;

class Page extends \AMH\Mapper\Mapper{
	public function __construct(\PDO $pdo,array $options=array()){
		$this->reqTables=array(
			'create table pages(
				id int not null auto_increment primary key,
				name text not null,
				route text not null,
				unique(name(50),route(50))
			)engine=myisam default charset=utf8',
		);
		parent::__construct($pdo,$options);
	}
	
	protected function dbSelect($filter){
		$query='select * from pages';
		if(count($filter)){
			$where='';
			foreach($filter as $col=>$val){
				if(in_array($col,$this->options['fieldsMap'])){
					$col=array_search($col,$this->options['fieldsMap']);
				}
				
				if(!mb_strlen($where)){
					$where=' where '.$col.'=?';
				}
				else{
					$where.=' and '.$col.'=?';
				}
			}
			$sth=$this->pdo->prepare($query.$where);
			$vals=array_values($filter);
			foreach($vals as $ind=>$val){
				$sth->bindValue($ind+1,$val);
			}
			$sth->execute();
			return $sth;
		}
		return $this->pdo->query($query);
	}
	
	protected function dbInsert($item){
		$sth=$this->pdo->prepare('insert into pages values (null,?,?)');
		$sth->bindValue(1,$item->name);
		$sth->bindValue(2,$item->route);
		return $sth->execute();
	}
	
	protected function dbSave($item){
		$sth=$this->pdo->prepare('update pages set name=?, route=? where id=?');
		$sth->bindValue(1,$item->name);
		$sth->bindValue(2,$item->route);
		$sth->bindValue(3,$item->id);
		return $sth->execute();
	}
	
	protected function dbDelete($item){
		return $this->pdo->query('delete from pages where id='.$item->id);
	}
	
	protected function fetch($row){
		return new \Adminpanel\Model\Page((array)$row);
	}
}
?>
