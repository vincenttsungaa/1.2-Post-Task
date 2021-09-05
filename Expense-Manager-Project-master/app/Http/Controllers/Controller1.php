<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use app\User;

class Controller1 extends Controller
{
	public function test(Request $request){

		$Validation = $this->validate($request, [
			"Name" = "Required",
			"Age" = "Required",
      "Email" = "Required"
		]};

		$this->register($request);
	}

	public function register($request){

		$checkCount = User::where('email', '=', $request['email'])
			             ->get()
			             ->count();
		if($checkCount > 0)
	{
		return response()->json(['Email already exists'], 300);
	}

	$input - $request;
	$user = User::create($input);

	return response()->json(['Success'], 201);
	}
}
}
