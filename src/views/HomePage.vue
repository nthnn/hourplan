<script setup lang="ts">
import $ from "jquery";
import env from "@/assets/scripts/config";
import Navbar from "../components/Navbar.vue";
import NewTaskModal from "../components/NewTaskModal.vue";
import TaskList from "../components/TaskList.vue";

import { ref, type Ref } from "vue";
import {
    validateCurrentSession
} from "@/assets/scripts/session";
import { toJSDate } from "@/assets/scripts/time";

validateCurrentSession();
setInterval(()=> validateCurrentSession(), 1000);

const dates: Ref<Date[]> = ref([]);
const calendarAttr: Ref<{}> = ref({});

function updateHighlightedDates(): void {
    calendarAttr.value = dates.value.map(date=> ({
        highlight: true,
        dates: date
    }));
}

dates.value.push(new Date());

setInterval(()=> {
    $.post(
        env.host + "/task.php",
        {
            action: "marked_dates_current_month",
            session: localStorage.getItem("hash") as string
        },
        (data: any)=> {
            if(data.status == 1) {
                data.dates.forEach((date: string)=>
                    dates.value.push(toJSDate(parseInt(date))));
                updateHighlightedDates();
            }
        }
    );
}, 300);

setTimeout(()=> {
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

    const currentDate: Date = new Date();
    const currentDayMarker: JQuery<HTMLElement> = $(".id-" + currentDate.getFullYear() +
        "-" + (currentDate.getMonth() + 1 <= 9 ? "0" : "") + (currentDate.getMonth() + 1) +
        "-" + (currentDate.getDate() <= 9 ? "0" : "") + currentDate.getDate() +
        " .vc-day-content");

    currentDayMarker.css({
        "outline-color": "#ebb797",
        "border-color": "#ebb797",
        "background-color": "#ebb797",
        "color": "#1a172a"
    });
}, 200);
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
                        class="w-auto btn outlined-secondary brdr-secondary text-dark text-lato w-100 py-2"
                        data-bs-toggle="modal" data-bs-target="#newTaskModal">Add New Task</button>
                </div>
            </div>
        </div>
        <br/>

        <div class="px-2 px-lg-4">
            <VDatePicker
                id="date-picker"
                mode="multiple"
                class="w-100 border-dark date-picker"
                :attributes="calendarAttr" />
        </div>
        <br/><br class="desktop-only" />

        <div class="row px-2 px-lg-4">
            <div class="col-lg-6">
                <h4 class="pb-2">Class Schedule</h4>

                <div id="class-sched-loading" align="center">
                    <br/>
                    <img src="@/assets/images/cat-loading.gif" width="150" />
                </div>

                <TaskList apiAction="todays_unfinished_schedules" />
            </div>

            <div class="col-lg-6">
                <br class="mobile-only" />
                <h4 class="pb-2">Due Soon</h4>

                <div id="class-task-loading" align="center">
                    <br/>
                    <img src="@/assets/images/cat-loading.gif" width="150" />
                </div>

                <TaskList apiAction="todays_unfinished_tasks" />
            </div>
        </div>
    </div>

    <br/><br/>
    <NewTaskModal />
</template>