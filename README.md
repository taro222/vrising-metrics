# VRising Metrics Stats Page
written in PHP

![image](https://github.com/user-attachments/assets/740606e0-1268-4ca5-be8b-4847be0ad982)

Live demo can be found [here](https://vr.sequell.de/)
# Config
Edit config.php to your Server IP, Display IP (extern), Port and Metricsurl of your Server
```
$serverip = "localhost";			// Server IP for Pings
$display_ip = "176.9.31.121";			// Server IP (extern)
$serverport = 9877;				// Port
$metricsurl = "http://localhost:9091/metrics";	// Metrics URL
```
# Metricsurl
In ServerHostSettings.json the outgoing port is configured
```
{
  "Name": "VRising Server",
  ...
  "API": {
    "Enabled": true,
    "BindPort": 9091
  },
  ...
}
```
