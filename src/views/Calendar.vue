<script setup lang="ts">
import Navbar from "../components/Navbar.vue";
import NewTaskModal from "../components/NewTaskModal.vue";
</script>

<script lang="ts">
import $ from "jquery";
import env from "@/assets/scripts/config";
import md5 from "md5";

import { ref, type Ref } from "vue";
import { VCalendar as VCalendarVuetify } from "vuetify/labs/VCalendar";

import {
    validateCurrentSession
} from "@/assets/scripts/session";
import { toJSDate } from "@/assets/scripts/time";

const prevCalendarHash: Ref<string> = ref("");
const calendarData: Ref<any> = ref({
    today: [new Date()],
    events: []
});

export default {
    data() {
        return calendarData.value;
    },
    created() {
        validateCurrentSession();
        setInterval(validateCurrentSession, 1000);

        this.renderDateTime();
        setInterval(this.renderDateTime, 1000);

        this.renderCalendarData();
        setInterval(this.renderCalendarData, 2000);
    },
    methods: {
        renderDateTime(): void {
            $("#date-elem").html(
                new Date().toLocaleDateString(
                    'en-US',
                    {
                        weekday: 'short',
                        month: 'long',
                        day: 'numeric',
                        year: 'numeric'
                    } as any
                )
            );
        },
        renderCalendarData(): void {
            $.post(
                env.host + "/task.php",
                {
                    action: "calendar_data",
                    session: localStorage.getItem("hash") as string
                },
                (data: any)=> {
                    const dataHash: string = md5(data.toString());
                    if(prevCalendarHash.value == dataHash)
                        return;
                    prevCalendarHash.value = dataHash;

                    calendarData.value.events = [];
                    for(let i of data)
                        calendarData.value.events.push({
                            title: atob(i.title),
                            start: toJSDate(i.start),
                            end: toJSDate(i.end)
                        })
                }
            );
        }
    }
};
</script>

<template>
    <Navbar />

    <div class="main-content">
        <div class="row mt-4 px-2 px-lg-4">
            <div class="col-7">
                <div class="d-inline-block w-auto px-2 px-lg-4 py-2 outlined-secondary brdr-secondary" align="center">
                    <p id="date-elem" class="text-lato m-0"></p>
                </div>
            </div>

            <div class="col-5">
                <div class="w-100" align="right">
                    <button
                        class="w-auto btn outlined-secondary brdr-secondary text-dark text-lato w-100 w-lg-50 py-2"
                        data-bs-toggle="modal" data-bs-target="#newTaskModal">Add New Task</button>
                </div>
            </div>
        </div>
        <br class="desktop-only" />

        <div class="container">
            <VCalendarVuetify
                ref="calendar"
                v-model="today"
                :events="events"
                type="week">
            </VCalendarVuetify>
        </div>
        <br/>
    </div>

    <br/><br/>
    <NewTaskModal />
</template>