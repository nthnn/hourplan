<script setup lang="ts">
import $ from "jquery";
import env from "../assets/scripts/config";
import Navbar from "../components/Navbar.vue";
import {
    validateCurrentSession
} from "@/assets/scripts/session";

validateCurrentSession();
setInterval(validateCurrentSession, 1000);

setTimeout(()=> {
    $("#home-route").addClass("selected-menu");
    $("#mb-home-route").addClass("menu-selected");
    $("#username").html(localStorage.getItem('username') as string);

    $("#logout-route").click(()=> {
        $.post(
            env.host + "/account.php",
            {
                action: "logout",
                hash: localStorage.getItem("hash")
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
}, 200);
</script>

<template>
    <Navbar />
</template>

<style>
.selected-menu {
    padding-top: 6px;
    padding-bottom: 6px;
    padding-left: 12px;
    padding-right: 12px;
    border-radius: 6px;
    border-color: white;
    border-style: solid;
    border-width: 1px;
}

.menu-selected {
    padding-bottom: 12px;
    border-style: solid;
    border-color: white;
    border-width: 0px 0px 4px 0px !important;
}
</style>