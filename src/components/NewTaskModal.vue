<script setup lang="ts">
import "../../node_modules/animate.css/animate.min.css";

import $ from "jquery";
import env from "@/assets/scripts/config";

import { onMounted, ref, type Ref } from "vue";
import { toUNIX } from "@/assets/scripts/time";

const startDate: Ref<Date> = ref(new Date()),
    endDate: Ref<Date> = ref(new Date()),
    endRepeatDate: Ref<Date> = ref(new Date());

endDate.value.setDate(endDate.value.getDate() + 1);
endRepeatDate.value.setDate(endRepeatDate.value.getDate() + 1);

function createNewTask() {
    const title: string = $("#task-title").val() as string;
    const desc: string = $("#task-desc").val() as string;
    const categories: string = $("#task-categories").val() as string;

    const startDt: number = toUNIX(startDate.value);
    const endDt: number = toUNIX(endDate.value);

    const repeat: number = $("#task-repeat option:selected").val() as number;
    const type: number = $("input[name=task-type]:checked").val() as number;

    let ends: any = $("input[name=task-end]:checked").val();
    if(ends == "never")
        ends = 0;
    else ends = toUNIX(endRepeatDate.value);

    const showError = (message: string): void => {
        $("#create-error").html(message);
        $("#create-error").removeClass("d-none");

        $("#create-error").addClass("animate__shakeX");
        $("#create-error").addClass("d-block");

        setTimeout(
            ()=> $("#create-error").removeClass("animate__shakeX"),
            1500
        );
    };

    if(title == undefined || title == "") {
        showError("Invalid task title string.");
        return;
    }

    if(desc == undefined || desc == "") {
        showError("Invalid task description string.");
        return;
    }

    if(repeat == undefined) {
        showError("Repeat must have a selected value.");
        return;
    }

    if(type == undefined) {
        showError("Type must have a selected value.");
        return;
    }

    $.post(
        env.host + "/task.php",
        {
            action: "create",
            session: localStorage.getItem("hash") as string,
            title: title,
            desc: desc,
            start_dt: startDt,
            end_dt: endDt,
            repeat: repeat,
            color: $("#task-color").val(),
            ends: ends,
            type: type,
            categories: categories
        },
        (data: any)=> {
            $("#create-error").removeClass("d-block");
            $("#create-error").addClass("d-none");

            if(data.status == 1) {
                $("#create-error").removeClass("d-block");
                $("#create-error").addClass("d-none");

                $("#closeCreateTaskModal").trigger("click");
                setTimeout(
                    ()=> $("#openTaskCreatedModal").trigger("click"),
                    200
                );

                return;
            }

            showError(data.error);
        }
    );
}

onMounted(()=> {
    const newTaskModal: HTMLElement = document.getElementById("newTaskModal") as HTMLElement;

    newTaskModal.addEventListener("hide.bs.modal", ()=> {
        $("#task-title").val("");
        $("#task-desc").val("");
        $("#task-categories").val("");
        $("#task-color").val("#a9b7fa");

        $("#task-repeat option:selected").prop("selected", false);
        $("input[name=task-type]:checked").prop("checked", false);
        $("input[name=task-end]:checked").prop("checked", false);
    });

    newTaskModal.addEventListener("show.bs.modal", ()=> {
        startDate.value = new Date();
        endDate.value = new Date();
        endRepeatDate.value = new Date();

        endDate.value.setDate(endDate.value.getDate() + 1);
        endRepeatDate.value.setDate(endRepeatDate.value.getDate() + 1);
    });
});
</script>

