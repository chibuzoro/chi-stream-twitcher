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
}
