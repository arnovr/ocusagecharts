VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "ocusagecharts.box"

  config.vm.box_url = "http://thuis.arnoenbregtje.nl/public.php?service=files&t=75fa63994f6828d804f455985b9dedaa&path=%2Fdebian&files=ocusagecharts.box&download"

  config.vm.network "private_network", ip: "192.168.12.12"
  config.vm.hostname = "ocusagecharts.box"

  config.ssh.username = "debian"
  config.ssh.password = "debian"

  config.vm.synced_folder "./", "/var/www/owncloud/apps/ocusagecharts", owner:  "www-data", group: "www-data"

  config.vm.provision "ansible" do |ansible|
    ansible.playbook = "provision.yml"
    ansible.inventory_path = "inventory/devhosts"
    ansible.verbose = "v"
    ansible.limit = "192.168.12.12"
  end
end
