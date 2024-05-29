<script lang="ts">
import $ from "jquery";
import env from "@/assets/scripts/config";
import md5 from "md5";
import TaskCard from "../components/TaskCard.vue";

let prevTodoListHash: string = "";
export default {
    props: {
        apiAction: {type: String},
    },
    components: {
        TaskCard
    },
    data() {
        return {
            tasks: [],
            classes: []
        }
    },
    created() {
        this.updateTasks();
    },
    methods: {
        updateTasks() {
            setInterval(()=> {
                $.post(
                    env.host + "/task.php",
                    {
                        action: this.apiAction,
                        session: localStorage.getItem("hash") as string
                    },
                    (data: any)=> {
                        if(data.status != 1) {
                            if(this.apiAction == "todays_unfinished_tasks") {
                                $("#class-task-loading").removeClass("d-none");
                                $("#class-task-loading").addClass("d-block");
                            }
                            else {
                                $("#class-sched-loading").removeClass("d-none");
                                $("#class-sched-loading").addClass("d-block");
                            }

                            return;
                        }

                        const currentHash = md5(JSON.stringify(data));
                        if(prevTodoListHash === currentHash)
                            return;
                        prevTodoListHash = currentHash;

                        if(data.tasks.length == 0) {
                            if(this.apiAction == "todays_unfinished_tasks") {
                                $("#class-task-loading").removeClass("d-none");
                                $("#class-task-loading").addClass("d-block");
                            }
                            else {
                                $("#class-sched-loading").removeClass("d-none");
                                $("#class-sched-loading").addClass("d-block");
                            }
                        }
                        else {
                            if(this.apiAction == "todays_unfinished_tasks") {
                                $("#class-task-loading").removeClass("d-block");
                                $("#class-task-loading").addClass("d-none");
                            }
                            else {
                                $("#class-sched-loading").removeClass("d-block");
                                $("#class-sched-loading").addClass("d-none");
                            }
                        }

                        this.tasks = data.tasks;
                    }
                );
            }, 300);
        },
        handleTaskUpdate() {
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
        :startDate="task[4]"
        :endDate="task[5]"
        :emotion="'crying'" />
</template>