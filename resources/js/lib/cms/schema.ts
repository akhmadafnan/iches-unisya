import type { DashboardProps, Locale } from '@/types';

export type TranslationStatus = 'missing' | 'draft' | 'ready';
export interface Translation { status: TranslationStatus; content: Record<string, string> | null }
export type Translations = Record<Locale, Translation>;
export interface ComponentDefinition { variants: string[]; fields: Record<string, 'text' | 'url'> }
export type Catalog = Record<string, ComponentDefinition>;
export interface Section {
    id: string; component_key: string; variant: string; position: number; visible: boolean;
    locales: Translations; media_ids: string[]; bindings: { source: 'event'; field: string }[];
}
export interface Snapshot { schema_version: 1; locales: Translations; sections: Section[] }
export interface Revision { id: string; version: number; created_at: string | null }
export interface Audit { id: string; action: 'publish' | 'restore'; from_revision_id: string | null; to_revision_id: string; occurred_at: string | null }
export interface CmsPage { id: string; slug: string; draft_revision_id: string | null; published_revision_id: string | null }
export interface PageRow { id: string; slug: string; draft_version: number | null; published_version: number | null; updated_at: string | null }
export interface EventSchedule { starts_at: string | null; local_start: string | null; timezone: string; timezones: string[] }
export interface PagesProps extends DashboardProps {
    eventSchedule: EventSchedule;
    cmsEvent: { id: string; name: string }; schemaAvailable: boolean; search: string;
    pages: { data: PageRow[]; current_page: number; last_page: number; prev_page_url: string | null; next_page_url: string | null } | null;
}
export interface EditorProps extends DashboardProps {
    cmsEvent: { id: string; name: string }; cmsPage: CmsPage; snapshot: Snapshot | null;
    catalog: Catalog; revisions: Revision[]; audit: Audit[];
}
export interface RevisionResponse { page_id: string; revision_id: string; version: number; snapshot?: Snapshot }
