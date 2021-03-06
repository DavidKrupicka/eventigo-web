<?php declare(strict_types=1);

namespace App\Modules\Front\Components\Sign;


interface SignInFactory
{
	public function create(): SignIn;
}
