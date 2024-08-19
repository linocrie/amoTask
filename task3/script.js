(async function() {
  let response = await fetch('https://ipapi.co/json/');
  let data = await response.json();

  let deviceInfo = navigator.userAgent;

  fetch('http://visitstats.infinityfreeapp.com/track_visit.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
          ip: data.ip,
          city: data.city,
          device: deviceInfo
      })
  });
})();
