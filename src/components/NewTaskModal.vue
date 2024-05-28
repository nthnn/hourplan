<script setup lang="ts">
import $ from "jquery";
import { ref } from "vue";
import { toUNIX } from "@/assets/scripts/time";

const startDate = ref(new Date()),
    endDate = ref(new Date()),
    endRepeatDate = ref(new Date());

endDate.value.setDate(endDate.value.getDate() + 1);
endRepeatDate.value.setDate(endRepeatDate.value.getDate() + 1);

function createNewTask() {
    const title = $("#task-title").val();
    const desc = $("#task-desc").val();
    const startDt = toUNIX(startDate.value);
    const endDt = toUNIX(endDate.value);
    const repeat = $("#task-repeat option:selected").val();
    const type = $("input[name=task-type]:checked").val();

    let ends: any = $("input[name=task-end]:checked").val();
    if(ends == "never")
        ends = 0;
    else ends = toUNIX(endRepeatDate.value);
}
</script>

<template>
    <div class="modal fade" id="newTaskModal" tabindex="-1" aria-labelledby="newTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-9">
                            <h3 class="mb-4">Add New Task</h3>
                        </div>

                        <div class="col-3" align="right">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>

                    <label for="task-title" class="form-label text-lato">Title</label>
                    <input type="text" class="form-control text-lato" id="task-title" placeholder="Add Title" />

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
                    <select id="task-repeat" name="task-repeat" class="form-control text-lato">
                        <option class="text-lato" value="0">Does not repeat</option>
                        <option class="text-lato" value="1">Every Day</option>
                        <option class="text-lato" value="2">Every Week</option>
                        <option class="text-lato" value="3">Every Month</option>
                        <option class="text-lato" value="4">Every Year</option>
                    </select>

                    <label for="task-end" class="form-label text-lato mt-2">Ends</label>
                    <div class="d-block">
                        <div class="form-check d-inline-block">
                            <input class="form-check-input" type="radio" id="task-end-never" value="never" name="task-end" />
                            <label for="task-end-never" class="form-label text-lato">Never</label>
                        </div>

                        <div class="form-check d-inline-block ms-4">
                            <input class="form-check-input" type="radio" id="task-end-on" value="on" name="task-end" />
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
                            <input type="radio" name="task-type" id="task-type" class="form-check-input" value="0" />
                            <label for="task-type" class="form-label text-lato">Task</label>
                        </div>

                        <div class="form-check d-inline-block ms-4">
                            <input type="radio" name="task-type" id="task-type" class="form-check-input" value="1" />
                            <label for="task-type" class="form-label text-lato">Schedule</label>
                        </div>
                    </div>

                    <div align="right">
                        <button class="btn clr-secondary text-lato brdr-dark" @click="createNewTask">Submit New Task</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>