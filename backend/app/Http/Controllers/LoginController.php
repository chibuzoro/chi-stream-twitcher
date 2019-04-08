<?php
/**
 * Created by IntelliJ IDEA.
 * User: chibuzorogbu
 * Date: 2019-04-08
 * Time: 09:06
 */

namespace App\Http\Controllers;


class LoginController extends Controller
{

    final public function getAuthCodeUrl()
    {
        return ['authUrl' => $this->twitchRepository->getAuthCodeUrl()];//  response()->json();
    }

}
