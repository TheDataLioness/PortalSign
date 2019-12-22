<?php

declare(strict_types=1);

namespace DataLion\PortalSign;

use pocketmine\block\Block;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\level\Position;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\block\WallSign;
use pocketmine\tile\Tile;

class Main extends PluginBase implements Listener {

	public function onEnable() : void{
		$this->getLogger()->info("Enabled");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}



	public function onDisable() : void{
		$this->getLogger()->info("Disabled");
	}


	public function onMove(PlayerMoveEvent $e){
	    $player = $e->getPlayer();
	    if($e->getPlayer()->getLevel()->getBlock($player->asVector3())->getId() == Block::HEAVY_WEIGHTED_PRESSURE_PLATE){
	        if($e->getPlayer()->getLevel()->getBlock(new Vector3($player->getFloorX(), $player->getFloorY() + 1, $player->getFloorZ()))->getId() == Block::WALL_SIGN){
	            if(is_null($e->getPlayer()->getLevel()->getTile(new Vector3($player->getFloorX(), $player->getFloorY() + 1, $player->getFloorZ())))){
	                return;
                }
	            $text = $e->getPlayer()->getLevel()->getTile(new Vector3($player->getFloorX(), $player->getFloorY() + 1, $player->getFloorZ()))->getText();
	            if(strtolower($text[0]) == "[tdc portal]"){
	                if(!empty($text[1])){
	                    $level = $this->getServer()->getLevelByName($text[1]);
	                    if(!is_null($level)){
	                        if(!empty($text[2])){
	                            $coords = explode(" ", $text[2]);
	                            if(sizeof($coords) == 3){
	                                foreach ($coords as $num){
	                                    if(!is_numeric($num)){
	                                        return;
                                        }
                                    }
	                                $x = intval($coords[0]);
	                                $y = intval($coords[1]);
	                                $z = intval($coords[2]);
	                                $player->teleport(new Position($x, $y, $z, $level));
	                                if(!empty($text[3])){
	                                    $player->addTitle($text[3]);
                                        $player->getLevel()->addSound(new EndermanTeleportSound($player->asVector3(), 3), [$player]);
                                    }
                                }
                            }
                        }
                    }
                }
	        }

        }
    }
}
