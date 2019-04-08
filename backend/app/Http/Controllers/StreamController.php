<?php
/**
 * Created by IntelliJ IDEA.
 * User: chibuzorogbu
 * Date: 2019-04-08
 * Time: 09:06
 */

namespace App\Http\Controllers;


use App\Traits\TwitchTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StreamController extends Controller
{
    use TwitchTrait;

    final public function captureStream( string $channel)
    {

        try {
            $streamData = $this->twitchRepository->captureStream($channel);

        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => 'Invalid Channel'], 400);
        }

        return response()->json($streamData);
    }

    final public function registerWebHookSubscriptions(Request $request, $userId){

        $token = $this->extractTokenFromHeader($request);
        $this->twitchRepository->registerWebhook($userId, $token);

        return response()->json('OK');

    }

    final public function pubsub(Request $request)
    {
        if (true === $request->isMethod('post')){
            return  $this->twitchRepository->publishStreamChanged($request);
        }

        return $this->twitchRepository->validateSubscription($request);

    }

}
