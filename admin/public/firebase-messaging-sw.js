importScripts("https://www.gstatic.com/firebasejs/8.4.1/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.4.1/firebase-messaging.js");

var firebaseConfig = {
  apiKey: "AIzaSyAfHf0uzTKJCb_eQ1sBj2yJyEG-UH0otfE",
  authDomain: "com-scholege-core.firebaseapp.com",
  databaseURL: "https://com-scholege-core.firebaseio.com",
  projectId: "com-scholege-core",
  storageBucket: "com-scholege-core.appspot.com",
  messagingSenderId: "364306157297",
  appId: "1:364306157297:web:df5ef4ca4f9a6a6a666350",
  measurementId: "G-WMBH4KRG1G",
};

const fireapp = firebase.initializeApp(firebaseConfig);

fireapp.messaging().getToken({
  vapidKey:
    "BLgjpVljeGHgj_2guCLDKzSfBtfm8KLNzonTkRTTTX1zNyiEbi1sLy8d39nLk-719_OyNFxHVXxg04xLZDz1kFg",
});
