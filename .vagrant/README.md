# How to Enable Port 80 in the Firewall on CentOS 9

To allow incoming traffic on port 80 (HTTP) in the firewall on CentOS 9, you can use the `firewalld` utility, which is the default firewall management tool. Follow these steps:

## Step 1: Check if `firewalld` is Installed
You can check if `firewalld` is installed on your CentOS 9 system by running the following command:
```bash
sudo systemctl is-enabled firewalld
```

If it's not installed, you can install it using dnf:
```bash
sudo dnf install firewalld
```

## Step 2: Enable and Start `firewalld`
If firewalld is not already enabled and started, you can do so with the following commands:
```bash
sudo systemctl enable firewalld
sudo systemctl start firewalld
```

## Step 3: Allow Port 80 (HTTP)
To allow incoming traffic on port 80 (HTTP), you can use the firewall-cmd command. Run the following command to open port 80:
```bash
sudo firewall-cmd --add-port=80/tcp --permanent
```

- `--add-port=80/tcp`: This command adds port 80 to the list of allowed ports.
- `--permanent`: This flag makes the rule persistent, so it will survive firewall reloads or system reboots.

## Step 4: Reload `firewalld` to Apply the Changes
After adding the rule, reload `firewalld` to apply the changes:
```bash
sudo firewall-cmd --reload
```

## Step 5: Check the Status of the Firewall Rules
You can verify that port 80 is open by listing the allowed ports:
```bash
sudo firewall-cmd --list-ports
```

Port 80 should be listed in the output.
Port 80 is now open in the firewall, allowing incoming HTTP traffic. If you have a web server (e.g., Apache or Nginx) running on your CentOS 9 system, it should be able to accept incoming HTTP requests on port 80.