<?php

namespace iTzFreeHD\Ssigncommand;

use pocketmine\block\Block;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\RemoteConsoleCommandSender;
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


    public function onEnable()
    {

        @mkdir($this->getDataFolder());

        $cfg = new Config($this->getDataFolder(). "config.yml", Config::YAML);

        if(empty($cfg->get("Prefix"))) {
            $cfg->set("Prefix", "§7[§bSingCommands§7]");
            $cfg->save();
        }

        $this->p = $cfg->get("Prefix");
        $this->getLogger()->info(c::GREEN . "Plugin wurde Geladen");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

    }
    public function onSignCange(SignChangeEvent $event){
            $player = $event->getPlayer();
            if (strtolower($event->getLine(0) === "[run]")) {
                if ($player->hasPermission("command.sign") or $player->isOp()) {
                    $event->setLine(0, c::GREEN . "Command");
                    if ($event->getLine(1) === "Player") {
                        $event->setLine(1, c::YELLOW . "Player");
                    } else {
                        if ($event->getLine(1) === "VIP") {
                            $event->setLine(1, c::GOLD . "VIP");
                        } else {
                            if ($event->getLine(1) === "Admin") {
                                $event->setLine(1, c::RED . "Only Stuff");
                            } else {
                                if ($event->getLine(1) === "Console") {
                                    $event->setLine(1, c::RED . "Console");
                                }
                            }
                        }
                    }
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
                    if ($text[1] === c::YELLOW . "Player") {
                        if ($player->hasPermission("player.sign")) {
                            $this->getServer()->dispatchCommand($player, $cmd);
                        } else {
                            $player->sendMessage($this->p . c::RED . "Keine Rechte");
                        }
                    }
                    if ($text[1] === c::GOLD . "VIP") {
                        if ($player->hasPermission("vip.sign")) {
                            $this->getServer()->dispatchCommand($player, $cmd);
                        } else {
                            $player->sendMessage($this->p . c::RED . "Keine Rechte");
                        }
                    }
                    if ($text[1] === c::RED . "Only Stuff") {
                        if ($player->hasPermission("admin.sign")) {

                            $this->getServer()->dispatchCommand($player, $cmd);
                        } else {
                            $player->sendMessage($this->p . c::RED . "Keine Rechte");
                        }
                    }
                    if ($text[1] === c::RED . "Console") {
                        if ($player->hasPermission("console.sign")) {
                            $ccmd = $text[2] and $text[3];
                            $this->getServer()->dispatchCommand(new ConsoleCommandSender(), $ccmd);
                        } else {
                            $player->sendMessage($this->p . c::RED . "Keine Rechte");
                        }
                    }
                }
            }
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
        {
            if ($command->getName() === "sign") {
                $sender->sendMessage(c::AQUA . "Made by iTzFreeHD");
                $sender->sendMessage(c::GRAY . "--------------------");
                $sender->sendMessage(c::GREEN . "So benutz du SignCommand");
                $sender->sendMessage(c::GREEN . "Line1: [run]");
                $sender->sendMessage(c::GREEN . "Line2: Player, VIP, Admin");
                $sender->sendMessage(c::GREEN . "Line3: <Command>");
                $sender->sendMessage(c::GREEN . "Line4: <Beschreibung>");
                $sender->sendMessage(c::GREEN . "Alle Permissions:");
                $sender->sendMessage(c::GREEN . " Player -> player.sign");
                $sender->sendMessage(c::GREEN . " VIP -> vip.sign");
                $sender->sendMessage(c::GREEN . " Admin -> admin.sign");
                $sender->sendMessage(c::GREEN . " Console -> console.sign");
                $sender->sendMessage(c::GRAY . "--------------------");
                return false;
            }
        }
}