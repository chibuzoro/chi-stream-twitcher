<?php
/**
 * Created by IntelliJ IDEA.
 * User: chibuzorogbu
 * Date: 2019-04-08
 * Time: 12:18
 */

class LoginIntegrationTest extends TestCase
{

    use TestTrait;
    /**
     * test authcode url is returned
     */
    public function testGetAuthCodeUrl(){

        $this->get('/api/auth');


        $scope = 'user:edit+viewing_activity_read+openid+channel:read:subscriptions+bits:read+channel_subscriptions+channel_read';


        $authUrl = sprintf('https://id.twitch.tv/oauth2/authorize?client_id=%s&redirect_uri=%s&response_type=code&scope=%s',
            env('TWITCH_API_CLIENT_ID'), $this->buildUrlString( env('TWITCH_API_REGISTERED_REDIRECT_URL')),
            $scope);

        $this->assertResponseStatus(200);
        $this->seeJsonEquals(['authUrl' => $authUrl]);
    }


    /**
     * test accessToken url is returned
     */
    public function testGetAccessToken(){

        $code = 'xwsoponpmuadw6aclommctso6t36ei';

        $mock = Mockery::spy(\NewTwitchApi\NewTwitchApi::class);


        $expectedTokenApiResponse = json_encode([
            'access_token' => '0123456789abcdefghijABCDEFGHIJ',
            'refresh_token' => '6VFV5LNrZFUj8oU231',
            'expires_in' => 3600,
            'scope' => ['viewing_activity_read'],
            'token_type' => ['bearer'],

        ]);

        $tokenResponse = new \GuzzleHttp\Psr7\Response(200, [], $expectedTokenApiResponse);

        $redirectUri = $this->buildUrlString(env('TWITCH_API_REGISTERED_REDIRECT_URL'));
        $mock->shouldReceive('getOauthApi->getUserAccessToken')
             ->withArgs([$code, $redirectUri])
             ->andReturn($tokenResponse);
        $this->app->instance(\NewTwitchApi\NewTwitchApi::class, $mock);

        $this->get('/api/auth/token?code=' . $code);

        $this->assertResponseStatus(200);
        $this->shouldReturnJson(json_decode($expectedTokenApiResponse, true));
    }



}
