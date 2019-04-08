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

}
