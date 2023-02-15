<?php

/**
 * @Author: BiB
 * @Date:   2023-02-14 17:55:50
 * @Last Modified by:   BiB
 * @Last Modified time: 2023-02-15 08:10:13
 */
/* #######################################  */
/*      I don`t always test my code         */
/*  But when I do, I do it in production    */
/* #######################################  */
namespace iAmBiB\ApiVersionFallback\Extension;
use Laravel\Lumen\Application as LumenApplication;


final class Application extends LumenApplication{
	public function __construct($path){
		parent::__construct($path);
	}

	public function handleFoundRoute($routeInfo){
		return parent::handleFoundRoute($routeInfo);
	}
	public function createDispatcher(){
		return parent::createDispatcher();
	}
}