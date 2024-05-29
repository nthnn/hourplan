export function toUNIX(date: Date): number {
    return Math.floor(date.getTime() / 1000);
}

export function toJSDate(unixDate: number): Date {
    return new Date(unixDate * 1000);
}

export function toShortDate(date: Date): string {
    const monthNames = [
        "January", "February", "March",
        "April", "May", "June",
        "July", "August", "September",
        "October", "November", "December"
    ];

    let hours = date.getHours(),
        minutes = date.getMinutes();
    const period = hours >= 12 ? 'PM' : 'AM';
    const paddedMinutes = minutes < 10 ? '0' + minutes : minutes;

    if(hours > 12)
        hours -= 12;
    else if(hours === 0)
        hours = 12;

    return monthNames[date.getMonth()] + " " +
        date.getDate() + ", " + date.getFullYear() + " " +
        hours + ":" + paddedMinutes + period;
}