<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function getFile($file){
        $pathFile = __DIR__ . '/mocks/files/'.$file;

        if (!file_exists($pathFile)) {
            new Exception('File not found '.$pathFile);
        }

        return json_decode(file_get_contents($pathFile), true);
    }
}
