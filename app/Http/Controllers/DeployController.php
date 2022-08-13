<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

//use Symfony\Component\Process\Process;

class DeployController extends Controller
{
    public function deploy(Request $request)
    {
	    Log::debug($request);
	    $data = $request->all();
       return (json_decode($request->input('payload'))->pusher);

//        Process::

    }
}
