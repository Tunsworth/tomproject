<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

</script>



<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center sm:pt-0 bg-gray-100">
        <div
            class="w-full sm:max-w-md px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>

        <Link v-if="canRegister" 
            :href="route('register')"
            class="ml-4 mt-4 text-sm text-gray-700 dark:text-gray-500 underline"
            >Register</Link
        >
        <Link v-if="canLogin == true" 
            :href="route('register')"
            class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline"
            >Login</Link
        >
    </div>
</template>
