<?php

namespace App\Http\Controllers;

use App\Repository\TwitchRepository;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**@var \App\Repository\TwitchRepository **/
    protected $twitchRepository;


    public function __construct(TwitchRepository $repository)
    {
        $this->twitchRepository = $repository;
    }

}
