<?php

namespace HerobrineAlive;

use pocketmine\inventory\InventoryHolder;
use pocketmine\inventory\InventoryType;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\entity\Entity;
use pocketmine\entity\ProjectileSource;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\network\protocol as ptc;

class Herobrine extends Position implements ProjectileSource, InventoryHolder{
	/** @var int */
	protected $eid;
	/** @var float */
	protected $yaw, $pitch;
	/** @var Main */
	protected $plugin;
	/** @var Server */
	protected $server;
	/** @var HerobrineAI */
	protected $control;
	/** @var Player[] */
	protected $viewers = [];
	/** @var int */
	protected $fireTicks = 0;
	/** @var HerobrineInventory */
	protected $inventory;
	/** @var Entity */
	protected $target;
	public function __construct(Main $plugin, Position $pos, Vector3 $speed = null, $ticks = 0.1, $yaw = 0, $pitch = 0){
		$this->eid = Entity::$entityCount++;
		$this->plugin = $plugin;
		$this->server = Server::getInstance();
		$this->control = new HerobrineAI($plugin, $this, 0.1); // 0.1 is the number of ticks in a second to update this entity
		$this->x = $pos->getX();
		$this->y = $pos->getY();
		$this->z = $pos->getZ();
		$this->yaw = $yaw;
		$this->pitch = $pitch;
		$this->setLevel($pos->getLevel());
		$this->speed = ($speed instanceof Vector3) ? new Vector3($speed->getX(), $speed->getY(), $speed->getZ()):new Vector3(0, 0, 0);
		$this->inventory = new HerobrineInventory($this, InventoryType::get(InventoryType::PLAYER));
	}
	public function spawnTo(Player $player){
		$pk = new ptc\AddPlayerPacket;
		$pk->clientID = 0;
		$pk->username = "Herobrine";
		$pk->eid = $this->eid;
		$pk->x = $this->getX();
		$pk->y = $this->getY();
		$pk->z = $this->getZ();
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
		$pk->unknown1 = 0;
		$pk->unknown2 = 0;
		$pk->metadata = $this->getMetadata();
		$player->dataPacket($pk);
		$this->viewers[$player->getID()] = $player;
	}
	public function despawnFrom(Player $player){
		$pk = new ptc\RemovePlayerPacket;
		$pk->eid = $this->eid;
		$pk->clientID = 0;
		$player->dataPacket($pk);
		unset($this->viewers[$player->getID()]);
	}
	public function say($msg){
		Server::getInstance()->broadcastMessage($msg);
	}
	public function setSpeed(Vector3 $motion){
		$this->speed = $motion;
		$pk = new ptc\SetEntityMotionPacket;
		$pk->eid = $this->eid;
		$pk->speedX = $motion->getX();
		$pk->speedY = $motion->getY();
		$pk->speedZ = $motion->getZ();
		$this->broadcastPacket($pk);
	}
	public function broadcastPacket(ptc\DataPacket $packet){
		foreach($this->viewers as $viewer){
			$viewer->dataPacket($packet);
		}
	}
	public function tick(){
		$this->x += $this->speed->getX();
		$this->y += $this->speed->getY();
		$this->z += $this->speed->getZ();
		$this->fireTicks--;
	}
	/**
	 @return int
	 */
	public function getMetadata(){
		$flags = 0;
		$flags |= $this->isOnFire() ? 1:0;
		$d = [
			0 => ["type" => 0, "value" => $flags],
			1 => ["type" => 1, "value" => 0], // it says airTicks. TODO: compare the effect of the change in this value.
			16 => ["type" => 0, "value" => 0], // wt* is this?
			17 => ["type" => 6, "value" => [0, 0, 0]], // wt* is this?
		]; // TODO 0.9.0 update
		return $d;
	}
	public function isOnFire(){
		return $this->fireTicks > 0;
	}
	public function finalize(){
		foreach($this->viewers as $viewer){
			$this->despawnFrom($viewer);
		}
	}
	public function getInventory(){
		return $this->inventory;
	}
	public function setTarget(Entity $target){
		$this->target = $target;
	}
	public function getTarget(){
		return $this->target;
	}
}
