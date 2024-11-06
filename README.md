# VRising Metrics Stats Page
written in PHP

![image](https://github.com/user-attachments/assets/740606e0-1268-4ca5-be8b-4847be0ad982)

Live demo can be found [here](https://vr.sequell.de/)
# Config
Edit config.php to your Server IP, Display IP (extern), Port and Metricsurl of your Server
```
$servername = "x5 Rate | Teleport with Items PVE Server";
$serverip = "89.58.36.130";
$metricsurl = "http://sequell.de:9091/metrics";
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
