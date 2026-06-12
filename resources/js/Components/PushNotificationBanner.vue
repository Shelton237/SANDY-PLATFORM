<script setup>
import { onMounted } from 'vue';
import { usePushNotifications } from '@/Composables/usePushNotifications';

const { isSupported, permission, subscribed, subscribe, checkStatus } = usePushNotifications();

onMounted(checkStatus);
</script>

<template>
    <Transition
        enter-active-class="transition duration-500 ease-out"
        enter-from-class="opacity-0 translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition duration-300 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-4"
    >
        <div
            v-if="isSupported && permission === 'default' && !subscribed"
            class="fixed bottom-4 left-4 right-4 md:left-auto md:right-4 md:w-96 z-50 bg-[#1c3820] border border-[#4ade80]/30 rounded-2xl shadow-2xl p-4 flex items-start gap-3"
        >
            <div class="shrink-0 bg-[#E85A14] rounded-full p-2">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white">Sois le premier informé !</p>
                <p class="text-xs text-white/60 mt-0.5">Active les notifications pour recevoir nos nouveaux jus et promos.</p>
                <button
                    @click="subscribe"
                    class="mt-2 px-4 py-1.5 bg-[#E85A14] hover:bg-[#c44010] text-white text-xs font-semibold rounded-full transition-colors"
                >
                    Activer les notifications
                </button>
            </div>
        </div>
    </Transition>
</template>
