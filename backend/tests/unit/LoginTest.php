<?php
/**
 * Created by IntelliJ IDEA.
 * User: chibuzorogbu
 * Date: 2019-04-08
 * Time: 09:02
 */

class LoginTest extends TestCase
{

  use TestTrait;


    /**
     * @link https://dev.twitch.tv/docs/authentication/getting-tokens-oauth/#oauth-authorization-code-flow
     * test authorization url is returned as string.
     */
    public function testGetCodeUrl(){

        $twitchRepo = \Illuminate\Support\Facades\App::make(\App\Repository\TwitchRepository::class);


        $scope = 'user:edit+viewing_activity_read+openid+channel:read:subscriptions+bits:read+channel_subscriptions+channel_read';

        $loginController = new \App\Http\Controllers\LoginController($twitchRepo);

        $authUrl = sprintf('https://id.twitch.tv/oauth2/authorize?client_id=%s&redirect_uri=%s&response_type=code&scope=%s',
            env('TWITCH_API_CLIENT_ID'), $this->buildUrlString( env('TWITCH_API_REGISTERED_REDIRECT_URL')),
            $scope);

        $this->assertEquals(
            $authUrl, $loginController->getAuthCodeUrl()['authUrl']
        );

    }


    public function testGetAccessToken(){
        $mock = Mockery::spy(\NewTwitchApi\NewTwitchApi::class);


        $expectedTokenApiResponse = "{
  \"access_token\": \"0123456789abcdefghijABCDEFGHIJ\",
  \"refresh_token\": \"eyJfaWQmNzMtNGCJ9%6VFV5LNrZFUj8oU231/3Aj\",
  \"expires_in\": 3600,
  \"scope\": [\"viewing_activity_read\"],
  \"token_type\": \"bearer\"
}";
        $tokenResponse = new \GuzzleHttp\Psr7\Response(200, [], $expectedTokenApiResponse); //response()->json([json_decode($tokenResponse)]);

        $authCode = 'xwsoponpmuadw6aclommctso6t36ei';
        $redirectUri = $this->buildUrlString(env('TWITCH_API_REGISTERED_REDIRECT_URL'));
        $mock->shouldReceive('getOauthApi->getUserAccessToken')
             ->withArgs([$authCode, $redirectUri])
             ->andReturn($tokenResponse);
        $this->app->instance(\NewTwitchApi\NewTwitchApi::class, $mock);

        $loginController = new \App\Http\Controllers\LoginController($this->app->make(\App\Repository\TwitchRepository::class));
        $token = $loginController->getAccessToken($this->app->request->merge(['code' => $authCode]));

        $this->assertEquals($expectedTokenApiResponse, $token->getBody()->getContents() );

        // clear the mock
        $this->tearDown();


    }



}
