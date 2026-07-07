# Services Overview

## 1. Pi-hole (DNS & Ad-Blocking)
- **Host**: `mini3` (192.168.1.23)
- **Domain**: `pi-hole.ternis.net`
- **Config location**: `/etc/pihole/pihole.toml`
- **Password**: `Password`
- **Setup**: Serves as the primary DNS server for the home network (configured via Archer MR200 router). It maps all `*.ternis.net` requests locally to `mini3` to ensure traffic flows through the Apache reverse proxy.

## 2. Apache (Reverse Proxy & SSL Termination)
- **Host**: `mini3` (192.168.1.23)
- **Config location**: `/etc/apache2/sites-enabled/ternis-proxy.conf`
- **Setup**: Receives all incoming HTTP/HTTPS traffic for `*.ternis.net`. It terminates SSL using Let's Encrypt (`certbot --apache`) and proxies requests to backend servers.

## 3. Immich (Photo Backup)
- **Host**: `mini1` (192.168.1.21)
- **Domain**: `immich.ternis.net`
- **Local Port**: `2283`
- **Setup**: Deployed via Docker. Apache on `mini3` proxies `immich.ternis.net` to `http://192.168.1.21:2283`.

## 4. n8n (Workflow Automation)
- **Host**: `mini1` (192.168.1.21)
- **Domain**: `n8n.ternis.net`
- **Local Port**: `5678`
- **Setup**: Deployed via Docker. Apache on `mini3` proxies `n8n.ternis.net` to `http://192.168.1.21:5678`.

## 5. Gitea (Git Repo Manager)
- **Host**: `mini1` (192.168.1.21)
- **Domain**: `git.ternis.net`
- **Local Port**: `3000` (SSH on `2222`)
- **Setup**: Deployed via Docker. Apache on `mini3` proxies `git.ternis.net` to `http://192.168.1.21:3000`.


## 7. Dashboard Website
- **Host**: `mini3` (192.168.1.23)
- **Domain**: `www.ternis.net`
- **Location**: `/home/fabian/homelab/website`
- **Setup**: Served natively by Apache on `mini3`. Uses PHP and a SQLite database (`stats.db`) to display live metrics (Pi-hole ads blocked, Immich photos, etc.). A cronjob running every 5 minutes (`update_stats.php`) automatically pulls and updates the data.

## Current Status & Known Issues
- `mini1` had a broken direct IPv4 internet connection via the router. This was fixed by configuring NAT (IP forwarding and Masquerade) on `mini3` (`192.168.1.23`) and setting `mini3` as the default IPv4 gateway for `mini1`. `mini1` now routes its internet traffic through `mini3`'s WireGuard VPN tunnel.
- `mini2` is currently offline.
