<?php

namespace HerobrineAlive;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\protocol\AddPlayerPacket;

class Main extends PluginBase{
	public $herobrine = false;
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		// how do you expect this to be true if there is nothing that sets it to true?
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new HerobrineChat($this), 3*60*20);
		$this->getLogger()->info("HerobrineAlive Loaded!");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
		switch($cmd->getName()){
			case "herobrine":
				if($args[0] == "release"){
					if($this->herobrine === false){
						$this->herobrine = new Herobrine($this, null); // TODO replace null with the spawn position of Herobrine
						$sender->sendMessage("[HerobrineAlive] Herobrine has been released!");
					}else{
						$sender->sendMessage("[HerobrineAlive] Herobrine is already active!");
					}
				}elseif($args[0] == "kill"){
					if($this->herobrine instanceof Herobrine){
						$this->herobrine->finalize();
						$this->herobrine = false;
						$sender->sendMessage("[HerobrineAlive] Herobrine has been killed!");
					}else{
						$sender->sendMessage("[HerobrineAlive] Herobrine isn't currently active!");
					}
				}elseif($args[0] == "attack"){
					$target = $this->getServer()->getPlayer($args[1]);
					if($p instanceof Player){
						if($this->herobrine instanceof Herobrine){
							$this->herobrine->setTarget($target);
							$sender->sendMessage("[HerobrineAlive] Herobrine is now after " . $args[1] . "!");
						}else{
							$sender->sendMessage("[HerobrineAlive] Herobrine must be active to attack!");
						}
					}else{
						$sender->sendMessage("[HerobrineAlive] Target player not found!");
					}
				}else{
					$sender->sendMessage("Usage: /herobrine <release|kill|attack> [player]");
				}
			break;
		}
	}

	public function onDisable(){
		$this->getLogger()->info("HerobrineAlive Unloaded!");
	}
}
