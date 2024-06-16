<script lang="ts">
import $ from "jquery";
import env from "@/assets/scripts/config";
import md5 from "md5";
import TaskCard from "../components/TaskCard.vue";

import { base64ToString } from "@/assets/scripts/base64";
import { toUNIX } from "@/assets/scripts/time";

export default {
    props: {
        apiAction: {type: String},
        finished: {type: Boolean, default: false},
        large: {type: Boolean, default: false}
    },
    components: {
        TaskCard
    },
    data() {
        return {
            prevTodoListHash: {} as {[action: string]: string},
            tasks: [] as Array<string>,
            emotions: [] as Array<string>,
            fetchTask: 0 as number,
            loading: true as boolean
        }
    },
    mounted() {
        this.fetchTask = setInterval(this.fetchData, 1000);
    },
    beforeDestroy() {
        clearInterval(this.fetchTask);
    },
    methods: {
        base64ToString,
        fetchData(): void {
            $.post(
                env.host + "/task.php",
                {
                    action: this.apiAction,
                    session: localStorage.getItem("hash") as string
                },
                (data: any)=> {
                    if(this.apiAction == "todays_unfinished_tasks")
                        $("#class-task-loading")
                            .removeClass("d-block")
                            .addClass("d-none");
                    else if(this.apiAction == "all_task")
                        $("#class-tlist-loading")
                            .removeClass("d-block")
                            .addClass("d-none");
                    else $("#class-sched-loading")
                        .removeClass("d-block")
                        .addClass("d-none");

                    const currentHash = md5(JSON.stringify(data));
                    if(this.prevTodoListHash[this.apiAction as string] === currentHash)
                        return;
                    this.prevTodoListHash[this.apiAction as string] = currentHash;

                    this.tasks.splice(0, this.tasks.length);
                    this.emotions.splice(0, this.emotions.length);
                    this.fetchTask = 0;

                    this.tasks.splice(0, this.tasks.length);
                    this.tasks = data.tasks;

                    if(data.status != 1 || data.tasks.length == 0) {
                        if(this.apiAction == "todays_unfinished_tasks")
                            $("#todo-no-list")
                                .removeClass("d-none")
                                .addClass("d-block");
                        else if(this.apiAction == "all_task")
                            $("#list-no-task")
                                .removeClass("d-none")
                                .addClass("d-block");
                        else $("#sched-no-list")
                            .removeClass("d-none")
                            .addClass("d-block");

                        this.loading = false
                        return;
                    }
                    else {
                        if(this.apiAction == "todays_unfinished_tasks")
                            $("#todo-no-list")
                                .removeClass("d-block")
                                .addClass("d-none");
                        else if(this.apiAction == "all_task")
                            $("#list-no-task")
                                .removeClass("d-block")
                                .addClass("d-none");
                        else $("#sched-no-list")
                            .removeClass("d-block")
                            .addClass("d-none");
                    }

                    if(this.finished) {
                        this.tasks = this.tasks.filter(arr => arr[10] === "1");
                        if(this.tasks.length == 0)
                            $("#no-finished-tasks")
                                .removeClass("d-none")
                                .addClass("d-block");
                        else $("#no-finished-tasks")
                            .removeClass("d-block")
                            .addClass("d-none");
                    }

                    const currentUNIXstamp: number = toUNIX(new Date());
                    for(let i = 0; i < this.tasks.length; i++)
                        if(currentUNIXstamp > parseInt(this.tasks[i][4]) &&
                            this.tasks[i][10] !== '1' &&
                            !this.finished)
                            this.emotions[i] = "crying";
                        else this.emotions[i] = (["happy", "delighted"] as Array<string>)
                            [Math.round(Math.random())];

                    this.loading = false;
                }
            );
        }
    }
};
</script>

<template>
    <div v-if="!loading">
        <TaskCard
            v-for="(task, index) in tasks"
            :key="index"
            :large="large"
            :finished="task[10] === '1'"
            :id="parseInt(task[0])"
            :title="base64ToString(task[2])"
            :desc="base64ToString(task[3])"
            :color="task[7]"
            :startDate="parseInt(task[4])"
            :endDate="parseInt(task[5])"
            :emotion="emotions[index]"
            :categories="task[11]" />
    </div>
</template>