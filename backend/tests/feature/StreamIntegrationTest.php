<?php
/**
 * Created by IntelliJ IDEA.
 * User: chibuzorogbu
 * Date: 2019-04-08
 * Time: 20:02
 */

class StreamIntegrationTest extends TestCase
{

    public function testCaptureStream(){
        $mock = Mockery::spy(\NewTwitchApi\NewTwitchApi::class);

        $sampleStreamResponse = json_encode([
            'data'       => [
                [
                    'id'            => '26007494656',
                    'user_id'       => '23161357',
                    'user_name'     => 'LIRIK',
                    'game_id'       => '417752',
                    'community_ids' => [
                        "5181e78f-2280-42a6-873d-758e25a7c313",
                        "848d95be-90b3-44a5-b143-6e373754c382",
                        "fd0eab99-832a-4d7e-8cc0-04d73deb2e54",
                    ],
                    'type'          => 'live',
                    'title'         => 'Hey Guys, It\'s Monday - Twitter: @Lirik',
                    'viewer_count'  => 32575,
                    'started_at'    => '2017-08-14T16:08:32Z',
                    'language'      => 'en',
                    'thumbnail_url' => 'https://static-cdn.jtvnw.net/previews-ttv/live_user_lirik-{width}x{height}.jpg',

                ],
            ],
            'pagination' => [
                'cursor' => 'eyJiIjpudWxsLCJhIjp7Ik9mZnNldCI6MjB9fQ==',
            ],
        ]);

        $tokenResponse = new \GuzzleHttp\Psr7\Response(200, [],
            $sampleStreamResponse);

        $username = 'LIRIK';

        $mock->shouldReceive('getStreamsApi->getStreamForUsername')
             ->withArgs([$username])
             ->andReturn($tokenResponse);
        $this->app->instance(\NewTwitchApi\NewTwitchApi::class, $mock);

        $this->get('/api/stream/' . $username);
        $expectedResult[] = [
            'message'   => sprintf('%s viewers', 32575),
            'thumbnail' => strtr('https://static-cdn.jtvnw.net/previews-ttv/live_user_lirik-{width}x{height}.jpg', ['{width}' => 40, '{height}' => 40]),
            'userId'    => '23161357',
            'title'     => sprintf('%s: %s [%s]',
                'LIRIK',
                'Hey Guys, It\'s Monday - Twitter: @Lirik',
                'live'),
        ];

        $this->assertResponseStatus(200);
        $this->shouldReturnJson($expectedResult);

        // clear the mock
        $this->tearDown();
    }

    /**
     * @link @link https://dev.twitch.tv/docs/api/webhooks-guide/#subscriptions
     * @link https://dev.twitch.tv/docs/api/reference/#get-webhook-subscriptions
     * @link https://dev.twitch.tv/docs/api/webhooks-reference/#topic-stream-changed
     */
    public function testRegisterStreamWebHookSubscriptions(){
        $mock = Mockery::spy(\NewTwitchApi\NewTwitchApi::class);
        $token = 'xxxyxxxyx';
        $userId = 23161357;
        $streamCallbackUri = $this->buildUrlString('api/pubsub');

        $mock->shouldReceive('getWebhooksSubscriptionApi->subscribeToStream')
             ->withArgs([$userId, $token, $streamCallbackUri]);
        $this->app->instance(\NewTwitchApi\NewTwitchApi::class, $mock);

        $this->call('POST', 'api/stream/subscribe/'. $userId, [], [], [], [
            "HTTP_Authorization" => 'Bearer '. $token
        ]);

        $this->assertResponseOk();

        $this->tearDown();

    }


    /**
     * @link https://dev.twitch.tv/docs/api/webhooks-reference/#subscribe-tounsubscribe-from-events
     * @link https://dev.twitch.tv/docs/api/webhooks-guide/#getting-notifications
     */
    public function testPublishStreamChanged(){
        $mock = Mockery::spy(\Pusher\Pusher::class);

        $sampleStreamResponse = [
            'data'       => json_decode(json_encode([
                'id'            => '26007494656',
                'user_id'       => '23161357',
                'user_name'     => 'LIRIK',
                'game_id'       => '417752',
                'community_ids' => [],
                'type'          => 'live',
                'title'         => 'Best Stream Ever',
                'viewer_count'  => 32575,
                'started_at'    => '2017-08-14T16:08:32Z',
                'language'      => 'en',
                'thumbnail_url' => 'https://static-cdn.jtvnw.net/previews-ttv/live_user_lirik-{width}x{height}.jpg',

            ]))
        ];
        $username = 'LIRIK';
        $event = 'stream_changed';
        $token = 'xxxxyxxx';
        $data = [
            'message'   => sprintf('%s viewers', 32575),
            'thumbnail' => strtr('https://static-cdn.jtvnw.net/previews-ttv/live_user_lirik-{width}x{height}.jpg', ['{width}' => 40, '{height}' => 40]),
            'userId'    => '23161357',
            'title'     => sprintf('%s: %s [%s]', 'LIRIK', 'Best Stream Ever', 'live'),
        ];

        $mock->shouldReceive('trigger')
             ->withArgs([$username, $event, $data]);
        $this->app->instance(\Pusher\Pusher::class, $mock);

        $this->call('POST', 'api/pubsub', $sampleStreamResponse , [], [], [
            "HTTP_Authorization" => 'Bearer '. $token
        ]);

        $this->assertResponseOk();

        $this->tearDown();

    }


    /**
     * validates response from twitcher webhook.
     * @link https://dev.twitch.tv/docs/api/webhooks-reference/#subscribe-tounsubscribe-from-events
     */
    public function testVerifyStreamSubscription(){


        $token = 'xxxxyyxxx';
        $hubChallenge = 'myHubIKnow';

        $this->call('GET', 'api/pubsub?hub_challenge='.$hubChallenge, [] , [], [], [
            "HTTP_Authorization" => 'Bearer '. $token
        ]);

        $this->assertEquals($hubChallenge, $this->response->getContent());


    }

}
