<?php
class LanguageModel
{
	private $written_language_db;
	
	public function __construct()
	{
		$this->written_language_db = new WrittenLanguageDb();
	}
	
	public function getWrittenLanguageIdByTag($tag)
	{
		$where = array(
			'tag' => $tag
		);
		$column = array(
			'id'
		);
		$r = $this->written_language_db->selectRow($where,$column);
		return isset($r['id']) ? $r['id'] : NULL;
	}
}
?>
