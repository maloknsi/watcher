<?php

namespace backend\components;

/**
 * @property mixed error
 * @property mixed data
 * @property mixed notify
 */
class AjaxResult
{
	const BAD_REQUEST_PARAMS = 'Не правильно переданы параметры запроса. Обновите страницу и попробуйте еще раз';

	public $data = null;
	public $notify = null;
	public $error = null;
}