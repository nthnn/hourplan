<script setup lang="ts">
import $ from "jquery";
import Navbar from "../components/Navbar.vue";
import { useRouter } from "vue-router";
import {
    validateCurrentSession
} from "@/assets/scripts/session";

const router = useRouter();
validateCurrentSession(router);
setInterval(()=> validateCurrentSession(router), 1000);

const date = new Date();
setTimeout(()=> {
    $("#date-elem").html(
        date.toLocaleDateString(
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
                    <button class="w-auto btn outlined-secondary brdr-secondary text-dark text-lato w-100 py-2">Add New Task</button>
                </div>
            </div>
        </div>
        <br/>

        <div class="px-2 px-lg-4">
            <VDatePicker id="date-picker" v-model="date" class="w-100 border-dark date-picker" />
        </div>
        <br/>

        <div class="row px-2 px-lg-4">
            <div class="col-lg-6">
                <h4>Due Soon</h4>
            </div>

            <div class="col-lg-6">
                <br class="mobile-only" />
                <h4>Class Schedule</h4>
            </div>
        </div>
    </div>
</template>

<style>
.vc-title {
    background-color: white !important;
}

#date-picker {
    padding: 4px;
    border-width: 2px !important;
}

.vc-week:last-child {
    display: none;
}

@media only screen and (min-width: 770px) {
    .main-content {
        padding-left: 100px;
        padding-right: 100px;
    }

    .vc-header {
        margin-top: 16px !important;
    }

    .vc-header, .vc-day {
        margin-bottom: 12px;
    }
}
</style>