<?php

namespace App\Http\Middleware;

use App\Repository\TwitchRepository;
use App\Traits\TwitchTrait;
use Closure;
use Illuminate\Http\Request;

class TwitchAuthorizationMiddleware
{

    use TwitchTrait;

    /**
     * @var TwitchRepository
     */
    private $twitchRepo;
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    public function __construct(TwitchRepository $repository)
    {
        $this->twitchRepo = $repository;

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $token = $this->extractTokenFromHeader($request);
        if ($this->twitchRepo->isValidToken($token)) {
            return $next($request);
        }
        return response('Unauthorized', 401, [
            'WWW-Authenticate' => 'Basic realm="id.twitcher.tv"',
        ]);
    }
}
