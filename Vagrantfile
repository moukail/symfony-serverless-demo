Vagrant.configure("2") do |config|
  config.vm.box = "generic/centos9s"
  projectDirectory = "/var/www/"

  # Provisioner to run init.sh script
  config.vm.provision "shell", path: ".vagrant/bootstrap_centos.sh"

  # Synced folder to share your project files
  config.vm.synced_folder './', projectDirectory

  # Forward port 8000 from the VM to port 8080 on the host
  config.vm.network "forwarded_port", guest: 8000, host: 8000

  # Disable SSH key insertion
  config.ssh.insert_key = false

  config.vm.provider "virtualbox" do |v|
    v.memory = 1024
    v.cpus = 1
  end

  # Trigger to run init.sh script after VM is up
  config.trigger.after :up, :reload do |trigger|
    trigger.run_remote = {
      inline: "su - vagrant -c 'cd #{projectDirectory} && .vagrant/init.sh'"
    }
  end
end
