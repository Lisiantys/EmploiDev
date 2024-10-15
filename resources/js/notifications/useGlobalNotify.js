// src/composables/useGlobalNotify.js
import { useNotification } from '@kyvg/vue3-notification';

export function useGlobalNotify() {
    const { notify } = useNotification();
    return notify;
}

// useNotifications : Fournit les méthodes nécessaires pour déclencher des notifications.
// notify : Fonction utilisée pour envoyer des notifications.
