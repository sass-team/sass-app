# -*- mode: ruby -*-
# vi: set ft=ruby :

confDir = $confDir ||= File.expand_path(File.dirname(__FILE__))
afterScriptPath = confDir + "/scripts/after.sh"


Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/xenial64"
  config.vm.network "forwarded_port", guest: 33060, host: 3306
  config.vm.network "forwarded_port", guest: 3306, host: 33060
  config.vm.network "private_network", ip: "192.168.33.10"
  config.vm.synced_folder ".", "/var/www/sass", type: "nfs"
  config.vm.provision "shell", path: afterScriptPath, privileged: false, keep_color: true

  config.vm.provider "virtualbox" do |v|
     v.memory = 2048
  end
end
