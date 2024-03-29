<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

//use Symfony\Component\Process\Process;
class DeployController extends Controller
{
    public function deploy(Request $request)
    {
            Log::debug($request);
            $payload = json_decode($request->input('payload'));
            if ($payload->pusher->name == 'txnnxr' && str_contains($payload->ref,  env('ENV_BRANCH'))) {
                $this->gitPull();
                system('composer install --no-dev');
                system('composer dumpautoload');
                system('nvm use 17');
                system('npm install');
                system('npm run prod');
                Artisan::call('migrate');
        }
    }

    private function gitPull() {
        $process = new Process(['git', 'pull']);
        $process->run();
        if (!$process->isSuccessful()) Log::error('Failed with '. $process->getExitCode());
    }

    private function composerInstall()
    {
        $process = new Process(['composer',  'install', '--no-dev']);
        $process->run();
        if (!$process->isSuccessful()) Log::error('composer install Failed with '. $process->getExitCode());

    }

    private function npmInstall()
    {
        $process = new Process(['nvm', 'use 17']);
        $process->run();
        if (!$process->isSuccessful()) Log::error('nvm use 17 Failed with '. $process->getExitCode());
        $process = new Process(['npm', 'install']);
        $process->run();
        if (!$process->isSuccessful()) Log::error('npm install Failed with '. $process->getExitCode());
        $process = new Process(['npm', 'run', 'prod']);
        $process->run();
        if (!$process->isSuccessful()) Log::error('Npm run prod Failed with '. $process->getExitCode());
    }

    private function migrate()
    {

    }
}
