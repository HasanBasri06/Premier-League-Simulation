<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as QA;

#[QA\Info(title: 'Insider değerlendirme projesi', version: 1)]
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
