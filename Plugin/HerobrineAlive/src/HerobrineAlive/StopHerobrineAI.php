<?php

namespace HerobrineAlive;

use pocketmine\scheduler\PluginTask;
use pocketmine\network\protocol\AddPlayerPacket;
use pocketmine\Player;

class StopHerobrineAI extends PluginTask implements Main, StartHerobrineAI{
    public function onRun(){
        $this->finalize();
    }
}
