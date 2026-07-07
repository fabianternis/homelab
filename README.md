# Fabian's Homelab

This repository contains information about the homelab network setup.

## Network Overview
The network uses the **IPv4** `192.168.1.0/24` subnet and the **IPv6** `2a09:3f00:2004::/64` subnet.

### Gateway
- **.1**: Router / Main Gateway (Archer MR200) - Default IPv4 Gateway
- **.23** (`2a09:3f00:2004::1`): mini3 - Default IPv6 Gateway (via WireGuard Tunnel)

### Mini PCs
- **.21**: mini1 (immich.ternis.net) - immich Host (HP ProDesk 600 G2 Mini)
- **.22**: mini2 - nextcloud + n8n Host (HP ProDesk 600 G2 Mini)
- **.23**: mini3 + pi.hole - Wireguard (Client + Host) + Pi-hole Host (HP ProDesk 400 G2 Mini)

### Network Devices
- **.50**: TP-Link Network Switch
- **.70**: TL-WA850RE - WiFi Extender (TP-Link Access Point)

## IP Addressing
Static IP addresses are managed and tracked in the `Addresses.csv` file. For IPv6, SLAAC is enabled but devices can be assigned static addresses (e.g. `2a09:3f00:2004::21` for `.21`).

## WireGuard & Pi-hole
Wenn du über den WireGuard Tunnel auch deinen Pi-Hole auf dem `mini3` als DNS-Server nutzen möchtest, kannst du im `[Interface]`-Block der Client-Config noch `DNS = 192.168.1.23` oder `DNS = 10.5.0.1` eintragen.
