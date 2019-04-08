<?php
/**
 * Created by IntelliJ IDEA.
 * User: chibuzorogbu
 * Date: 2019-04-08
 * Time: 12:22
 */

trait TestTrait
{
    private function buildUrlString(string $path) : string{
        return  sprintf(
            env('URL_PATTERN'),
            url('/', [], env('APP_ENV') === 'production'),
            $path
        );
    }
}
