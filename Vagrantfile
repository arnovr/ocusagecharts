VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "owncloud.box"

  config.vm.box_url = "http://thuis.arnoenbregtje.nl/public.php?service=files&t=75fa63994f6828d804f455985b9dedaa&path=%2Fowncloud&files=owncloud.box&download"

  config.vm.network "private_network", ip: "192.168.33.33"
  config.vm.hostname = "owncloud.box"

  config.ssh.username = "vagrant"
  config.ssh.password = "vagrant"

  config.vm.synced_folder "./", "/var/www", owner:  "www-data", group: "www-data"
end
