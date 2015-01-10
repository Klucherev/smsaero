<?php 

namespace Klucherev\Smsaero\Facades;
 
use Illuminate\Support\Facades\Facade;
 
class Smsaero extends Facade 
{
	protected static function getFacadeAccessor() 
	{ 
		return 'smsaero'; 
	}
}