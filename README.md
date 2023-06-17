# VRising Metrics Stats Page
written in PHP

![image](https://github.com/taro222/vrising-metrics/assets/25179142/e38d0d6e-5ad1-45c9-8f66-e0315e00d820)

Live demo can be found [here](https://vr.sequell.de/)
# Config
Edit config.php to your Servername, IP:Port and Metricsurl of your Server
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
