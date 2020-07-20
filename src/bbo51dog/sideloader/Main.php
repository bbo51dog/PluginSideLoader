<?php

namespace bbo51dog\sideloader;

use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginLoadOrder;
use pocketmine\utils\Config;
use function file_exists;
use function is_dir;
use function substr;

class Main extends PluginBase{

    public function onLoad(){
        $config = new Config('SideLoader.yml', Config::YAML, [
            'dirs' => [
                'directory/',
            ],
            'plugins' => [
                'ExamplePlugin',
            ],
        ]);
        $loader = new FolderPluginLoader();
        $this->getServer()->getPluginManager()->registerInterface($loader);
        /** @var string[] $dirs */
        $dirs = $config->get('dirs');
        /** @var string[] $plugins */
        $plugins = $config->get('plugins');
        foreach($dirs as $dir){
            if(substr($dir, -1) !== '/'){
                $dir .= '/';
            }
            foreach($plugins as $k => $plugin){
                $path = $dir . $plugin;
                if(file_exists($path) && is_dir($path)){
                    $this->getServer()->getPluginManager()->loadPlugin($path, [$loader]);
                    unset($plugins[$k]);
                }
            }
        }
    }

    public function onEnable(){
        $this->getServer()->enablePlugins(PluginLoadOrder::STARTUP);
    }
}