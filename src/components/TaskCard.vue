<script lang="ts">
import $ from "jquery";
import env from "@/assets/scripts/config";
import { toJSDate, toShortDate, toShortTime } from '@/assets/scripts/time';

export default {
    data() {
        return {
            taskId: "task-" + Math.round(Math.random() * 100000),
            startJSDate: toJSDate(this.startDate as number),
            endJSDate: toJSDate(this.endDate as number),
            categoryArray: atob(this.categories as string).split(",")
        }
    },
    props: {
        id: {type: Number},
        finished: {type: Boolean},
        large: {type: Boolean},
        title: {type: String},
        desc: {type: String},
        color: {type: String},
        emotion: {type: String},
        startDate: {type: Number},
        endDate: {type: Number},
        categories: {type: String}
    },
    computed: {
        shortStartDate(): string {
            return toShortDate(this.startJSDate);
        },
        shortEndDate(): string {
            return toShortDate(this.endJSDate);
        },
        startShortTime(): string {
            return toShortTime(this.startJSDate);
        },
        endShortTime(): string {
            return toShortTime(this.endJSDate);
        }
    },
    methods: {
        isSameDayStartEnd(): boolean {
            return (this.startJSDate.getDate() == this.endJSDate.getDate()) &&
                (this.startJSDate.getMonth() == this.endJSDate.getMonth()) &&
                (this.startJSDate.getFullYear() == this.endJSDate.getFullYear());
        },
        getStartDateDay(): string {
            return ["MON", "TUES", "WED", "THURS", "FRI", "SAT", "SUN"][this.startJSDate.getDay()];
        },
        getStartDateMonth(): string {
            return [
                "January", "February", "March",
                "April", "May", "June", "July",
                "August", "September", "October",
                "November", "December"
            ][this.startJSDate.getMonth() + 1];
        },
        getEndDateDay(): string {
            return ["MON", "TUES", "WED", "THURS", "FRI", "SAT", "SUN"][this.endJSDate.getDay()];
        },
        getEndDateMonth(): string {
            return [
                "January", "February", "March",
                "April", "May", "June", "July",
                "August", "September", "October",
                "November", "December"
            ][this.endJSDate.getMonth() + 1];
        },
        onMarked(): void {
            $.post(
                env.host + "/task.php",
                {
                    action: "mark_task",
                    id: this.id,
                    session: localStorage.getItem("hash") as string,
                    finished: $("#" + this.taskId).is(":checked") ? "1" : "0"
                },
                (_: any)=> {
                    window.location.reload();
                }
            );
        }
    }
};
</script>

<template>
    <div class="w-100 rounded border-none shadow p-1" :style="{ backgroundColor: color}">
        <div v-if="!large">
            <div class="row">
                <div class="col-4 col-lg-3">
                    <div v-if="emotion === 'happy'">
                        <img src="@/assets/images/cat-happy.png" class="w-100 p-2" />
                    </div>
                    <div v-else-if="emotion === 'delighted'">
                        <img src="@/assets/images/cat-delighted.png" class="w-100 p-2" />
                    </div>
                    <div v-else-if="emotion === 'crying'">
                        <img src="@/assets/images/cat-crying.png" class="w-100 p-2" />
                    </div>
                </div>

                <div class="col-6 col-lg-7">
                    <div class="p-2">
                        <h3 class="text-lato">{{ title }}</h3>
                        <p class="text-lato">
                            {{ desc }}<br/>
                            <small class="text-muted text-lato desktop-only">{{ shortStartDate }} &mdash; {{ shortEndDate }}</small>
                        </p>
                    </div>
                </div>

                <div class="col-2">
                    <div class="d-flex align-items-center h-100 w-100 m-0 p-0">
                        <input type="checkbox"
                            class="form-check-input" value=""
                            :checked=finished :id=taskId :disabled=finished
                            @click="onMarked" />
                    </div>
                </div>
            </div>

            <small class="text-lato px-2 mb-2 mobile-only text-muted">{{ shortStartDate }} &mdash; {{ shortEndDate }}</small>
        </div>

        <div v-else>
            <div class="row">
                <div class="col-lg-3 desktop-only" align="center">
                    <div class="row">
                        <div class="col-6">
                            <div class="py-4" align="center">
                                <h5 class="text-lato">{{ getStartDateDay() }}</h5>
                                <h1 class="text-lato fw-bold">{{ startJSDate.getDate() }}</h1>
                                <h6 class="text-lato">{{ getStartDateMonth() }}</h6>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="py-4 d-flex flex-column justify-content-center align-items-center h-100 w-100" align="center">
                                <div v-if="!isSameDayStartEnd()">
                                    <h5 class="text-lato">{{ getEndDateDay() }}</h5>
                                    <h1 class="text-lato fw-bold">{{ endJSDate.getDate() }}</h1>
                                    <h6 class="text-lato">{{ getEndDateMonth() }}</h6>
                                </div>
                                <div v-else>
                                    <p class="text-lato">{{ startShortTime }}</p>
                                    <p class="text-lato">{{ endShortTime }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-4 col-lg-2">
                    <div v-if="emotion === 'happy'">
                        <img src="@/assets/images/cat-happy.png" class="w-100" />
                    </div>
                    <div v-else-if="emotion === 'delighted'">
                        <img src="@/assets/images/cat-delighted.png" class="w-100" />
                    </div>
                    <div v-else-if="emotion === 'crying'">
                        <img src="@/assets/images/cat-crying.png" class="w-100" />
                    </div>
                </div>

                <div class="col-5 col-lg-6">
                    <div class="py-1 py-lg-3">
                        <h3 class="text-lato">{{ title }}</h3>
                        <p v-for="(category, index) in categoryArray" :key="index" class="badge clr-primary text-lato m-0 me-1">{{ category }}</p>
                        <p class="text-lato">{{ desc }}</p>
                    </div>
                </div>

                <div class="col-2 col-lg-1">
                    <div class="d-flex align-items-center h-100 w-100 m-0 p-0">
                        <input type="checkbox"
                            class="form-check-input" value=""
                            :checked=finished :id=taskId :disabled=finished
                            @click="onMarked" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br/>
</template>

<style scoped>
input[type="checkbox"] {
    width: 28px !important;
    height: 28px !important;
    outline: none !important;
    border: none !important;
    margin-top: -12px;
}

input[type="checkbox"]:checked {
    background-color: #1a172a !important;
}
</style>