# IPv6 Subnet Allocation Plan

**Base Prefix:** `2a09:3f00:2000::/44`
**Prefix Length:** /44
**Total /48 Subnets:** 16

## 1. /48 Subnet Breakdown
The /44 prefix contains the following 16 /48 subnets:
1. `2a09:3f00:2000::/48` (External)
2. `2a09:3f00:2001::/48` (External)
3. `2a09:3f00:2002::/48` (External)
4. `2a09:3f00:2003::/48` (External)
5. **`2a09:3f00:2004::/48` (Local Network - Primary)**
6. **`2a09:3f00:2005::/48` (Local Network - Expansion 1)**
7. **`2a09:3f00:2006::/48` (Local Network - Expansion 2)**
8. **`2a09:3f00:2007::/48` (Local Network - Expansion 3)**
9. `2a09:3f00:2008::/48` (External)
10. `2a09:3f00:2009::/48` (External)
11. `2a09:3f00:200a::/48` (External)
12. `2a09:3f00:200b::/48` (External)
13. `2a09:3f00:200c::/48` (External)
14. `2a09:3f00:200d::/48` (External)
15. `2a09:3f00:200e::/48` (External)
16. `2a09:3f00:200f::/48` (External)

## 2. Local Network Allocation (5th to 8th Subnet)
We allocate the 5th through 8th subnets for the local network. These four /48s can be aggregated into a single `/46` route if needed:
**`2a09:3f00:2004::/46`**

For the initial setup, we will use the 5th subnet (`2a09:3f00:2004::/48`) to carve out `/64` networks for your local VLANs:
- **VLAN 1 (Management):** `2a09:3f00:2004:0000::/64`
- **VLAN 10 (Main/Clients):** `2a09:3f00:2004:0010::/64`
- **VLAN 20 (IoT):** `2a09:3f00:2004:0020::/64`
- **VLAN 30 (Guest):** `2a09:3f00:2004:0030::/64`

## 3. LTE Router Routing Strategy
Because your local router connects via LTE, the mobile provider will not natively route your custom `2a09:3f00:2000::/44` prefix to your mobile connection. 

**Solution: VPN Tunnel (WireGuard)**
1. **VPS / Gateway:** A server (e.g., VPS in a data center) receives the `/44` routing from the provider via BGP or static routing.
2. **Tunnel Interface:** Establish a WireGuard tunnel between the VPS and your LTE Router.
3. **Routing:** The VPS routes the local network blocks (`2a09:3f00:2004::/46`) over the WireGuard tunnel to the LTE Router's tunnel IP.
4. **MTU / MSS Clamping:** LTE overhead + WireGuard overhead requires adjusting the MTU (usually to 1420 or 1360 for WireGuard over LTE) and configuring TCP MSS Clamping on the LTE router to prevent packet loss and hanging connections.

## 4. Next Steps to Begin Implementation
1. **Configure Gateway/VPS:** Set up WireGuard on the endpoint where the `/44` terminates.
2. **Configure LTE Router:** Connect the LTE router to the WireGuard tunnel and establish connectivity.
3. **Configure LAN Interfaces:** Assign the `/64` subnets to the local interfaces/VLANs on the LTE router.
4. **Router Advertisements (SLAAC/DHCPv6):** Enable RAs on the local interfaces so clients receive their public IPv6 addresses.
5. **Firewall Rules:** Set up IPv6 firewall rules on the LTE router to permit outbound traffic and block unestablished inbound traffic.
