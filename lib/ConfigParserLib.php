<?php
class ConfigParserLib
{
	private function __construct()
	{
		
	}

	public static function getConfigFileDir()
	{
		return dirname(__FILE__).'/../conf/';
	}
	
	public static function get($file_name,$key)
	{
		require self::getConfigFileDir().$file_name.'.conf';
		$tmp = str_replace(array("['","']['","']"),array("->"),$key);
		$a = explode('->',$tmp);
		$main_string = $a[0];
		$result = $$main_string;
		unset($a[0]);
		foreach($a as $value)
		{
			$result = $result[$value];
		}
		return $result;
	}
}
?>
