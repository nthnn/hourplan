<script setup lang="ts">
import $ from "jquery";
import Navbar from "../components/Navbar.vue";
import NewTaskModal from "../components/NewTaskModal.vue";

import { useRouter } from "vue-router";
import {
    validateCurrentSession
} from "@/assets/scripts/session";

const router = useRouter();
validateCurrentSession(router);
setInterval(()=> validateCurrentSession(router), 1000);

const currentDate = new Date().toLocaleDateString(
    'en-US',
    {
        weekday: 'short',
        month: 'long',
        day: 'numeric',
        year: 'numeric'
    } as any
);
</script>

<template>
    <Navbar />

    <div class="main-content">
        <div class="row mt-4 px-2 px-lg-4">
            <div class="col-7">
                <div class="d-inline-block w-auto px-2 px-lg-4 py-2 outlined-secondary brdr-secondary" align="center">
                    <p class="text-lato m-0">{{ currentDate }}</p>
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
        <br/><br class="desktop-only" />

        <div class="d-block" align="center">
            <br/><br/>
            <img src="@/assets/images/cat-loading.gif" width="200" />
        </div>

        <br/><br/>
    </div>

    <br/><br/>
    <NewTaskModal />
</template>