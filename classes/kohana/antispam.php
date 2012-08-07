<?php 
defined('SYSPATH') OR die('No direct access allowed.');
class Kohana_Antispam {

	protected static $botobor_class;
	protected static $config;
	
	// В стиле Kohana 3.2
	public static function factory($html = NULL){
		return new Antispam($html);
	}
	
	
	public function __construct($html = NULL){
		// Подгружаем класс Botobor 0.3.0		
        if ( ! self::$botobor_class){
            require Kohana::find_file('vendor', 'botobor', 'php');
        }
        self::$botobor_class = new Botobor_Form($html);
		self::$config = Kohana::$config->load('antispam');
		foreach (self::$config['checks'] as $type => $val){
			self::$botobor_class->setCheck($type, $val);
		}
		self::$botobor_class->setDelay(self::$config['delay']);
		self::$botobor_class->setLifetime(self::$config['lifetime']);
		self::$botobor_class->addHoneypot(self::$config['honeypots']);
		Botobor::setSecret(self::$config['secret_key']);
	}
	
	// Получаем измененный код формы
	public static function getForm(){
		return self::$botobor_class->getCode();
	}
	
	// Проверка на "человечность"
	public static function isHuman()
	{
		return Botobor_Keeper::isHuman();
	}
	
}