<script setup lang="ts">
import $ from "jquery";
import md5 from "md5";

import { detectMobile } from '@/assets/scripts/mobile_detect';

const showMessage = (id: string, message: string)=> {
    $("#" + id).removeClass("d-none");
    $("#" + id).addClass("d-block");
    $("#" + id).html(message);
}, hideMessage = (id: string)=> {
    $("#" + id).removeClass("d-block");
    $("#" + id).addClass("d-none");
    $("#" + id).html("");
};

function saveChanges() {
    let username: string = $("#settings-username").val() as string,
        email: string = $("#settings-email").val() as string;

    hideMessage("change-info-error");
    hideMessage("change-info-success");

    $.post(
        env.host + "/account.php",
        {
            action: "change_info",
            hash: localStorage.getItem("hash") as string,
            username: username,
            email: email
        },
        (data)=> {
            if(data.status == 1) {
                showMessage("change-info-success", "Changes saved!");
                localStorage.setItem("username", username);

                setTimeout(()=> window.location.reload(), 2000);
                return;
            }

            showMessage("change-info-error", data.error);
        }
    );
}

function changePassword() {
    let oldPassword: string = $("#settings-old-password").val() as string,
        newPassword: string = $("#settings-new-password").val() as string,
        confirmPassword: string = $("#settings-confirm-password").val() as string;

    hideMessage("change-pass-error");
    hideMessage("change-pass-success");

    if(newPassword != confirmPassword) {
        showMessage(
            "change-pass-error",
            "New password and password confirmation did not match."
        );
        return;
    }

    $.post(
        env.host + "/account.php",
        {
            action: "change_password",
            hash: localStorage.getItem("hash") as string,
            oldpw: md5(oldPassword),
            newpw: md5(newPassword)
        },
        (data)=> {
            if(data.status == 1) {
                showMessage("change-pass-success", "Password changed!");
                setTimeout(()=> {
                    $("#settings-old-password").val("");
                    $("#settings-new-password").val("");
                    $("#settings-confirm-password").val("");

                    hideMessage("change-pass-success");
                }, 2000);

                return;
            }

            showMessage("change-pass-error", data.error);
        }
    );
}
</script>

<script lang="ts">
import env from "@/assets/scripts/config";

export default {
    mounted() {
        $.post(
                env.host + "/account.php",
                {
                    action: "fetch_email",
                    hash: localStorage.getItem("hash") as string
                },
                (data: any)=> {
                    $("#settings-username").val(localStorage.getItem("username") as string);
                    $("#settings-email").val(data[0]);
                }
        );
    }
}
</script>

<template>
    <h1 v-if="!detectMobile()">Account Settings</h1>
    <hr class="desktop-only"/>

    <div class="container">
        <div class="row">
            <div class="col-3">
                <label for="settings-username" class="form-label mt-1 text-lato">Username</label>
            </div>

            <div class="col-9">
                <input type="text" class="col-9 form-control text-lato" id="settings-username" placeholder="Username" />
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-3">
                <label for="settings-email" class="form-label mt-1 text-lato">Email</label>
            </div>

            <div class="col-9">
                <input type="email" class="form-control text-lato" id="settings-email" placeholder="Email Address" />
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <p class="d-none text-danger text-lato" id="change-info-error"></p>
                <p class="d-none text-info text-lato" id="change-info-success"></p>
            </div>

            <div class="col-6" align="right">
                <button type="button" class="btn outlined-secondary text-lato mt-2" @click="saveChanges">Save Changes</button>
            </div>
        </div>
        <hr/>

        <div class="row mt-2">
            <div class="col-3">
                <label for="settings-old-password" class="form-label mt-1 text-lato">Old Password</label>
            </div>

            <div class="col-9">
                <input type="password" class="form-control text-lato" id="settings-old-password" placeholder="Old Password" />
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-3">
                <label for="settings-new-password" class="form-label mt-1 text-lato">New Password</label>
            </div>

            <div class="col-9">
                <input type="password" class="form-control text-lato" id="settings-new-password" placeholder="New Password" />
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-3">
                <label for="settings-confirm-password" class="form-label mt-1 text-lato">Confirm Password</label>
            </div>

            <div class="col-9">
                <input type="password" class="form-control text-lato" id="settings-confirm-password" placeholder="Confirm Password" />
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <p class="d-none text-danger text-lato" id="change-pass-error"></p>
                <p class="d-none text-info text-lato" id="change-pass-success"></p>
            </div>

            <div class="col-6" align="right">
                <button type="button" class="btn outlined-secondary text-lato mt-2" @click="changePassword">Change Password</button>
            </div>
        </div>
    </div>
    <br/>
</template>