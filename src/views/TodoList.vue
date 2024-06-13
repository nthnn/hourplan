<script setup lang="ts">
import Navbar from "../components/Navbar.vue";
import NewTaskModal from "../components/NewTaskModal.vue";
</script>

<script lang="ts">
import $ from "jquery";
import { createVNode, render } from "vue";

import {
    validateCurrentSession
} from "@/assets/scripts/session";
import TaskList from "@/components/TaskList.vue";

export default {
    created() {
        validateCurrentSession();
        setInterval(validateCurrentSession, 1000);

        this.renderDateTime();
        setInterval(this.renderDateTime, 1000);
    },
    mounted() {
        this.renderDateTime();
        this.renderTaskList();
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
        renderTaskList(): void {
            const listContainer: HTMLElement | null = document.getElementById("list-container");

            if(listContainer)
                render(
                    createVNode(
                        TaskList,
                        {apiAction: "all_task", large: true}
                    ),
                    listContainer
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

        <div id="class-tlist-loading" class="d-block" align="center">
            <br/>
            <br class="desktop-only" />
            <br/><br/>

            <img src="@/assets/images/cat-loading.gif" width="200" />
        </div>

        <div id="list-no-task" class="d-none" align="center">
            <br/>
            <img src="@/assets/images/cat-delighted.png" width="150" />
            <p>To-do list has no tasks yet!</p>
        </div>

        <br/>
        <div class="container">
            <div id="list-container"></div>
        </div>
        <br/>
    </div>

    <br/><br/>
    <NewTaskModal />
</template>