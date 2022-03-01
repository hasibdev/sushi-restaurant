import firebase from "firebase/app";
import "firebase/firebase-messaging";

if ("Notification" in window) {
  Notification.requestPermission().then((permission) => {
    if (permission === "granted") {
      console.log("Notification permission granted.");
    } else {
      Notification.requestPermission();
    }
  });
}

firebase.initializeApp({
  apiKey: "AIzaSyAfHf0uzTKJCb_eQ1sBj2yJyEG-UH0otfE",
  authDomain: "com-scholege-core.firebaseapp.com",
  databaseURL: "https://com-scholege-core.firebaseio.com",
  projectId: "com-scholege-core",
  storageBucket: "com-scholege-core.appspot.com",
  messagingSenderId: "364306157297",
  appId: "1:364306157297:web:df5ef4ca4f9a6a6a666350",
  measurementId: "G-WMBH4KRG1G",
});

export default firebase;
