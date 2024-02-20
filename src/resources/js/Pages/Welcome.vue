<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Welcome" />

    <div class="relative flex justify-center items-center min-h-screen bg-cover bg-center object-cover object-center animate-fade-in-fwd" style="background-image: url('/images/welcome-image.jpeg')">
        <div v-if="canLogin" class="fixed top-0 right-0 p-6 text-right">
            <Link v-if="$page.props.auth.user" :href="route('home')" class="font-semibold  text-gray-300 hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">home</Link>

            <template v-else>
                <Link :href="route('login')" class="font-semibold  text-gray-300 hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">Log in</Link>

                <Link v-if="canRegister" :href="route('register')" class="ml-4 font-semibold  text-gray-300 hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">Register</Link>
            </template>
        </div>
        <div class="w-full">
            <h1 class="font-playfair-display text-2xl md:text-5xl text-light text-center animate-tracking-in-expand-fwd md:animate-tracking-in-contract">COACHTECH FREMA</h1>
            <p class="font-great-vibes text-base md:text-2xl text-gray-300 mt-2 md:mt-4 mx-10 text-center animate-tracking-in-expand-fwd md:animate-tracking-in-contract">May you find a treasure at the market.</p>
        </div>
    </div>
</template>
