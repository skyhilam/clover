<?php 

namespace Clover\Exception;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class JsonException 
{
	public static function jsonResponse($errors, $status = 422) 
	{
		throw new HttpResponseException(new JsonResponse([
			'errors' => $errors
		], $status));
	}
}
