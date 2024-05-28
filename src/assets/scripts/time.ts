export function toUNIX(date: Date): number {
    return Math.floor(date.getTime() / 1000);
}

export function toJSDate(unixDate: number): Date {
    return new Date(unixDate);
}