<template>
    <div class="modal fade" id="newTaskModal" tabindex="-1" aria-labelledby="newTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-9">
                            <h3 class="mb-4" style="overflow: hidden">Add New Task</h3>
                        </div>

                        <div class="col-3" align="right">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeCreateTaskModal"></button>
                        </div>
                    </div>

                    <label for="task-title" class="form-label text-lato">Title</label>
                    <div class="row">
                        <div class="col-10 col-lg-10">
                            <input type="text" class="form-control text-lato" id="task-title" placeholder="Add Title" />
                        </div>

                        <div class="col-2 col-lg-2 pe-lg-4">
                            <input type="color" id="task-color" class="form-control outline-none border-none p-0 m-0 me-lg-4 h-100" value="#a9b7fa" />
                        </div>
                    </div>

                    <label for="task-desc" class="form-label text-lato mt-2">Description</label>
                    <input type="text" class="form-control text-lato" id="task-desc" placeholder="What your task is about?" />

                    <div class="row">
                        <div class="col-lg-6">
                            <label for="task-start-time" class="form-label text-lato mt-2">Start Time</label>
                            <VDatePicker v-model="startDate" mode="dateTime" :input-debounce="500">
                                <template #default="{ inputValue, inputEvents }">
                                    <input class="form-control text-lato" id="task-start-time" :value="inputValue" v-on="inputEvents" />
                                </template>
                            </VDatePicker>
                        </div>

                        <div class="col-lg-6">
                            <label for="task-end-time" class="form-label text-lato mt-2">End Time</label>
                            <VDatePicker v-model="endDate" mode="dateTime" :input-debounce="500">
                                <template #default="{ inputValue, inputEvents }">
                                    <input class="form-control text-lato" id="task-end-time" :value="inputValue" v-on="inputEvents" />
                                </template>
                            </VDatePicker>
                        </div>
                    </div>

                    <label for="task-repeat" class="form-label text-lato mt-2">Repeat</label>
                    <select id="task-repeat" name="task-repeat" class="form-control text-lato" required>
                        <option class="text-lato" value="0">Does not repeat</option>
                        <option class="text-lato" value="1">Every Day</option>
                        <option class="text-lato" value="3">Every Month</option>
                        <option class="text-lato" value="4">Every Year</option>
                    </select>

                    <label for="task-end" class="form-label text-lato mt-2">Ends</label>
                    <div class="d-block">
                        <div class="form-check d-inline-block">
                            <input class="form-check-input" type="radio" id="task-end-never" value="never" name="task-end" required />
                            <label for="task-end-never" class="form-label text-lato">Never</label>
                        </div>

                        <div class="form-check d-inline-block ms-4">
                            <input class="form-check-input" type="radio" id="task-end-on" value="on" name="task-end" required />
                            <label for="task-end-on" class="form-label text-lato">On</label>
                        </div>

                        <div class="form-check d-inline-block">
                            <VDatePicker v-model="endRepeatDate" mode="dateTime" :input-debounce="500">
                                <template #default="{ inputValue, inputEvents }">
                                    <input class="form-control text-lato d-inline-block mb-2" id="task-end-repeat-date" :value="inputValue" v-on="inputEvents" />
                                </template>
                            </VDatePicker>
                        </div>
                    </div>

                    <label for="task-type" class="form-label text-lato">Type</label>
                    <div class="d-block">
                        <div class="form-check d-inline-block">
                            <input type="radio" name="task-type" id="task-type" class="form-check-input" value="0" required />
                            <label for="task-type" class="form-label text-lato">Task</label>
                        </div>

                        <div class="form-check d-inline-block ms-4">
                            <input type="radio" name="task-type" id="task-type" class="form-check-input" value="1" required />
                            <label for="task-type" class="form-label text-lato">Schedule</label>
                        </div>
                    </div>

                    <label for="task-categories" class="form-label text-lato">Categories</label>
                    <input type="text" class="form-control text-lato" id="task-categories" placeholder="List of categories (e.g.: class,job,etc.)" />

                    <p class="text-lato text-danger" id="create-error"></p>
                    <div align="right">
                        <button
                            class="btn clr-secondary text-lato brdr-dark"
                            @click="createNewTask">Submit New Task</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="taskCreatedModal" tabindex="-1" aria-labelledby="taskCreatedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-body" align="center">
                    <img src="@/assets/images/cat-happy.png" width="120" />
                    <br/>
                    <h3 style="overflow: hidden">Task was successfully created!</h3>
                </div>
            </div>
        </div>
    </div>

    <button class="d-none" id="openTaskCreatedModal" data-bs-toggle="modal" data-bs-target="#taskCreatedModal"></button>
</template>