import type { Locale } from '@/types';
import type { Catalog, Section, Snapshot, Translations, TranslationStatus } from './schema';

export const locales: Locale[] = ['en', 'id', 'ar'];
export const statuses: TranslationStatus[] = ['missing', 'draft', 'ready'];
export const emptyTranslations = (): Translations => ({
    en: { status: 'missing', content: null }, id: { status: 'missing', content: null }, ar: { status: 'missing', content: null },
});
export const emptySnapshot = (): Snapshot => ({ schema_version: 3, locales: emptyTranslations(), sections: [], seo: { title: emptyTranslations(), description: emptyTranslations() } });
export function editableSnapshot(snapshot: Snapshot): Snapshot {
    const next = copy(snapshot);
    next.schema_version = 3;
    next.seo ??= { title: emptyTranslations(), description: emptyTranslations() };
    next.sections.forEach(section => { section.media ??= null; });
    return next;
}
export function selectMedia(section: Section, id: string) {
    if (section.media?.file_id === id) return;
    section.media_ids = id ? [id] : [];
    section.media = id ? { file_id: id, focal: { x: 50, y: 50 }, locales: emptyTranslations() } : null;
}
export const copy = <T>(value: T): T => JSON.parse(JSON.stringify(value)) as T;

// getRandomValues also works on the owner's local HTTP hostname (randomUUID requires HTTPS).
function sectionId(): string {
    const bytes = crypto.getRandomValues(new Uint8Array(16));
    bytes[6] = (bytes[6]! & 15) | 64; bytes[8] = (bytes[8]! & 63) | 128;
    const hex = Array.from(bytes, byte => byte.toString(16).padStart(2, '0')).join('');
    return `${hex.slice(0, 8)}-${hex.slice(8, 12)}-${hex.slice(12, 16)}-${hex.slice(16, 20)}-${hex.slice(20)}`;
}

export function setStatus(translations: Translations, locale: Locale, status: TranslationStatus, fields: string[]) {
    const previous = translations[locale].content;
    translations[locale] = { status, content: status === 'missing' ? null : Object.fromEntries(fields.map(field => [field, previous?.[field] ?? ''])) };
}
export function addSection(snapshot: Snapshot, catalog: Catalog, key: string): Snapshot {
    if (!catalog[key] || snapshot.sections.length >= 100) return snapshot;
    const next = copy(snapshot);
    next.sections.push({ id: sectionId(), component_key: key, variant: catalog[key].variants[0]!,
        position: next.sections.length, visible: true, locales: emptyTranslations(), media_ids: [], media: null,
        bindings: key === 'countdown' ? [{ source: 'event', field: 'starts_at' }] : [] });
    return next;
}
export function changeSection(snapshot: Snapshot, index: number, action: 'up' | 'down' | 'duplicate' | 'delete'): Snapshot {
    const next = copy(snapshot);
    const section = next.sections[index];
    if (!section) return snapshot;
    if (action === 'delete') next.sections.splice(index, 1);
    if (action === 'duplicate' && next.sections.length < 100) next.sections.splice(index + 1, 0, { ...copy(section), id: sectionId() });
    const target = action === 'up' ? index - 1 : action === 'down' ? index + 1 : -1;
    if (target >= 0 && target < next.sections.length) [next.sections[index], next.sections[target]] = [next.sections[target]!, section];
    next.sections.forEach((item, position) => { item.position = position; });
    return next;
}

// Inline feedback mirrors common v1 limits; PHP remains the final validator.
export function validField(value: string, type: 'text' | 'url', status: TranslationStatus, limit = 5000): boolean {
    if ([...value].length > limit || (status === 'ready' && !value.trim())) return false;
    const decoded = value.replace(/&#(?:x([a-f0-9]+)|(\d+));?/gi, (_, hex, dec) => String.fromCodePoint(Math.min(parseInt(hex ?? dec, hex ? 16 : 10), 0x10ffff)))
        .replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&colon;/gi, ':');
    if (/[<>\x00-\x08\x0b\x0c\x0e-\x1f\x7f]|(?:javascript|vbscript|data)\s*:|expression\s*\(/iu.test(decoded)) return false;
    if (type === 'text') return true;
    if (value.length > 2048 || /[\s\\"'<>]|%0[ad]|%5c/i.test(value)) return false;
    if (value.startsWith('/') && !value.startsWith('//')) return true;
    try { const url = new URL(value); return url.protocol === 'https:' && !url.username && !url.password; } catch { return false; }
}
export function canPublish(snapshot: Snapshot): boolean {
    return snapshot.locales.en.status === 'ready' && !!snapshot.locales.en.content?.title?.trim();
}
export function sectionFields(section: Section, catalog: Catalog): Record<string, 'text' | 'url'> {
    return catalog[section.component_key]?.fields ?? {};
}

export function localizationReview(snapshot: Snapshot) {
    const status = (translations: Translations | undefined) => Object.fromEntries(locales.map(locale => [locale, translations?.[locale].status ?? 'missing'])) as Record<Locale, TranslationStatus>;
    const rows: { key: 'page' | 'section' | 'media' | 'seoTitle' | 'seoDescription'; section?: number; statuses: Record<Locale, TranslationStatus> }[] = [
        { key: 'page', statuses: status(snapshot.locales) },
    ];
    for (const section of snapshot.sections.filter(section => section.visible)) {
        rows.push({ key: 'section', section: section.position + 1, statuses: status(section.locales) });
        if (section.media) rows.push({ key: 'media', section: section.position + 1, statuses: status(section.media.locales) });
    }
    rows.push({ key: 'seoTitle', statuses: status(snapshot.seo?.title) }, { key: 'seoDescription', statuses: status(snapshot.seo?.description) });
    return rows;
}
