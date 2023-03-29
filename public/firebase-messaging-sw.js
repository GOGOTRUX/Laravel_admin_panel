// Import and configure the Firebase SDK
// These scripts are made available when the app is served or deployed on Firebase Hosting
// If you do not serve/host your project using Firebase Hosting see https://firebase.google.com/docs/web/setup
importScripts('https://www.gstatic.com/firebasejs/5.8.5/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/5.8.5/firebase-messaging.js');
var config = {
    apiKey: "AIzaSyArZ5oTxpSuxQhaaNyJmKK94fPLKynjVPk",
    authDomain: "gogo-trux.firebaseapp.com",
    databaseURL: "https://gogo-trux.firebaseio.com",
    projectId: "gogo-trux",
    storageBucket: "gogo-trux.appspot.com",
    messagingSenderId: "304537554816",
    appId: "1:304537554816:web:1138e829b9c550a8063b65"
  };
firebase.initializeApp(config);
var messaging = firebase.messaging();
// console.log('initializeApp firebase-messaging-sw.js');

messaging.setBackgroundMessageHandler(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload.data);   
  var notiData = JSON.stringify(payload.data);
  var notificationTitle = payload.data.title;
  var notificationOptions = {
    body: payload.data.body,
    vibrate: [3000, 2000, 3000, 2000, 3000],
  };
  return self.registration.showNotification(notificationTitle,
    notificationOptions);
});

