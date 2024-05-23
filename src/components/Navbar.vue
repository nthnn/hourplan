<template>
    <div class="clr-primary hp-navbar p-3">
        <div class="row">
            <div class="col-6 mt-1">
                <img src="@/assets/images/profile-placeholder.png" class="mx-4" width="36" />
                <p id="username" class="text-white d-inline text-lato"></p>
            </div>

            <div class="col-6" align="right">
                <div class="mx-3">
                    <span class="text-white text-lato mx-3 menu" id="home-route">Home</span>
                    <span class="text-white text-lato mx-3 menu" id="todo-route">To-Do List</span>
                    <span class="text-white text-lato mx-3 menu" id="calendar-route">Calendar</span>
                    <span class="text-white text-lato mx-3 menu" id="logout-route">Log-out</span>

                    <img src="@/assets/images/navbar-cat.png" width="48" />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.menu {
    cursor: pointer;
}
</style>