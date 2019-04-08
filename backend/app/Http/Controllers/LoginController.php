<?php
/**
 * Created by IntelliJ IDEA.
 * User: chibuzorogbu
 * Date: 2019-04-08
 * Time: 09:06
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{

    final public function getAuthCodeUrl()
    {
        return ['authUrl' => $this->twitchRepository->getAuthCodeUrl()];//  response()->json();
    }

    final public function getAccessToken(Request $request)
    {
        $authCode =  $request->get('code');
        try{
            return $this->twitchRepository->getAccessToken($authCode);
        }catch (GuzzleException $e){
            Log::alert('unable to retrieve access token');
            return response()->json([
                'message' => 'supplied code maybe invalid'
            ], 500);

        }

    }

}
