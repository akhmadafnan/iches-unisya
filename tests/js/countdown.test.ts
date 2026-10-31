import { test } from 'node:test';
import assert from 'node:assert/strict';
import { calculateCountdown, parseCountdownTarget } from '../../resources/js/lib/public-cms/countdown.ts';

// Synthetic clock values, unrelated to the official conference schedule.
const target = '2032-06-18T14:23:11+07:00';
const instant = Date.parse(target);
for (const [label, seconds, days, hours, minutes, remainder] of [
    ['30 days', 2592000, 30, 0, 0, 0],
    ['same day', 14706, 0, 4, 5, 6],
    ['day transition before', 86401, 1, 0, 0, 1],
    ['day transition after', 86399, 0, 23, 59, 59],
    ['hour boundary', 3600, 0, 1, 0, 0],
    ['hour transition', 3599, 0, 0, 59, 59],
    ['minute boundary', 60, 0, 0, 1, 0],
    ['minute transition', 59, 0, 0, 0, 59],
    ['one second before', 1, 0, 0, 0, 1],
] as const) {
    test(label, () => assert.deepEqual(calculateCountdown(target, instant - seconds * 1000), {
        started: false, days, hours, minutes, seconds: remainder,
    }));
}
test('subsecond before start is not started; exact and past boundaries clamp to zero', () => {
    assert.deepEqual(calculateCountdown(target, instant - 1), { started: false, days: 0, hours: 0, minutes: 0, seconds: 0 });
    for (const now of [instant, instant + 1, instant + 500000]) {
        assert.deepEqual(calculateCountdown(target, now), { started: true, days: 0, hours: 0, minutes: 0, seconds: 0 });
    }
});
for (const value of [null, undefined, '', 'bad', 'Infinity', Infinity, NaN, {}, '2032-06-18',
    '2032-06-18T14:23:11', '2032-02-30T00:00:00Z', '2031-02-29T00:00:00Z',
    '2032-13-01T00:00:00Z', '2032-00-01T00:00:00Z', '2032-01-00T00:00:00Z',
    '2032-01-01T24:00:00Z', '2032-01-01T00:60:00Z', '2032-01-01T00:00:60Z',
    '0000-01-01T00:00:00Z', '2032-01-01T00:00:00+24:00', '2032-01-01T00:00:00+07:60',
    '2032-01-01T00:00:00Z trailing', ' 2032-01-01T00:00:00Z']) {
    test(`reject invalid target ${String(value)}`, () => {
        assert.equal(parseCountdownTarget(value), null);
        assert.equal(calculateCountdown(value, instant), null);
    });
}
test('valid leap day, fractional seconds and nonfinite clocks', () => {
    assert.equal(parseCountdownTarget('2032-02-29T00:00:00.123Z'), Date.parse('2032-02-29T00:00:00.123Z'));
    for (const now of [NaN, Infinity, -Infinity]) assert.equal(calculateCountdown(target, now), null);
});
test('Jakarta and UTC are equivalent regardless of viewer timezone', () => {
    const previous = process.env.TZ;
    try {
        for (const zone of ['America/New_York', 'Asia/Tokyo', 'UTC']) {
            process.env.TZ = zone;
            assert.equal(parseCountdownTarget(target), parseCountdownTarget('2032-06-18T07:23:11Z'));
            assert.deepEqual(calculateCountdown(target, instant - 90000), calculateCountdown('2032-06-18T07:23:11Z', instant - 90000));
        }
    } finally { if (previous === undefined) delete process.env.TZ; else process.env.TZ = previous; }
});
test('replacement target immediately uses the new instant', () => {
    const now = instant - 1000;
    assert.equal(calculateCountdown(target, now)?.seconds, 1);
    assert.equal(calculateCountdown('2032-06-19T07:23:11Z', now)?.days, 1);
    assert.equal(calculateCountdown(null, now), null);
});
test('clock jumps and resumed clocks recalculate absolute remaining time', () => {
    for (const delta of [10000, 2000, 900000, 1000, -5000]) {
        const result = calculateCountdown(target, instant - delta);
        assert.equal(result?.seconds, Math.max(0, Math.floor(delta / 1000)) % 60);
        assert.equal(result?.started, delta <= 0);
    }
});
