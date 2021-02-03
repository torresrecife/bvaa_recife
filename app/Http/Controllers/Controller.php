<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    /** @var array $sendToView */
    protected $sendToView = [];

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function resetDataToView()
    {
        $this->sendToView['data'] = [];
    }

    protected function sendDataToView($key, $value)
    {
        $pieces = explode('.', $key);

        if (count($pieces) == 2) {
            $this->sendToView['data'][$pieces[0]][$pieces[1]] = $value;

            return;
        }

        if (count($pieces) == 3) {
            $this->sendToView['data'][$pieces[0]][$pieces[1]][$pieces[2]] = $value;

            return;
        }

        $this->sendToView['data'][$key] = $value;
    }
}
