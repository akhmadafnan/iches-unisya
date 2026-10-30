import type { Component } from 'vue';
import HeroSection from '@/components/public/sections/HeroSection.vue';
import EditorialCopySection from '@/components/public/sections/EditorialCopySection.vue';
import EventEssentialsSection from '@/components/public/sections/EventEssentialsSection.vue';
import CountdownSection from '@/components/public/sections/CountdownSection.vue';
import CtaSection from '@/components/public/sections/CtaSection.vue';
import type { ComponentKey } from './types';

export const sectionRegistry = {
    hero: HeroSection,
    'editorial-copy': EditorialCopySection,
    'event-essentials': EventEssentialsSection,
    countdown: CountdownSection,
    cta: CtaSection,
} satisfies Record<ComponentKey, Component>;
