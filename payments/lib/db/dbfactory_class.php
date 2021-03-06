<?php

/**
* @class IDBFactory
* @brief 数据库工厂
*/
class IDBFactory
{
	//数据库对象
	public static $instance   = NULL;

	//默认的数据库连接方式
	private static $defaultDB = 'mysqli';

	/**
	 * @brief 创建对象
	 * @return object 数据库对象
	 */
	public static function getDB()
	{
		//单例模式
		if(self::$instance != NULL && is_object(self::$instance))
		{
			return self::$instance;
		}

		//获取数据库配置信息
		if(!isset(IWeb::$app->config['DB']) || IWeb::$app->config['DB'] == null)
		{
			throw new IHttpException('can not find DB info in config.php',1000);
			exit;
		}
		$dbinfo = IWeb::$app->config['DB'];

		//数据库类型
		$dbType = isset($dbinfo['type']) ? $dbinfo['type'] : self::$defaultDB;

		switch($dbType)
		{
			case "mysqli":
			{
				return self::$instance = new IMysqli();
			}
			break;

			case "mysql":
			{
				return self::$instance = new IMysql();
			}
			break;
		}
	}
    private function __construct(){}
    private function __clone(){}
}
