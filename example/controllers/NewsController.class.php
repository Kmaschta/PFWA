<?php

class NewsController extends PFWA_BackController {

	public function actionIndex(array $vars) {
		$news = News::all();
		return array_merge($vars, array(
			"news" => $news
		));
	}

	public function actionAdd(array $vars) {
		$success = "null";
		$r = $this->app->request;
		$infos = array(
			"title" => ($r->title) ? $r->title->content : null,
			"author" => ($r->author) ? $r->author->content : null,
			"content" => ($r->content) ? $r->content->content : null,
			"date" => ($r->date) ? $r->date->content : null
		);
		if($infos["title"] != null &&
			$infos["author"] != null &&
			$infos["content"] != null &&
			$infos["date"] != null)
		{
			$news = News::create($infos);
			$success = $news->save();
			$success = ($success) ? "true" : "false";
		}
		return array_merge($vars, array("success" => $success));
	}

	public function actionShow(array $vars) {
		$news = News::find_by_id($vars['id']);
		return array_merge($vars, array("news" => $news));
	}

	public function actionDel(array $vars) {
		$confirm = $vars['confirm'];
		$success = "null";
		if($confirm !== null && $confirm !== '/') {
			$news = News::find_by_id($vars['id']);
			if(isset($news)) {
				$news->delete();
				$success = "true";
			}
			else $success = "false";
		}
		return array_merge($vars, array("success" => $success));
	}
}