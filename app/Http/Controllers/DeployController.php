<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

//use Symfony\Component\Process\Process;

class DeployController extends Controller
{
    public function deploy($webhook)
    {
        Log::debug($webhook);
        dd($webhook);

//        Process::

    }
}
