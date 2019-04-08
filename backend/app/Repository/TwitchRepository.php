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

    public function __construct(NewTwitchApi $twitchApi)
    {
        $this->twitchApi = $twitchApi;

    }

}
