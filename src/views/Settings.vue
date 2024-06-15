<script setup lang="ts">
import $ from "jquery";
import env from "@/assets/scripts/config";
import Navbar from "../components/Navbar.vue";

import AccountSettings from "@/components/AccountSettings.vue";

import { ref, type Ref } from "vue";
import { detectMobile } from "@/assets/scripts/mobile_detect";

const showAccountSettings: Ref<boolean> = ref(true),
    showNotificationsSettings: Ref<boolean> = ref(false),
    showThemesSettings: Ref<boolean> = ref(false),
    showSettingsNav: Ref<boolean> = ref(true);
const pageTitle: Ref<string> = ref("");

function accountSettings(): void {
    pageTitle.value = "Account Settings";

    showAccountSettings.value = true;
    showNotificationsSettings.value = false;
    showThemesSettings.value = false;

    if(detectMobile())
        showSettingsNav.value = false;
}

function notificationSettings(): void {
    pageTitle.value = "Notification Settings";

    showAccountSettings.value = false;
    showNotificationsSettings.value = true;
    showThemesSettings.value = false;

    if(detectMobile())
        showSettingsNav.value = false;
}

function themeSettings(): void {
    pageTitle.value = "Theme Settings";

    showAccountSettings.value = false;
    showNotificationsSettings.value = false;
    showThemesSettings.value = true;

    if(detectMobile())
        showSettingsNav.value = false;
}

function settingsGoBack(): void {
    pageTitle.value = "";

    showAccountSettings.value = false;
    showNotificationsSettings.value = false;
    showThemesSettings.value = false;
    showSettingsNav.value = true;
}

function logout() {
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
}
</script>

<script lang="ts">
import {
    validateCurrentSession
} from "@/assets/scripts/session";
import NotificationSettings from "@/components/NotificationSettings.vue";
import ThemeSettings from "@/components/ThemeSettings.vue";

export default {
    created() {
        validateCurrentSession();
        setInterval(validateCurrentSession, 1000);
    }
};
</script>

<template>
    <Navbar />
    <br/>

    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-4">
                <div v-if="showSettingsNav" class="clr-primary rounded p-4" align="center">
                    <h2 class="text-white overflow-hidden mt-2">Settings</h2>
                    <br/>

                    <button v-if="showAccountSettings && !detectMobile()" class="clr-secondary border-none py-1 rounded shadow-none text-dark text-lato px-2 w-100 mt-2" @click="accountSettings">Account</button>
                    <button v-else class="clr-enabled border-none py-1 rounded text-dark text-lato px-2 w-100 mt-2" @click="accountSettings">Account</button>

                    <button v-if="showNotificationsSettings" class="clr-secondary border-none py-1 rounded text-dark text-lato px-2 w-100 mt-2" @click="notificationSettings">Notifications</button>
                    <button v-else class="clr-enabled border-none py-1 rounded text-dark text-lato px-2 w-100 mt-2" @click="notificationSettings">Notifications</button>

                    <button v-if="showThemesSettings" class="clr-secondary border-none py-1 rounded text-dark text-lato px-2 w-100 mt-2" @click="themeSettings">Theme</button>
                    <button v-else class="clr-enabled border-none py-1 rounded text-dark text-lato px-2 w-100 mt-2" @click="themeSettings">Theme</button>

                    <div class="desktop-only">
                        <br/><br/><br/><br/><br/><br/>
                    </div>
                    <hr class="mobile-only" />

                    <button class="clr-enabled border-none py-1 rounded text-dark text-lato px-2 w-100 mt-2" @click="logout">Logout</button>
                    <br/><br/>
                </div>
                <div v-else>
                    <div class="row">
                        <div class="col-2">
                            <img src="@/assets/images/arrow.png" width="100%" @click="settingsGoBack" />
                        </div>

                        <div class="col-9">
                            <h3 class="mt-1">{{ pageTitle }}</h3>
                        </div>
                    </div>
                </div>

                <br/>
            </div>

            <div v-if="!detectMobile()" class="col-8">
                <AccountSettings v-if="showAccountSettings" />
                <NotificationSettings v-if="showNotificationsSettings" />
                <ThemeSettings v-if="showThemesSettings" />
            </div>
        </div>

        <div v-if="detectMobile() && !showSettingsNav">
            <AccountSettings v-if="showAccountSettings" />
            <NotificationSettings v-if="showNotificationsSettings" />
            <ThemeSettings v-if="showThemesSettings" />
        </div>
    </div>
</template>

<style scoped>
hr {
    color: white;
}
</style>