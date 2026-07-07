# Fabian's Homelab

This repository contains documentation, configurations, and scripts for the `ternis.net` homelab network setup.

## 📚 Documentation
Detailed architectural and service information is kept in the `docs/` folder:

- [**Services Overview**](docs/services_overview.md): Details about all running services (Immich, Gitea, n8n, Nextcloud) and their respective ports.
- [**Network Architecture**](docs/network_architecture.md): Explanation of the WireGuard routing, Reverse Proxy setup, and how external traffic reaches internal servers.
- [**DNS Records**](docs/dns_records.md): The master list of all `*.ternis.net` subdomains and how they resolve locally vs. externally.
- [**IPv6 Allocation Plan**](docs/ipv6_allocation_plan.md): The IPv6 subnets and static IPv6 assignments.

## 🚀 Network Overview
The network uses the **IPv4** `192.168.1.0/24` subnet and the **IPv6** `2a09:3f00:2004::/64` subnet.

### 🌐 Gateways
- **.1**: Router (Archer MR200) - Primary DHCP server (native IPv4 routing currently degraded).
- **.23**: `mini3` - Serves as the stable IPv4 NAT Gateway (routing traffic out through WireGuard), DNS Server (Pi-Hole), and Reverse Proxy (Apache).

### 🖥️ Servers (Mini PCs)
- **.21 (`mini1`)**: Main Application Server (Docker Host for Immich, Gitea, and n8n).
- **.22 (`mini2`)**: Secondary Server (currently offline; planned for Nextcloud).
- **.23 (`mini3`)**: Network Core Server (Pi-hole, WireGuard tunnel to VPS, Apache Proxy).

### 🔌 Network Devices
- **.50**: TP-Link Network Switch
- **.70**: TL-WA850RE - WiFi Extender (TP-Link Access Point)

## 📡 IP Addressing
Static IP addresses are managed and tracked in the `Addresses.csv` file. 

## 🌐 Dashboard
A simple status dashboard for the homelab is available in the `website/` directory.

## 🤖 n8n Workflows
Exported n8n workflows (like the Service Health Checker) are stored in the `n8n_workflows/` directory.

---
**WireGuard Note**: To use Pi-hole DNS over the VPN tunnel, add `DNS = 192.168.1.23` (or the VPN-IP `10.2.3.9`) to your client config.
