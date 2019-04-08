<?php
/**
 * Created by IntelliJ IDEA.
 * User: chibuzorogbu
 * Date: 2019-04-08
 * Time: 09:10
 */

namespace App\Providers;


use App\Repository\TwitchRepository;
use NewTwitchApi\NewTwitchApi;


class TwitchServiceProvider
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

        // register the twitch repository
        $this->app->singleton(TwitchRepository::class, function($app){
            return new TwitchRepository(
                $app->make( NewTwitchApi::class)
            );

        });
    }

}
