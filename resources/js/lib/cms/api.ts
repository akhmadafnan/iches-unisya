export class CmsApiError extends Error {
    constructor(public status: number, public fields: Record<string, string[]> = {}) { super(`CMS request failed (${status})`); }
}
export async function cmsRequest<T>(url: string, body?: unknown): Promise<T> {
    const token = document.cookie.split('; ').find(cookie => cookie.startsWith('XSRF-TOKEN='))?.slice('XSRF-TOKEN='.length);
    const response = await fetch(url, {
        method: body === undefined ? 'GET' : 'POST', credentials: 'same-origin', cache: 'no-store', redirect: 'error',
        headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest',
            ...(token ? { 'X-XSRF-TOKEN': decodeURIComponent(token) } : {}) },
        ...(body === undefined ? {} : { body: JSON.stringify(body) }),
    });
    const payload = await response.json().catch(() => null);
    if (!response.ok) throw new CmsApiError(response.status, payload?.errors ?? {});
    if (!payload || typeof payload !== 'object') throw new CmsApiError(503);
    return payload as T;
}
export function cmsErrorKey(error: unknown): string {
    if (!(error instanceof CmsApiError)) return 'cms.errors.network';
    return `cms.errors.${[401, 403, 409, 419, 422, 503].includes(error.status) ? error.status : 'server'}`;
}
