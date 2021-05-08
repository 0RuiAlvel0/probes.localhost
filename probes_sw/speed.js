var speedTest = require('speedtest-net');
var test = speedTest({maxTime: 15000, serverId: '1849'});

test.on('data', data => {
  console.dir(data);
});

test.on('error', err => {
  console.error(err);
});
