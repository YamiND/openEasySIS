#!/bin/bash

#########################
# Update/Upgrade System #
#########################

sudo yum -y update

# Install Software #

yum install -y epel-release

yum install git vim htop policycoreutils-python wget httpd php mod_ssl openssh-server mariadb mariadb-server php-mysqli

########################################
# Install OpenSSH Server and Configure #
########################################

cp /etc/ssh/sshd_config ~/sshd_config.backup
sudo sed -i '/PermitRootLogin yes/c\PermitRootLogin no' /etc/ssh/sshd_config
sudo sed -i '/#Port 25/c\Port 1069' /etc/ssh/sshd_config

##########################################
# Configure SELinux and Firewall for SSH #
##########################################

sudo semanage port -a -t ssh_port_t -p tcp 1069

sudo firewall-cmd --permanent --add-port=1069/tcp
sudo firewall-cmd --reload

#########################################
# Change Ownerships and set Permissions #
#########################################

sudo chown -R apache:apache /var/www/html
sudo chmod -R 555 /var/www/html

#############################################################
# Add an exception to SELinux to allow php files to be run  #
# Any files within that directory (should be document root) #
#############################################################

sudo semanage fcontext -a -t httpd_sys_script_exec_t '/var/www/html(/.*)?'
sudo restorecon -R -v /var/www/html/

#############################
# Remove the welcome screen #
#############################

sudo rm -f /etc/httpd/conf.d/welcome.conf

############################################
# Add exception to the firewall and reload #
# This should open the HTTPS port          #
############################################

sudo firewall-cmd --permanent --add-port=443/tcp
sudo firewall-cmd --reload

############################################
# Add exception to the firewall and reload #
# This should open the HTTPS port          #
############################################

sudo firewall-cmd --permanent --add-port=80/tcp
sudo firewall-cmd --reload

##############################################
# Start the httpd service and enable at boot #
##############################################

systemctl start httpd
systemctl enable httpd

# Enable mariadb #
sudo systemctl enable mariadb
sudo systemctl start mariadb

# Harden mysql # 
sudo mysql_secure_installation

################################
# Change PHP's max upload size #
################################

sudo sed -i '/upload_max_filesize/c\upload_max_filesize = 20M' /etc/php.ini
sudo sed -i '/post_max_size/c\post_max_size = 20M' /etc/php.ini

############
# Hide PHP #
############

sudo sed -i '/expose_php/c\expose_php = off' /etc/php.ini

# Allow HTTPD to access mariadb #
setsebool -P httpd_can_network_connect 1

# Allow apache to sendmail
setsebool -P httpd_can_sendmail 1

# Install sendmail
yum install sendmail*

# Install dovecot
yum install dovecot
