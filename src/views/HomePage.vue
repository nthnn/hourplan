<script setup lang="ts">
import $ from "jquery";
import Navbar from "../components/Navbar.vue";
import NewTaskModal from "../components/NewTaskModal.vue";

import { useRouter } from "vue-router";
import { nextTick, ref } from "vue";
import {
    validateCurrentSession
} from "@/assets/scripts/session";

const router = useRouter();
validateCurrentSession(router);
setInterval(()=> validateCurrentSession(router), 1000);

const dates = ref([]);
const calendarAttr = ref({});

function updateHighlightedDates() {
    calendarAttr.value = dates.value.map(date=> ({
        highlight: true,
        dates: date
    }));
}

dates.value.push(Date.now());
updateHighlightedDates();

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
}, 200);
</script>

<template>
    <Navbar />

    <div class="main-content">
        <div class="row mt-4 px-2 px-lg-4">
            <div class="col-6">
                <div class="d-inline-block btn w-auto px-2 px-lg-4 py-2 outlined-secondary brdr-secondary" align="center">
                    <p id="date-elem" class="text-lato m-0"></p>
                </div>
            </div>

            <div class="col-6">
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
                <h4>Class Schedule</h4>

                <div id="class-sched-loading" align="center">
                    <br/>
                    <img src="@/assets/images/cat-loading.gif" width="150" />
                </div>
            </div>

            <div class="col-lg-6">
                <br class="mobile-only" />
                <h4>Due Soon</h4>

                <div id="class-sched-loading" align="center">
                    <br/>
                    <img src="@/assets/images/cat-loading.gif" width="150" />
                </div>
            </div>
        </div>
    </div>

    <br/><br/>
    <NewTaskModal />
</template>