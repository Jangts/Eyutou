<?php
/*
 * NIML Compiler
**/
trait NIML_plugins_math {
	public static function _divide($dividend, $divisor){
		return $dividend/$divisor;
	}

	public static function __divide($dividend, $divisor){
		echo $dividend/$divisor;
	}

	public static function _mod($dividend, $divisor){
		return $dividend%$divisor;
	}

	public static function __mod($dividend, $divisor){
		echo $dividend%$divisor;
	}
}
