<?php

namespace HerobrineAlive;

use pocketmine\scheduler\PluginTask;
use pocketmine\Server;

class HerobrineChat extends PluginTask{
    private $messages = [];
    public function __construct(Main $plugin){
        parent::__construct($plugin);
        $this->messages[] = "<Herobrine> Where is your god now?";
        $this->messages[] = "<Herobrine> You can not defeat me!";
        $this->messages[] = "<Herobrine> I am your god now!";
        $this->messages[] = "<Herobrine> Your world is mine!";
        $this->messages[] = "<Herobrine> You can't escape me!";
        $this->messages[] = "<Herobrine> I am your worst nightmare!";
        $this->messages[] = "<Herobrine> Even Notch can't save you!";
        $this->messages[] = "<Herobrine> I'm here!";
        $this->messages[] = "<Herobrine> No one can save you now!";
        $this->messages[] = "<Herobrine> You're next!";
    }
    public function onRun(){
        Server::getInstance()->broadcastMessage(array_rand($this->messages));
    }
}
