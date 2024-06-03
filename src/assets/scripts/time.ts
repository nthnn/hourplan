export function toUNIX(date: Date): number {
    return Math.floor(date.getTime() / 1000);
}

export function toJSDate(unixDate: number): Date {
    return new Date(unixDate * 1000);
}

export function toShortTime(date: Date): string {
    let hours: number = date.getHours(),
        minutes: number = date.getMinutes();
    const period: string = hours >= 12 ? "PM" : "AM";
    const paddedMinutes: string = minutes < 10 ?
        "0" + minutes : minutes.toString();

    if(hours > 12)
        hours -= 12;
    else if(hours === 0)
        hours = 12;

    return hours + ":" + paddedMinutes + period;
}

export function toShortDate(date: Date): string {
    return [
        "January", "February", "March",
        "April", "May", "June",
        "July", "August", "September",
        "October", "November", "December"
    ][date.getMonth()] + " " +
        date.getDate() + ", " + date.getFullYear() + " " +
        toShortTime(date);
}