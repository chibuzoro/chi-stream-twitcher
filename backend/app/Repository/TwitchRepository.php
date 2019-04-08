<?php
/**
 * Created by IntelliJ IDEA.
 * User: chibuzorogbu
 * Date: 2019-04-08
 * Time: 09:07
 */
namespace App\Repository;

use NewTwitchApi\NewTwitchApi;

class TwitchRepository
{

    /**
     * @var NewTwitchApi|Null
     */
    private $twitchApi;

    /**@var string **/
    protected $redirectUri;


    private const  SCOPES = 'user:edit+viewing_activity_read+openid+channel:read:subscriptions+bits:read+channel_subscriptions+channel_read';




    public function __construct(NewTwitchApi $twitchApi, string $redirectUri)
    {
        $this->twitchApi = $twitchApi;
        $this->redirectUri = $redirectUri;

    }


    /**
     * Gets ouath2  authorization url for Activation Code workflow
     * @return string
     */
    final public function getAuthCodeUrl(): string
    {
        return $this->twitchApi->getOauthApi()->getAuthUrl(
            $this->redirectUri,
            'code',
            self::SCOPES,
            false
        );
    }


    /**
     * converts activation code into access tokens
     * @link https://dev.twitch.tv/docs/authentication/getting-tokens-oauth/#oauth-authorization-code-flow
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    final public function getAccessToken($authCode)
    {

        return $this->twitchApi->getOauthApi()->getUserAccessToken(
            $authCode,
            $this->redirectUri
        );
    }

}
