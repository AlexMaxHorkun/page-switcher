<?php
namespace Adminpanel\Mapper;

class Block extends \AMH\Mapper\Mapper{
	public function __construct(\PDO $pdo){
		$this->reqTables=array(
			'create table blocks(
				id int not null auto_increment primary key,
				name text not null,
				unique(name(50)
			)engine=myisam default charset=utf8'.
		);
		parent::__construct($pdo);
	}
	
	protected dbSelect($filter){
		return $this->pdo->query
	}
}
?>
