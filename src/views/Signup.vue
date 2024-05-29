<script setup lang="ts">
import "../../node_modules/animate.css/animate.min.css";

import $ from "jquery";
import env from "@/assets/scripts/config";
import md5 from "md5";

import { RouterLink, useRoute, type RouteLocationNormalizedLoaded } from "vue-router";
import {
    validateSession
} from "@/assets/scripts/session";

const route: RouteLocationNormalizedLoaded = useRoute();
validateSession(route);
setInterval(()=> validateSession(route), 1000);

function signup(): void {
    const username: string = $("#username").val() as string;
    const email: string = $("#email").val() as string;
    const password: string = $("#password").val() as string;

    $.post(
        env.host + "/account.php",
        {
            action: "signup",
            username: username,
            email: email,
            password: md5(password as string)
        },
        (data)=> {
            $("#signup-error").removeClass("d-block");
            $("#signup-error").addClass("d-none");

            if(data.status == 1) {
                localStorage.setItem("username", username as string);
                localStorage.setItem("theme", data.theme);
                localStorage.setItem("hash", data.hash);

                $("#signup-error").removeClass("d-block");
                $("#signup-error").addClass("d-none");

                window.location.href = "/home";
                return;
            }

            $("#signup-error").html(data.error);
            $("#signup-error").removeClass("d-none");

            $("#signup-error").addClass("animate__shakeX");
            $("#signup-error").addClass("d-block");

            setTimeout(
                ()=> $("#signup-error").removeClass("animate__shakeX"),
                1500
            );
        }
    );
}
</script>

<template>
    <div class="d-flex align-items-center justify-content-center min-vh-100 w-100">
        <div class="d-block col-lg-3 col-sm-8 signup-form">
            <div class="w-100" align="center">
                <img src="@/assets/images/login-cat.png" width="180" class="sitting-cat" />
            </div>

            <div class="clr-primary rounded text-white p-4">
                <h1 align="center">Create Account</h1>

                <input type="text" placeholder="Username" class="form-control mt-4 text-lato" id="username" />
                <input type="email" placeholder="Email" class="form-control mt-2 text-lato" id="email" />
                <input type="password" placeholder="Password" class="form-control mt-2 text-lato" id="password" />

                <p class="text-danger mt-2 d-none animate__animated text-lato" id="signup-error"></p>
                <div align="center">
                    <button
                        class="btn btn-warning clr-secondary brdr-secondary text-dark text-lato w-75 mt-4"
                        @click="signup">Sign-up</button>
                </div>
            </div>

            <div align="center">
                <p class="text-lato mt-3">Already have an account?</p>

                <RouterLink
                    class="btn outlined-secondary brdr-secondary text-dark text-lato w-50 mx-4"
                    to="/login">Login</RouterLink>
            </div>
        </div>
    </div>

    <div class="min-vh-100 w-100 d-fixed">
        <div id="twohands"></div>
    </div>
</template>

<style scoped>
    .sitting-cat {
        margin-bottom: -87px;
    }

    div:has(> .sitting-cat) {
        overflow: hidden !important;
    }

    .signup-form {
        margin-top: -140px !important;
    }

    #twohands {
        background-image: url("@/assets/images/twohands.png");
        background-position: bottom center;
        background-repeat: no-repeat;
        background-size: 100%;
        left: calc(50% - 300px);
        bottom: -350px;
        z-index: -9999;
        position: fixed;
        height: 600px;
        width: 600px;
    }

    @media only screen and (max-width: 771px) {
        #twohands {
            left: calc(50% - 150px);
            bottom: -150px !important;
            height: 300px;
            width: 300px;
            bottom: 0;
        }
    }
</style>
