<script setup lang="ts">
import Navbar from "../components/Navbar.vue";
import NewTaskModal from "../components/NewTaskModal.vue";
import TaskList from "../components/TaskList.vue";
</script>

<script lang="ts">
import $ from "jquery";
import env from "@/assets/scripts/config";

import { createVNode, render } from "vue";
import {
    validateCurrentSession
} from "@/assets/scripts/session";
import { toJSDate } from "@/assets/scripts/time";

export default {
    data() {
        return {
            happyCat: "./images/cat-happy-" + (localStorage.getItem("theme") as string) + ".png",
            dates: [] as Array<Array<Date>>,
            calendarAttr: [] as Array<any>
        }
    },
    created() {
        this.renderDateTime();
        this.renderHighlightedDates();
        validateCurrentSession();

        setInterval(validateCurrentSession, 1000);
        setInterval(this.renderDateTime, 1000);
        setInterval(this.renderHighlightedDates, 1000);
    },
    mounted() {
        this.renderDateTime();
        this.renderHighlightedDates();
        this.renderTodoList();
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
        renderHighlightedDates(): void {
            $.post(
                env.host + "/task.php",
                {
                    action: "highlightable_dates",
                    session: localStorage.getItem("hash") as string
                },
                (data: any)=> {
                    const currentDate: Date = new Date();
                    const currentDayMarker: JQuery<HTMLElement> = $(".id-" + currentDate.getFullYear() +
                        "-" + (currentDate.getMonth() + 1 <= 9 ? "0" : "") + (currentDate.getMonth() + 1) +
                        "-" + (currentDate.getDate() <= 9 ? "0" : "") + currentDate.getDate() +
                        " .vc-day-content");
                    currentDayMarker.addClass("vc-day-current");

                    if(data.status == 1) {
                        this.dates.splice(0, this.dates.length);
                        data.dates.forEach((date: any)=>
                            this.dates.push([toJSDate(parseInt(date[0])), toJSDate(parseInt(date[1]))]));
                        this.updateHighlightedDates();
                    }
                }
            );
        },
        renderTodoList(): void {
            const taskListContainer: HTMLElement | null = document.getElementById("task-list-container"),
                schedListContainer: HTMLElement | null = document.getElementById("sched-list-container");

            if(taskListContainer)
                render(
                    createVNode(
                        TaskList,
                        {apiAction: "todays_unfinished_tasks"}
                    ),
                    taskListContainer
                );

            if(schedListContainer)
                render(
                    createVNode(
                        TaskList,
                        {apiAction: "todays_unfinished_schedules"}
                    ),
                    schedListContainer
                );
        },
        updateHighlightedDates(): void {
            this.calendarAttr.splice(0, this.calendarAttr.length);
            this.dates.forEach(date=> {
                this.calendarAttr.push({
                    highlight: true,
                    dates: [[date[0], date[1]]]
                });
            });
        }
    }
}
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
        <br/>

        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <VDatePicker
                        class="w-100 border-dark date-picker"
                        :attributes="calendarAttr" />
                </div>

                <div class="col-lg-6">
                    <br class="mobile-only" />
                    <h4 class="pb-2">Class Schedule</h4>

                    <div id="class-sched-loading" align="center">
                        <br/>
                        <img src="@/assets/images/cat-loading.gif" width="120" />
                    </div>

                    <div id="sched-no-list" class="d-none" align="center">
                        <br/>
                        <img :src="happyCat" width="120" />
                        <p>No schedules in range!</p>
                    </div>

                    <div id="sched-list-container"></div>
                    <br/><br class="mobile-only" />

                    <h4 class="pb-2">Due Soon</h4>
                    <div id="class-task-loading" align="center">
                        <br/>
                        <img src="@/assets/images/cat-loading.gif" width="120" />
                    </div>

                    <div id="todo-no-list" class="d-none" align="center">
                        <br/>
                        <img :src="happyCat" width="120" />
                        <p>No task in range!</p>
                    </div>

                    <div id="task-list-container"></div>
                </div>
            </div>
        </div>
        <br/>
    </div>

    <br/><br/>
    <NewTaskModal />
</template>