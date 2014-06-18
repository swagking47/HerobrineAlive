<?php

namespace HerobrineAlive;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Main extends PluginBase implements Listener{
	public $herobrine = false;
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new HerobrineChat($this), 3*60*20);
		$this->getLogger()->info("HerobrineAlive Loaded!");
	}
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
		switch($cmd->getName()){
			case "herobrine":
				if($args[0] == "release"){
					if($this->herobrine === false){
						$spawn = $this->getServer()->getDefaultLevel()->getSafeSpawn();
						$this->herobrine = new Herobrine($this, $spawn);
						$sender->sendMessage("[HerobrineAlive] Herobrine has been released!");
					}
					else{
						$sender->sendMessage("[HerobrineAlive] Herobrine is already active!");
					}
				}
				elseif($args[0] == "kill"){
					if($this->herobrine instanceof Herobrine){
						$this->herobrine->finalize();
						$this->herobrine = false;
						$sender->sendMessage("[HerobrineAlive] Herobrine has been killed!");
					}
					else{
						$sender->sendMessage("[HerobrineAlive] Herobrine isn't currently active!");
					}
				}
				elseif($args[0] == "attack"){
					$target = $this->getServer()->getPlayer($args[1]);
					if($target instanceof Player){
						if($this->herobrine instanceof Herobrine){
							$this->herobrine->setTarget($target);
							$sender->sendMessage("[HerobrineAlive] Herobrine is now after " . $args[1] . "!");
						}
						else{
							$sender->sendMessage("[HerobrineAlive] Herobrine must be active to attack!");
						}
					}
					else{
						$sender->sendMessage("[HerobrineAlive] Target player not found!");
					}
				}
				else{
					$sender->sendMessage("Usage: /herobrine <release|kill|attack> [player]");
				}
			break;
		}
	}
	public function onDisable(){
		$this->getLogger()->info("HerobrineAlive Unloaded!");
		$h =& $this->herobrine;
		if($h instanceof Herobrine){
			$h->finalize();
		}
		$h = false;
	}
	/**
	 * @return bool|Herobrine
	 */
	public function getHerobrine(){
		return $this->herobrine;
	}
}
