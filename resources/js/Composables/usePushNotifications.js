import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const isSupported = 'serviceWorker' in navigator && 'PushManager' in window;
const permission  = ref(isSupported ? Notification.permission : 'denied');
const subscribed  = ref(false);

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64  = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const raw     = atob(base64);
    return Uint8Array.from([...raw].map((c) => c.charCodeAt(0)));
}

async function subscribe() {
    if (!isSupported) return;

    const perm = await Notification.requestPermission();
    permission.value = perm;
    if (perm !== 'granted') return;

    const registration = await navigator.serviceWorker.ready;
    const vapidKey = window.VAPID_PUBLIC_KEY;

    const pushSub = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(vapidKey),
    });

    const json = pushSub.toJSON();

    await fetch('/push/subscribe', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? '' },
        body: JSON.stringify({
            endpoint: json.endpoint,
            p256dh:   json.keys.p256dh,
            auth:     json.keys.auth,
        }),
    });

    subscribed.value = true;
}

async function unsubscribe() {
    if (!isSupported) return;

    const registration = await navigator.serviceWorker.ready;
    const pushSub      = await registration.pushManager.getSubscription();
    if (!pushSub) return;

    await fetch('/push/unsubscribe', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? '' },
        body: JSON.stringify({ endpoint: pushSub.endpoint }),
    });

    await pushSub.unsubscribe();
    subscribed.value = false;
}

async function checkStatus() {
    if (!isSupported) return;
    permission.value = Notification.permission;
    if (permission.value !== 'granted') return;
    const registration = await navigator.serviceWorker.ready;
    const pushSub      = await registration.pushManager.getSubscription();
    subscribed.value   = !!pushSub;
}

export function usePushNotifications() {
    return { isSupported, permission, subscribed, subscribe, unsubscribe, checkStatus };
}
