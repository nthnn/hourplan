<script setup lang="ts">
import "../../node_modules/animate.css/animate.min.css";

import $ from "jquery";
import env from "@/assets/scripts/config";
import md5 from "md5";

import {
    RouterLink,
    useRoute,
    type RouteLocationNormalizedLoaded
} from "vue-router";
import {
    validateSession
} from "@/assets/scripts/session";

const route: RouteLocationNormalizedLoaded = useRoute();
validateSession(route);
setInterval(()=> validateSession(route), 1000);

function login() {
    const username: string = $("#username").val() as string;
    const password: string = $("#password").val() as string;

    $.post(
        env.host + "/account.php",
        {
            action: "login",
            username: username,
            password: md5(password as string)
        },
        (data: any)=> {
            $("#login-error").removeClass("d-block");
            $("#login-error").addClass("d-none");

            if(data.status == 1) {
                localStorage.setItem("username", username as string);
                localStorage.setItem("theme", data.theme);
                localStorage.setItem("hash", data.hash);

                $("#login-error").removeClass("d-block");
                $("#login-error").addClass("d-none");

                window.location.href = "/home";
                return;
            }

            $("#login-error").html(data.error);
            $("#login-error").removeClass("d-none");

            $("#login-error").addClass("animate__shakeX");
            $("#login-error").addClass("d-block");

            setTimeout(
                ()=> $("#login-error").removeClass("animate__shakeX"),
                1500
            );
        }
    );
}
</script>

<template>
    <div class="d-flex align-items-center justify-content-center min-vh-100 w-100">
        <div class="d-block col-lg-3 col-sm-8 login-form">
            <div class="w-100" align="center">
                <img src="@/assets/images/login-cat.png" width="180" class="sitting-cat" />
            </div>

            <div class="clr-primary rounded text-white p-4">
                <h1 align="center">Welcome Back</h1>

                <input type="text" placeholder="Username" class="form-control mt-4 text-lato" id="username" />
                <input type="password" placeholder="Password" class="form-control mt-2 text-lato" id="password" />
                <p class="text-danger mt-2 d-none animate__animated text-lato" id="login-error"></p>
                <br/>

                <button
                    class="btn btn-warning clr-secondary brdr-secondary text-dark text-lato w-100"
                    @click="login">Log-in</button>
            </div>

            <div align="center">
                <p class="text-lato mt-2">or</p>

                <RouterLink
                    class="btn outlined-secondary brdr-secondary text-dark text-lato w-100 mt-2"
                    to="/signup">Create New Account</RouterLink>
            </div>
        </div>
    </div>

    <div class="min-vh-100 w-100 d-fixed" id="hands">
        <div id="left-hand"></div>
        <div id="right-hand"></div>
    </div>
</template>

<style scoped>
    .sitting-cat {
        margin-bottom: -87px;
    }

    div:has(> .sitting-cat) {
        overflow: hidden !important;
    }

    .login-form {
        margin-top: -125px;
    }

    #left-hand {
        background-image: url("@/assets/images/lefthand.png");
        background-position: bottom right;
        margin-bottom: -70px;
        bottom: 0;
        right: 0;
    }

    #right-hand {
        background-image: url("@/assets/images/righthand.png");
        background-position: top left;
        margin-top: -65px;
        left: 0;
        top: 0;
    }

    #hands {
        position: relative;
        z-index: -9999 !important;
    }

    #left-hand, #right-hand {
        background-repeat: no-repeat;
        background-size: 65%;
        position: fixed;
        height: 500px;
        width: 500px;
    }

    @media only screen and (max-width: 771px) {
        #left-hand, #right-hand {
            background-size: 29%;
        }
    }
</style>
