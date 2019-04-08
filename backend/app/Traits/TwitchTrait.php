<?php
/**
 * Created by IntelliJ IDEA.
 * User: chibuzorogbu
 * Date: 2019-04-08
 * Time: 18:03
 */

namespace App\Traits;


use Illuminate\Http\Request;


trait TwitchTrait
{

    public function extractTokenFromHeader(Request $request)
    {
        $tokenString = $request->header('Authorization');
        $token = explode(' ', $tokenString);
        return array_pop($token);
    }

}
