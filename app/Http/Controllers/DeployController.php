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
        if (json_decode($request->input('payload'))->pusher == 'txnnxr') {
            $this->gitPull();
            $this->composerInstall();
            $this->npmInstall();
            $this->migrate();
        }
    }

    private function gitPull() {
        $process = new Process('git pull');
        $this->info("Running 'git pull'");

        $process->run();
    }

    private function composerInstall()
    {
        $process = new Process('composer install --no-dev');
        $process->run();
    }

    private function npmInstall()
    {
        $process = new Process('nvm use 17');
        $process->run();
        $process = new Process('npm install');
        $process->run();
        $process = new Process('npm run prod');
        $process->run();
    }

    private function migrate()
    {
        $process = new Process('php artisan migrate');
        $process->run();
    }
}
