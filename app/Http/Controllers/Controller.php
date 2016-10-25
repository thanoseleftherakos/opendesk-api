<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
	
	public function createResponse($data,$message,$code){
    	return response()->json(['data' => $data, 'message' => $message],$code);
    }

    public function buildFailedValidationResponse(Request $request,array $errors){
    	return $this->createResponse($request, $errors,422);
    }

}
