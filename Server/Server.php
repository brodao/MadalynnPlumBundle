<?php

/*
 * This file is part of the Madalynn package.
 *
 * (c) 2010-2011 Julien Brochet <mewt.fr@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Madalynn\DeployBundle\Server;

class Server
{
    protected $host;
    protected $port;
    protected $user;
    protected $dir;
    
    public function __construct($host, $user, $dir, $port = 22)
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->dir = $dir;
    }
}
