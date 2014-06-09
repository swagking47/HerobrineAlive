<?php

namespace HerobrineAlive;

use pocketmine\scheduler\PluginTask;
use pocketmine\Player;

class StopHerobrineAI extends PluginTask implements StartHerobrineAI{
    public function onRun(){
        $this->finalize();
    }
}
