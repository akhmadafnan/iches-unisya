export function parseCountdownTarget(value: unknown): number | null {
    if (typeof value !== 'string') return null;
    const match = /^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(?:\.(\d{1,3}))?(Z|[+-]\d{2}:\d{2})$/.exec(value);
    if (!match) return null;
    const [, year, month, day, hour, minute, second, , offset] = match;
    const wall = new Date(0);
    wall.setUTCFullYear(Number(year), Number(month) - 1, Number(day));
    wall.setUTCHours(Number(hour), Number(minute), Number(second), 0);
    if (Number(year) < 1 || wall.getUTCFullYear() !== Number(year)
        || wall.getUTCMonth() !== Number(month) - 1 || wall.getUTCDate() !== Number(day)
        || wall.getUTCHours() !== Number(hour) || wall.getUTCMinutes() !== Number(minute)
        || wall.getUTCSeconds() !== Number(second)) return null;
    if (offset !== 'Z' && (Number(offset!.slice(1, 3)) > 23 || Number(offset!.slice(4)) > 59)) return null;
    const instant = Date.parse(value);
    return Number.isFinite(instant) ? instant : null;
}

export function calculateCountdown(target: unknown, now: number) {
    const instant = parseCountdownTarget(target);
    if (instant === null || !Number.isFinite(now)) return null;
    const total = Math.max(0, Math.floor((instant - now) / 1000));
    return {
        started: now >= instant,
        days: Math.floor(total / 86400), hours: Math.floor(total % 86400 / 3600),
        minutes: Math.floor(total % 3600 / 60), seconds: total % 60,
    };
}
