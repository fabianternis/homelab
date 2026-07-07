# Fabian's Homelab

This repository contains information about the homelab network setup.

## Network Overview
The network uses the `192.168.1.0/24` subnet.

### Gateway
- **.1**: Router / Main Gateway (Archer MR200)

### Mini PCs
- **.21**: mini1 (immich.ternis.net) - immich Host (HP ProDesk 600 G2 Mini)
- **.22**: mini2 - nextcloud + n8n Host (HP ProDesk 600 G2 Mini)
- **.23**: mini3 + pi.hole - Wireguard (Client + Host) + Pi-hole Host (HP ProDesk 400 G2 Mini)

### Network Devices
- **.50**: TP-Link Network Switch
- **.70**: TL-WA850RE - WiFi Extender (TP-Link Access Point)

## IP Addressing
Static IP addresses are managed and tracked in the `Addresses.csv` file.

