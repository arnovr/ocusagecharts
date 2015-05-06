VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "ubuntu/trusty64"

  config.vm.network "private_network", ip: "192.168.12.12"
  config.vm.hostname = "ocusagecharts.box"

  config.vm.synced_folder "./", "/var/www/owncloud/apps/ocusagecharts", owner:  "www-data", group: "www-data"

  config.vm.provision "ansible" do |ansible|
    ansible.playbook = "ansible/provision.yml"
    ansible.inventory_path = "ansible/inventory/devhosts"
    ansible.verbose = "v"
    ansible.limit = "192.168.12.12"
  end
end