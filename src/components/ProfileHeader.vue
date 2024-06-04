<script setup lang="ts">
import "../assets/styles/global.css";

import $ from "jquery";
import env from "../assets/scripts/config";

import GalleryTab from "../components/GalleryTab.vue";
import OverviewTab from "../components/OverviewTab.vue";
import FinishedTaskTab from "../components/FinishedTaskTab.vue";
import AchievementsTab from "../components/AchievementsTab.vue";

import { useRoute, type RouteLocationNormalizedLoaded } from "vue-router";
import { ref, type Ref } from "vue";

const route: RouteLocationNormalizedLoaded = useRoute();
const username: string = "@" + (localStorage.getItem("username") as string);

const showGallery: Ref<boolean> = ref(true),
    showOverview: Ref<boolean> = ref(false),
    showFinished: Ref<boolean> = ref(false),
    showAchievement: Ref<boolean> = ref(false);

function switchGalleryView() {
    showGallery.value = true;
    showOverview.value = false;
    showFinished.value = false;
    showAchievement.value = false;
}

function switchOverviewView() {
    showGallery.value = false;
    showOverview.value = true;
    showFinished.value = false;
    showAchievement.value = false;
}

function switchFinishedTaskView() {
    showGallery.value = false;
    showOverview.value = false;
    showFinished.value = true;
    showAchievement.value = false;
}

function switchAchievementsView() {
    showGallery.value = false;
    showOverview.value = false;
    showFinished.value = false;
    showAchievement.value = true;
}

setTimeout(()=> {
    $("#" + (route.name as string) + "-route").addClass("selected-menu");
    $("#mb-" + (route.name as string) + "-route").addClass("menu-selected");

    $("#logout-route").click(()=> {
        $.post(
            env.host + "/account.php",
            {
                action: "logout",
                hash: localStorage.getItem("hash") as string
            },
            (data)=> {
                if(data.status == 1) {
                    localStorage.setItem("username", "null");
                    localStorage.setItem("theme", "null");
                    localStorage.setItem("hash", "null");
                }
            }
        );
    });
}, 10);
</script>

