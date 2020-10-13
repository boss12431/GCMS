(function() {
  const version = '1.0.2';

  const offlineResources = [
    '/index.php/css/view/index',
    '/index.php/js/view/index',
    '/skin/fonts/icomoon.ttf',
    '/skin/fonts/icomoon.eot#iefix',
    '/skin/fonts/icomoon.woff',
    '/skin/fonts/icomoon.svg#icomoon'
  ];

  self.addEventListener("install", function(event) {
    event.waitUntil(
      caches
      .open(version + "static")
      .then(function(cache) {
        cache.addAll(offlineResources);
      })
    );
  });

  self.addEventListener("activate", function(event) {
    event.waitUntil(
      caches.keys().then(function(keys) {
        return Promise.all(keys
          .filter(function(key) {
            return key.indexOf(version) !== 0;
          })
          .map(function(key) {
            return caches.delete(key);
          })
        );
      })
    );
  });

  self.addEventListener("fetch", function(event) {
    var request = event.request;
    if (request.method === 'GET' && request.url.startsWith(self.location.origin)) {
      if (request.headers.get("Accept").indexOf("text/html") !== -1) {
        event.respondWith(
          fetch(request)
          .then(function(response) {
            var copy = response.clone();
            caches.open(version + "pages")
              .then(function(cache) {
                cache.put(request, copy);
              });
            return response;
          })
          .catch(function() {
            return caches.match(request)
              .then(function(response) {
                return response;
              });
          })
        );
        return;
      }
      event.respondWith(
        caches.match(request)
        .then(function(response) {
          return response || fetch(request);
        })
      );
    }
  });

  self.addEventListener('push', function(event) {
    console.log('Push message', event);

    var title = 'Push message';

    event.waitUntil(
      self.registration.showNotification(title, {
        'body': 'The Message',
        'icon': 'images/icon.png'
      }));
  });
})();
