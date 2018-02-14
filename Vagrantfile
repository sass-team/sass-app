# -*- mode: ruby -*-
# vi: set ft=ruby :

confDir = $confDir ||= File.expand_path(File.dirname(__FILE__))
afterScriptPath = confDir + "/scripts/after.sh"


Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/xenial64"
  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.network "private_network", ip: "192.168.33.10"
  config.vm.synced_folder ".", "/var/www/sass", type: "nfs"
  config.vm.provision "shell", path: afterScriptPath, privileged: false, keep_color: true
end
