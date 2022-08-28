<?php

namespace Uasoft\Badaso\Helpers\Firebase;

use Illuminate\Support\Facades\File;

class FirebasePublishFile
{
    public function getContentFirebaseMessagingSwJs()
    {
        $VITE_FIREBASE_API_KEY = \env('VITE_FIREBASE_API_KEY');
        $VITE_FIREBASE_AUTH_DOMAIN = \env('VITE_FIREBASE_AUTH_DOMAIN');
        $VITE_FIREBASE_PROJECT_ID = \env('VITE_FIREBASE_PROJECT_ID');
        $VITE_FIREBASE_STORAGE_BUCKET = \env('VITE_FIREBASE_STORAGE_BUCKET');
        $VITE_FIREBASE_MESSAGE_SEENDER = \env('VITE_FIREBASE_MESSAGE_SEENDER');
        $VITE_FIREBASE_APP_ID = \env('VITE_FIREBASE_APP_ID');
        $VITE_FIREBASE_MEASUREMENT_ID = \env('VITE_FIREBASE_MEASUREMENT_ID');

        $script_content = <<<JAVASCRIPT
        let cacheName = "app-badaso-cache";
        let broadcastChannelName = "sw-badaso-messages";
        const BROADCAST_TYPE_ONLINE_STATUS = "BROADCAST_TYPE_ONLINE_STATUS";
        const BROADCAST_TYPE_FIREBASE_MESSAGE = "BROADCAST_TYPE_FIREBASE_MESSAGE";
        let broadcastChannel = null;
        let broadcastMessageFormat = (type, data, message, errors) => {};

        try {
            let broadcastChannel = new BroadcastChannel(broadcastChannelName);
            let broadcastMessageFormat = (type, data, message, errors) => {
            broadcastChannel.postMessage({ type, data, message, errors });
            };
        } catch (error) {
            console.log('Error broadcast channel ', error)
        }

        try {
        self.addEventListener("install", (e) => {
            console.log("Worker Installed");
        });
        self.addEventListener("active", (e) => {
            e.waitUntil(self.clients.claim());
            e.waitUntil(
            caches.keys().then((cacheNames) =>
                Promise.all(
                cacheNames.map((cache, index) => {
                    if (cache !== cacheName) {
                    caches.delete(cache);
                    }
                })
                )
            )
            );
        });
        self.addEventListener("fetch", function (event) {
            event.respondWith(
            caches.open(cacheName).then(function (cache) {
                return fetch(event.request)
                .then((networkResponse) => {
                    if (event.request.method == "GET") {
                    cache.put(event.request, networkResponse.clone());
                    }
                    return networkResponse;
                })
                .catch((error) => {
                    return caches.match(event.request).then((response) => {
                    return response;
                    });
                });
            })
            );
        });
        } catch (error) {}
        try {
            importScripts("https://www.gstatic.com/firebasejs/8.2.7/firebase-app.js");
            importScripts(
                "https://www.gstatic.com/firebasejs/8.2.7/firebase-messaging.js"
            );
            var firebaseConfig = {
                apiKey: "$VITE_FIREBASE_API_KEY",
                authDomain: "$VITE_FIREBASE_AUTH_DOMAIN",
                projectId: "$VITE_FIREBASE_PROJECT_ID",
                storageBucket: "$VITE_FIREBASE_STORAGE_BUCKET",
                messagingSenderId: "$VITE_FIREBASE_MESSAGE_SEENDER",
                appId: "$VITE_FIREBASE_APP_ID",
                measurementId: "$VITE_FIREBASE_MEASUREMENT_ID",
            };
            const app = firebase.initializeApp(firebaseConfig);
            const messaging = firebase.messaging();
            messaging.onBackgroundMessage((payload) => {
                try {
                    broadcastMessageFormat(
                        BROADCAST_TYPE_FIREBASE_MESSAGE,
                        payload,
                        null,
                        null
                    );
                } catch (error) {
                    console.log('Error broadcast channel', error)
                }
            });
        } catch (error) {}
        JAVASCRIPT;

        return $script_content;
    }

    public static function publishNow()
    {
        $firebase_publish_file = new self();
        $path = public_path() . '/firebase-messaging-sw.js';
        if (File::exists($path)) {
            File::delete($path);
        }

        File::put($path, $firebase_publish_file->getContentFirebaseMessagingSwJs());
    }
}
