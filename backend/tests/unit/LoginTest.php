<?php
/**
 * Created by IntelliJ IDEA.
 * User: chibuzorogbu
 * Date: 2019-04-08
 * Time: 09:02
 */

class LoginTest extends TestCase
{

    private function buildUrlString(string $path) : string{
        return  sprintf(
            env('URL_PATTERN'),
            url('/', [], env('APP_ENV') === 'production'),
            $path
        );
    }


    /**
     * @link https://dev.twitch.tv/docs/authentication/getting-tokens-oauth/#oauth-authorization-code-flow
     * test authorization url is returned as string.
     */
    public function testGetCodeUrl(){

        $twitchApi = Mockery::spy(\NewTwitchApi\NewTwitchApi::class);

        $twitchRepo = new \App\Repository\TwitchRepository($twitchApi);

        $scope = 'user:edit+viewing_activity_read+openid+channel:read:subscriptions+bits:read+channel_subscriptions+channel_read';

        $loginController = new \App\Http\Controllers\LoginController($twitchRepo);

        $authUrl = sprintf('https://id.twitch.tv/oauth2/authorize?client_id=%s&redirect_uri=%s&response_type=code&scope=%s',
            env('TWITCH_API_CLIENT_ID'),
           $this->buildUrlString( env('TWITCH_API_REGISTERED_REDIRECT_URL')),
            $scope);

        $this->assertEquals(
            $authUrl, $loginController->getAuthCodeUrl()
        );

    }

}
