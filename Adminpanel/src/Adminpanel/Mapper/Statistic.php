<?php
namespace Adminpanel\Mapper;

class tatistic extends \AMH\Mapper\Mapper{
	public function __construct(\PDO $pdo){
		$this->reqTables=array(
			'create table  stat(
				page_id int not null,
				ip char(45) not null,
				date date not null,
				time time not null
			)engine=myisam default charset=utf8'.
		);
		parent::__construct($pdo);
	}
	
	protected function dbSelect($filter){
		$query='select * from stat';
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
				$sth->bindParam($ind,$val);
			}
			return $sth->execute();
		}
		return $this->pdo->query($query);
	}
	
	protected function dbInsert($item){
		$sth=$this->pdo->prepare('insert into stat values (?,?,?,?)');
		$sth->bindParam(1,$item->page_id);
		$sth->bindParam(2,$item->ip);
		$sth->bindParam(3,$item->date);
		$sth->bindParam(4,$item->time);
		return $sth->execute();
	}
	
	protected function dbSave($item){
		return null;
	}
	
	protected function dbDelete($item){
		$sth=$this->pdo->query('delete from stat where page_id=? and ip=? and date=? and time=?');
	}
}
?>
