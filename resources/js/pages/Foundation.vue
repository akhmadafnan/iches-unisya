<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineProps<{ task: string }>();
</script>

<template>
    <Head title="Application foundation" />
    <main class="flex min-h-svh items-center bg-stone-50 px-6 py-12 text-stone-900 sm:px-12">
        <section aria-labelledby="foundation-title" class="mx-auto w-full max-w-3xl border-t-4 border-teal-800 py-8 sm:py-12">
            <p class="text-sm font-semibold tracking-widest text-teal-800">ICHES UNISYA 2027</p>
            <h1 id="foundation-title" class="mt-6 font-serif text-4xl leading-tight sm:text-6xl">Application foundation</h1>
            <p class="mt-6 max-w-xl text-lg leading-relaxed text-stone-600">
                The application foundation is running. This is a development preview;
                conference information and services are still being prepared.
            </p>
            <p class="mt-10 border-t border-stone-300 pt-4 text-sm text-stone-600">Foundation task · {{ task }}</p>
        </section>
    </main>
</template>
