// Initialize Firebase
/*var config = {
    apiKey: "AIzaSyDGjNs0OAksxMp77450wj_56seTWtZvI2I",
    authDomain: "gogo-trux.firebaseapp.com",
    databaseURL: "https://gogo-trux.firebaseio.com",
    projectId: "gogo-trux",
    storageBucket: "gogo-trux.appspot.com",
    messagingSenderId: "304537554816"
};*/
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

const messaging = firebase.messaging();
//messaging.requestPermission().then(function() {
Notification.requestPermission().then((permission) => {
  console.log('Notification permission granted.');
  return messaging.getToken();
}).then(function(token){
  console.log('token here',token);
  window.localStorage.setItem('notification_token', token);
}).catch(function(err) {
  //alert('Please allow notification permission');
  console.log('Unable to get permission to notify.', err.code);
});

messaging.onMessage((payload) => {
  console.log('Message received. ', payload);
  window.localStorage.removeItem('notificationdata');
  window.localStorage.setItem('notificationdata', JSON.stringify(payload));
  /*$('#notifTitle').empty();
  $('#notifMsg').empty();
  var notifTitlediv = document.getElementById('notifTitle');
  notifTitlediv.innerHTML += payload.notification.title;
  var notifMsgdiv = document.getElementById('notifMsg');
  notifMsgdiv.innerHTML += payload.notification.body;

  var notification = new Notification(payload.notification.title,{body:payload.notification.body,icon:payload.notification.icon,click_action:payload.notification.click_action});
  notification.onclick = function(event) {
    event.preventDefault(); // prevent the browser from focusing the Notification's tab
    window.open(payload.notification.click_action, '_blank');
  }*/
});


