<?php

	#####################################################################################
	# Projet : Short example for PFWA demonstration										#
	#																					#
	# Author : Kmaschta <kmaschta@gmail.com>											#
	# Date : 2014-01-07																	#
	#																					#
	# Languages :																		#
	#   PHP 5.3, HTML5, CSS3, MySQL														#
	#																					#
	# Librairies :																		#
	# - Application : PFWA 0.1												#
	# - Template engine : Twig 1.13.20													#
	# - ORM : php-activerecord 1.0														#
	#																					#
	#####################################################################################

session_start();

/** Autoloaders call */
require_once("lib/Twig/Autoloader.php");
require_once("lib/php-activerecord/ActiveRecord.php");
require_once("lib/PFWA/Tools/PFWA_Loader.php");

/** Application run */
// STEP 1
// Set the path where this file is located from root URL
// Warning ! No slash at the end of string
$path = "/[...]/example";
$app = new PFWA_Application("MyApp", $path);
$app->run();

// STEP 2
// Configure the configuration file : /config/MyApp.ini