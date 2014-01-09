<?php

class IndexController extends PFWA_BackController {
	public function actionIndex(array $vars) {

		$welcome = "Hello word!";

		return array_merge($vars, array(
			"welcome" => $welcome
		));
	}
}