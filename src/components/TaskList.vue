<script lang="ts">
import $ from "jquery";
import env from "@/assets/scripts/config";
import md5 from "md5";
import TaskCard from "../components/TaskCard.vue";
import { base64ToString } from "@/assets/scripts/base64";
import { toUNIX } from "@/assets/scripts/time";

let prevTodoListHash: {[action: string]: string} = {};

export default {
    props: {
        apiAction: {type: String},
    },
    components: {
        TaskCard
    },
    data() {
        return {
            tasks: [] as any,
            emotions: [] as Array<string>,
            fetchTask: 0 as number
        }
    },
    mounted() {
        this.$forceUpdate();
        this.fetchData();
        this.updateTasks();
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
                    else $("#class-sched-loading")
                        .removeClass("d-block")
                        .addClass("d-none");

                    const currentHash = md5(JSON.stringify(data));
                    if(prevTodoListHash[this.apiAction as string] === currentHash)
                        return;
                    prevTodoListHash[this.apiAction as string] = currentHash;

                    if(data.status != 1) {
                        if(this.apiAction == "todays_unfinished_tasks")
                            $("#todo-no-list")
                                .removeClass("d-none")
                                .addClass("d-block");
                        else $("#sched-no-list")
                            .removeClass("d-none")
                            .addClass("d-block");

                        return;
                    }

                    if(data.tasks.length == 0) {
                        if(this.apiAction == "todays_unfinished_tasks")
                            $("#todo-no-list")
                                .removeClass("d-none")
                                .addClass("d-block");
                        else $("#sched-no-list")
                            .removeClass("d-none")
                            .addClass("d-block");
                    }
                    else {
                        if(this.apiAction == "todays_unfinished_tasks")
                            $("#todo-no-list")
                                .removeClass("d-block")
                                .addClass("d-none");
                        else $("#sched-no-list")
                            .removeClass("d-block")
                            .addClass("d-none");
                    }

                    this.tasks = data.tasks;

                    const currentUNIXstamp: number = toUNIX(new Date());
                    for(let i = 0; i < this.tasks.length; i++)
                        if(currentUNIXstamp > parseInt(this.tasks[i][4]))
                            this.emotions[i] = "crying";
                        else this.emotions[i] = (["happy", "delighted"] as Array<string>)
                            [Math.round(Math.random())];
                }
            );
        },
        updateTasks(): void {
            this.fetchTask = setInterval(this.fetchData, 300);
        },
        handleTaskUpdate(): void {
            this.updateTasks();
        }
    }
}
</script>

<template>
    <TaskCard
        v-for="(task, index) in tasks"
        :key="index"
        :title="base64ToString(task[2])"
        :desc="base64ToString(task[3])"
        :color="task[7]"
        :startDate="parseInt(task[4])"
        :endDate="parseInt(task[5])"
        :emotion="emotions[index]" />
</template>