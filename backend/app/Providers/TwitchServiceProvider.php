<?php
/**
 * Created by IntelliJ IDEA.
 * User: chibuzorogbu
 * Date: 2019-04-08
 * Time: 09:10
 */

namespace App\Providers;


use App\Repository\TwitchRepository;
use Illuminate\Support\ServiceProvider;
use NewTwitchApi\HelixGuzzleClient;
use NewTwitchApi\NewTwitchApi;
use Pusher\Pusher;


class TwitchServiceProvider extends ServiceProvider
{
    /**
     * Register any twitcher services.
     *
     * @return void
     */
    public function register(){

        // Register the Guzzle Client needed
        $this->app->singleton(HelixGuzzleClient::class, static function($app){
            return new HelixGuzzleClient(env('TWITCH_API_CLIENT_ID'));

        });


        // register the twitch api helper class
        $this->app->singleton(NewTwitchApi::class, static function($app){
            return new NewTwitchApi(
                $app->make( HelixGuzzleClient::class),
                env('TWITCH_API_CLIENT_ID'),
                env('TWITCH_API_CLIENT_SECRET')
            );

        });

        // register the twitch api helper class
        $this->app->singleton(Pusher::class, static function($app){
            return new Pusher(
                env('PUSHER_KEY'),
                env('PUSHER_SECRET'),
                env('PUSHER_APP_ID'),
                [
                    'cluster' => env('PUSHER_CLUSTER'),
                    'useTLS' => true
                ]
            );

        });

        // register the twitch repository
        $this->app->singleton(TwitchRepository::class, function($app){
            $redirectUri =  $this->buildUrlString(env('TWITCH_API_REGISTERED_REDIRECT_URL'));
            $callBackStreamUri = $this->buildUrlString('api/pubsub');
            return new TwitchRepository(
                $app->make( NewTwitchApi::class),
                $app->make( Pusher::class),
                $redirectUri,
                $callBackStreamUri

            );

        });


    }

    private function buildUrlString(string $path) : string{
        return  sprintf(
            env('URL_PATTERN'),
            url('/', [], env('APP_ENV') === 'production'),
            $path
        );
    }

}
