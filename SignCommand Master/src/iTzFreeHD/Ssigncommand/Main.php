<?php

namespace iTzFreeHD\Ssigncommand;

use pocketmine\block\block;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as c;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Player\PlayerInteractEvent;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;


class Main extends PluginBase implements listener{
    
    public $p;
    public $cfg;


    public function onEnable() {
        $this->getLogger()->info(c::GREEN."Plugin wurde Geladen");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        
        
        @mkdir($this->getDataFolder());
		if (!file_exists($this->getDataFolder() . 'config.yml')) {
			$this->initConfig();
		}

	$this->cfg = new Config($this->getDataFolder() . 'config.yml', Config::YAML);
        
        
    }
    public function initConfig() {
		$this->cfg = new Config($this->getDataFolder() . 'config.yml', Config::YAML);
		$this->cfg->set('Prefix', '§7[§6SignCommands§7]§r ');
		$this->cfg->save();
                $this->p = $this->cfg->get("Prefix");
	}
    public function onSignCange(SignChangeEvent $event){
            $player = $event->getPlayer();
            if (strtolower($event->getLine(0) === "[run]")) {
			if ($player->hasPermission("command.sign") or $player->isOp()) {
                            $event->setLine(0, c::GREEN."Command");
                            if ($event->getLine(1) === "Player") {
                               $event->setLine(1, c::YELLOW."Player"); 
                            } else {
                                if ($event->getLine(1) === "VIP") {
                                    $event->setLine(1, c::GOLD."VIP");
                                } else {
                                    if ($event->getLine(1) === "Admin") {
                                        $event->setLine(1, c::RED."Only Stuff");
                                    }
                                }
                            }
			} else {
                            $event->getPlayer()->sendMessage($this->p.c::RED."Du hast keine Permissions");
                            $event->setLine(0, "No Perm");
                            $event->setLine(1, "ERROR");
                            }
            }
}
    public function onSignClick(PlayerInteractEvent $event){
        $player = $event->getPlayer();
		$id = $event->getBlock()->getId();
		if ($id === Block::SIGN_POST or $id === Block::WALL_SIGN) {
			$tile = $event->getPlayer()->getLevel()->getTile($event->getBlock());
			if ($tile instanceof \pocketmine\tile\Sign) {
				$text = $tile->getText();
                                $cmd = $text[2];
				if (strtolower(c::clean($text[0])) === strtolower("Command")) {
					$this->getServer()->dispatchCommand($player, $cmd);
				} elseif (strtolower(c::clean($text[1])) === strtolower("ERROR")) {
                                    $player->sendMessage($this->p.c::RED."ERROR");
                            }
			}
		}
    }
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($command->getName() === "sign") {
            $sender->sendMessage(c::AQUA."Made by iTzFreeHD");
            $sender->sendMessage(c::GRAY."--------------------");
            $sender->sendMessage(c::GREEN."So benutz du SignCommand");
            $sender->sendMessage(c::GREEN."Line1: [run]");
            $sender->sendMessage(c::GREEN."Line2: Player, VIP, Admin");
            $sender->sendMessage(c::GREEN."Line3: <Command>");
            $sender->sendMessage(c::GREEN."Line4: <Beschreibung>");
            $sender->sendMessage(c::GREEN."Alle Permissions:");
            $sender->sendMessage(c::GREEN." Player -> player.sign");
            $sender->sendMessage(c::GREEN." VIP -> vip.sign");
            $sender->sendMessage(c::GREEN." Admin -> admin.sign");
            $sender->sendMessage(c::GRAY."--------------------");
            return false;}
    }
}