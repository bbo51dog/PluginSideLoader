<?php

namespace bbo51dog\sideloader;

use pocketmine\plugin\PluginDescription;
use pocketmine\plugin\PluginLoader;
use pocketmine\Server;

class FolderPluginLoader implements PluginLoader{

    public function canLoadPlugin(string $path): bool{
        return is_dir($path) && file_exists($path . "/plugin.yml") && file_exists($path . "/src/");
    }

    public function loadPlugin(string $file): void{
        Server::getInstance()->getLoader()->addPath("", "{$file}/src");
    }

    public function getPluginDescription(string $file): ?PluginDescription{
        if(is_dir($file) and file_exists($file . "/plugin.yml")){
            $yaml = file_get_contents($file . "/plugin.yml");
            return new PluginDescription($yaml);
        }
        return null;
    }

    public function getAccessProtocol(): string{
        return '';
    }
}