# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    config.vm.box = "precise32"

    config.vm.box_url = "http://files.vagrantup.com/precise32.box"

    config.vm.network :forwarded_port, guest: 80, host: 8839
    config.ssh.forward_agent = true
    
    config.vm.provision :shell, :path => "root-install.sh"
    config.vm.provision :shell, :path => "user-install.sh", privileged: false

    config.vm.synced_folder ".", "/vagrant", :mount_options => ["dmode=777", "fmode=666"]

    
    config.hostmanager.enabled = true
    config.hostmanager.manage_host = true
    config.hostmanager.ignore_private_ip = false
    config.hostmanager.include_offline = true
    config.vm.define 'testsite' do |node|
        node.vm.hostname = 'testsite.dev'
        node.vm.network :private_network, ip: '192.168.4.92'
    end
end