<template>
    <div class="clr-primary hp-navbar p-3 desktop-only">
        <div class="clr-primary hp-navbar p-3 d-fixed fixed-top" align="right">
            <RouterLink class="text-white text-lato mx-3 menu" id="home-route" to="/home">Home</RouterLink>
            <RouterLink class="text-white text-lato mx-3 menu" id="todo-list-route" to="/todo-list">To-Do List</RouterLink>
            <RouterLink class="text-white text-lato mx-3 menu" id="calendar-route" to="/calendar">Calendar</RouterLink>
            <span class="text-white text-lato mx-3 menu" id="logout-route">Log-out</span>

            <img src="@/assets/images/navbar-cat.png" width="48" />
        </div>

        <br/><br/><br/><br/>
        <br/><br/><br/><br/>
    </div>

    <div class="row profile-row-desktop">
        <div class="col-3">
            <img src="@/assets/images/profile-placeholder.png" width="250" />
        </div>

        <div class="col-6">
            <br/><br/>
            <h1 class="mt-2 text-lato text-white">{{ username }}</h1>

            <br/>
            <div class="d-block mt-2 w-100">
                <button v-if="showGallery" class="btn clr-primary text-white text-lato fw-light px-2 me-2 d-inline-block" @click="switchGalleryView">Gallery</button>
                <button v-else class="btn outlined-secondary brdr-secondary text-dark text-lato px-2 me-2 d-inline-block" @click="switchGalleryView">Gallery</button>

                <button v-if="showOverview" class="btn clr-primary text-white text-lato fw-light px-2 me-2 d-inline-block" @click="switchOverviewView">Task Overview</button>
                <button v-else class="btn outlined-secondary brdr-secondary text-dark text-lato px-2 me-2 d-inline-block" @click="switchOverviewView">Task Overview</button>

                <button v-if="showFinished" class="btn clr-primary text-white text-lato fw-light px-2 me-2 d-inline-block" @click="switchFinishedTaskView">Finished Task</button>
                <button v-else class="btn outlined-secondary brdr-secondary text-dark text-lato px-2 me-2 d-inline-block" @click="switchFinishedTaskView">Finished Task</button>

                <button v-if="showAchievement" class="btn clr-primary text-white text-lato fw-light px-2 me-2 d-inline-block" @click="switchAchievementsView">Achievements</button>
                <button v-else="showAchievement" class="btn outlined-secondary brdr-secondary text-dark text-lato px-2 me-2 d-inline-block" @click="switchAchievementsView">Achievements</button>
            </div>
        </div>

        <div class="col-3" align="right">
            <br/><br/>
            <RouterLink to="/settings" class="text-decoration-none">
                <img src="@/assets/images/ic-settings.png" width="48" />
            </RouterLink>
        </div>
    </div>

    <div class="clr-primary hp-navbar pt-3 pl-3 pr-3 mobile-only" id="mb-navbar">
        <div class="row clr-primary hp-navbar justify-content-center pt-3 pl-3 pr-3 d-fixed fixed-top">
            <div class="col-2">
                <img src="@/assets/images/profile-placeholder.png" width="36" />
            </div>

            <div class="col-2">
                <RouterLink to="/home">
                    <img src="@/assets/images/ic-home.png" id="mb-home-route" width="36" />
                </RouterLink>
            </div>

            <div class="col-2">
                <RouterLink to="/todo-list">
                    <img src="@/assets/images/ic-todo-list.png" id="mb-todo-list-route" width="36" />
                </RouterLink>
            </div>

            <div class="col-2">
                <RouterLink to="/calendar">
                    <img src="@/assets/images/ic-calendar.png" id="mb-calendar-route" width="36" />
                </RouterLink>
            </div>

            <div class="col-2">
                <img src="@/assets/images/navbar-cat.png" width="36" />
            </div>
        </div>

        <br/><br/><br/><br/>
        <br/><br/><br/><br/>
    </div>

    <div class="profile-row-mobile" align="center">
        <div class="row">
            <div class="col-3"></div>

            <div class="col-6">
                <img src="@/assets/images/profile-placeholder.png" width="120" />
            </div>

            <div class="col-3">
                <RouterLink to="/settings" class="text-decoration-none">
                    <img src="@/assets/images/ic-settings.png" width="32" class="mt-2" />
                </RouterLink>
            </div>
        </div>

        <div align="center">
            <h1 class="mt-2 text-lato">{{ username }}</h1>
        </div>

        <div class="row container g-1 mt-2">
            <div class="col-3">
                <button v-if="showGallery" class="btn clr-primary text-white text-lato fw-light px-2 w-100" @click="switchGalleryView">Gallery</button>
                <button v-else class="btn outlined-secondary brdr-secondary text-dark text-lato px-2 w-100" @click="switchGalleryView">Gallery</button>
            </div>

            <div class="col-3">
                <button v-if="showOverview" class="btn clr-primary text-white text-lato fw-light px-2 w-100" @click="switchOverviewView">Overview</button>
                <button v-else class="btn outlined-secondary brdr-secondary text-dark text-lato px-2 w-100" @click="switchOverviewView">Overview</button>
            </div>

            <div class="col-3">
                <button v-if="showFinished" class="btn clr-primary text-white text-lato fw-light px-2 w-100" @click="switchFinishedTaskView">Task</button>
                <button v-else class="btn outlined-secondary brdr-secondary text-dark text-lato px-2 w-100" @click="switchFinishedTaskView">Task</button>
            </div>

            <div class="col-3">
                <button v-if="showAchievement" class="btn clr-primary text-white text-lato fw-light px-2 w-100" @click="switchAchievementsView">Achievements</button>
                <button v-else class="btn outlined-secondary brdr-secondary text-dark text-lato px-2 w-100" @click="switchAchievementsView">Achievements</button>
            </div>
        </div>
    </div>
    <br/>

    <GalleryTab :show="showGallery" />
    <OverviewTab :show="showOverview" />
    <FinishedTaskTab :show="showFinished" />
    <AchievementsTab :show="showAchievement" />
</template>

<style scoped>
.menu {
    text-decoration: none;
    cursor: pointer;
}

#mb-navbar img {
    padding: 0px 4px 8px 4px;
}

.profile-row-desktop {
    padding-left: 120px;
    padding-right: 120px;
    margin-top: -125px;
}

.profile-row-mobile {
    display: none;
}

@media only screen and (max-width: 771px) {
    .profile-row-desktop {
        display: none;
    }

    .profile-row-mobile {
        margin-top: -60px;
        display: block;
    }
}
</style>