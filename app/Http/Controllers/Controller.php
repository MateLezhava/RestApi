<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

 /** 
  * @OA\info(
  *     title="My Doc API",
  *     version="1.0.0"
  *),
  * @OA\Pathitem(
  *     path="/api/"
  * )
  */

class Controller extends BaseController
{
  

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
