import type { Locale } from '@/types';

type Content = {
    hero: { heading: string; body: string };
    'editorial-copy': { body: string };
    'event-essentials': { heading: string };
    countdown: { heading: string };
    cta: { label: string; href: string };
};
export type ComponentKey = keyof Content;
export type EventFacts = { name: string; timezone: string; starts_at: string | null; ends_at: string | null };
export type PublicSection = {
    [K in ComponentKey]: {
        id: string;
        component_key: K;
        variant: string; // Allowed variants come from the server catalog, not another schema.
        content: Content[K];
        locale: Locale;
        fallback: boolean;
        bindings: Partial<EventFacts>;
    }
}[ComponentKey];
export type SectionOf<K extends ComponentKey> = Extract<PublicSection, { component_key: K }>;
export type PublicCatalog = Record<string, { variants: string[]; fields: Record<string, string> }>;
export type PublicCms = {
    event: EventFacts & { slug: string };
    page: { slug: string; title: string; locale: Locale; fallback: boolean };
    sections: PublicSection[];
    catalog: PublicCatalog;
};
