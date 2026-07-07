# Network Architecture (ternis.net)

## General Concept
The entire `*.ternis.net` domain points to a public VPS (`77.90.15.49`). 
To keep services self-hosted at home without exposing ports through the home router (Archer MR200), a WireGuard tunnel connects the home network to the VPS.

## Routing Flow
1. **Public VPS (`77.90.15.49`)**: Receives all public HTTP (80) and HTTPS (443) traffic. The VPS uses iptables (DNAT) to directly forward all port 80/443 traffic through the WireGuard tunnel to `mini3`.
2. **Reverse Proxy / DNS (`mini3` - 192.168.1.23)**: 
   - Runs `Pi-hole` (for local DNS) and `Apache` (Reverse Proxy). 
   - When you access `*.ternis.net` from outside, the traffic hits Apache on `mini3`.
   - When you access `*.ternis.net` from the home network, Pi-hole (set as default DNS on the router) intercepts it via the local wildcard `address=/ternis.net/192.168.1.23`, routing it directly to Apache on `mini3`.
   - Apache then delegates requests to the appropriate backend machine (like `mini1` or `mini2`).
3. **App Servers (`mini1` & `mini2`)**:
   - `mini1` (192.168.1.21): Hosts Immich and n8n via Docker.
   - `mini2` (192.168.1.22): Currently offline.

## WireGuard Setup
- `mini3` runs WireGuard interfaces (`wg0`, `wg1`).
- The VPS acts as the entry point and routes traffic to `mini3`.
- `mini1` and other devices do NOT use WireGuard; they simply receive local traffic from `mini3`'s Apache proxy.
