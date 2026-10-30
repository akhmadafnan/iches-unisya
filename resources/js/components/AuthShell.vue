<script setup lang="ts">
import LocaleSwitcher from './navigation/LocaleSwitcher.vue';
</script>

<template>
    <main class="flex min-h-svh flex-col px-6 py-8">
        <section class="mx-auto my-auto w-full max-w-sm">
            <div class="mb-6 flex flex-col gap-4"><p class="font-brand text-sm font-semibold tracking-widest text-primary" dir="ltr">ICHES UNISYA 2027</p><LocaleSwitcher /></div>
            <slot />
        </section>
    </main>
</template>
