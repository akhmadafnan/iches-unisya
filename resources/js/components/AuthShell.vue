<script setup lang="ts">
import LocaleSwitcher from './navigation/LocaleSwitcher.vue';
</script>

<template>
    <main class="flex min-h-svh items-center px-6 py-10">
        <section class="mx-auto w-full max-w-lg border-t-4 border-primary py-8">
            <div class="mb-10 flex flex-wrap items-center justify-between gap-6"><p class="font-brand text-sm font-semibold tracking-widest text-primary" dir="ltr">ICHES UNISYA 2027</p><LocaleSwitcher /></div>
            <slot />
        </section>
    </main>
</template>
