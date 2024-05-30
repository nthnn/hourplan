<script lang="ts">
import $ from "jquery";
import env from "@/assets/scripts/config";
import md5 from "md5";
import TaskCard from "../components/TaskCard.vue";

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
            tasks: []
        }
    },
    created() {
        this.fetchData();
        this.updateTasks();
    },
    methods: {
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

                    const currentHash = md5(JSON.stringify(data));
                    if(prevTodoListHash[this.apiAction as string] === currentHash)
                        return;
                    prevTodoListHash[this.apiAction as string] = currentHash;

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

                    console.log(data);
                    this.tasks = data.tasks;
                }
            );
        },
        updateTasks(): void {
            setInterval(this.fetchData, 300);
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
        :title="task[2]"
        :desc="task[3]"
        :color="task[7]"
        :startDate="parseInt(task[4])"
        :endDate="parseInt(task[5])"
        :emotion="'crying'" />
</template>