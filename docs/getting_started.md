## Requirements

To install the NetBoot/SUS/LP server using an installer, you need:

* The NetBoot/SUS/LP Server Installer (.run), available at:
<https://www.jamf.com/jamf-nation/third-party-products/180/netboot-sus-appliance?view=info>
* One of the following operating systems:
	* Ubuntu 14.04 LTS Server
	* Ubuntu 16.04 LTS Server
	* Red Hat Enterprise Linux (RHEL) 6.4 or later
	* CentOS 6.4 or later
* 500 GB of disk space available 
* 1 GB of RAM

To set up the NetBoot/SUS/LP server as an appliance, you need:

* The OVA file for the NetBoot/SUS/LP server, available at:
<https://www.jamf.com/jamf-nation/third-party-products/180/netboot-sus-appliance?view=info>
* Virtualization software that supports Open Virtualization Format 
* 500 GB of disk space available
* 2 GB of RAM

To host a NetBoot server using the NetBoot/SUS/LP server, you need a NetBoot image (.nbi folder). For more information, see the following Knowledge Base article:

[Creating a NetBoot Image and Setting Up a NetBoot Server](https://www.jamf.com/jamf-nation/articles/307/creating-a-netboot-image-and-setting-up-a-netboot-server)

**Only Intel-based Macs can use a NetBoot server hosted by the NetBoot/SUS/LP server.**

## Installing the NetBoot/SUS/LP Server Using an Installer
1. Copy the NetBoot/SUS/LP Installer (.run) to the server on which you plan to install the NetBoot/SUS /LP server.

2. Log in to the server as a user with superuser privileges.

3. Initiate the installer by executing a command similar to the following:

		sudo /path/to/NetSUSLPInstaller.run
	
4. Type "y" to proceed.

5. Go to `https://myhostname.local/webadmin` to access the NetBoot/SUS/LP server web application. Once the NetBoot/SUS/LP server is installed, it is recommended that you log in to the web application and change all usernames and passwords associated with the server. For more information, see [Accounts](accounts.md).

## Setting Up the NetBoot/SUS/LP Server as an Appliance
To set up the NetBoot/SUS/LP server as an appliance, import the OVA file for the NetBoot/SUS/LP server into a virtualization software product. This creates an Ubuntu VM with running SMB and AFP shares. The first time you power on the VM, a page displaying the URL for the NetBoot/SUS/LP server web application appears.

Once the NetBoot/SUS/LP server is set up as an appliance, it is recommended that you log in to the web application and change all usernames and passwords associated with the server. For more information, see [Accounts](accounts.md).
