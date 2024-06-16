<script setup lang="ts">
import $ from "jquery";
import env from "@/assets/scripts/config";

import { detectMobile } from '@/assets/scripts/mobile_detect';
import { ref, type Ref } from 'vue';

const publicPath: string = env.host.split("/")[0] + "/";
const currentTheme: string = localStorage.getItem("theme") as string;
const showAlaska: Ref<boolean> = ref(currentTheme == "0"),
    showCharcole: Ref<boolean> = ref(currentTheme == "1"),
    showTabby: Ref<boolean> = ref(currentTheme == "2");

const showMessage = (id: string, message: string)=> {
    $("#" + id).removeClass("d-none");
    $("#" + id).addClass("d-block");
    $("#" + id).html(message);
}, hideMessage = (id: string)=> {
    $("#" + id).removeClass("d-block");
    $("#" + id).addClass("d-none");
    $("#" + id).html("");
};

function updatePreview() {
    let selected: number = parseInt($("#selected-theme").find(":selected").val() as string);
    switch(selected) {
        case 0:
            showAlaska.value = true;
            showCharcole.value = false;
            showTabby.value = false;
            break;

        case 1:
            showAlaska.value = false;
            showCharcole.value = true;
            showTabby.value = false;
            break;

        case 2:
            showAlaska.value = false;
            showCharcole.value = false;
            showTabby.value = true;
            break;
    }
}

function changeTheme() {
    let selected: number = parseInt(
        $("#selected-theme")
            .find(":selected")
            .val() as string
    );

    hideMessage("change-theme-error");
    hideMessage("change-theme-success");

    $.post(
        env.host + "/account.php",
        {
            action: "change_theme",
            hash: localStorage.getItem("hash") as string,
            theme: selected,
        },
        (data)=> {
            if(data.status == 1) {
                showMessage("change-theme-success", "Successfully changed theme!");
                localStorage.setItem("theme", selected.toString());

                setTimeout(()=> hideMessage("change-theme-success"), 2000);
                return;
            }

            showMessage("change-theme-error", "Something went wrong while trying to change theme.");
        }
    );
}
</script>

<template>
    <h1 v-if="!detectMobile()">Theme Settings</h1>
    <hr class="desktop-only" />

    <div class="container">
        <div class="row">
            <div class="col-3">
                <label for="selected-theme" class="form-label mt-1 text-lato">Theme</label>
            </div>

            <div class="col-9 col-lg-7">
                <select class="form-control text-lato w-100" id="selected-theme" @change="updatePreview">
                    <option class="text-lato" value="0" :selected="currentTheme == '0'">Alaska</option>
                    <option class="text-lato" value="1" :selected="currentTheme == '1'">Charcole</option>
                    <option class="text-lato" value="2" :selected="currentTheme == '2'">Tabby</option>
                </select>
            </div>

            <div class="col-12 col-lg-2">
                <button type="button" class="btn outlined-secondary text-lato w-100 mt-2 mt-lg-0" @click="changeTheme">Save</button>
            </div>
        </div>

        <p class="d-none text-danger text-lato mt-2" id="change-theme-error"></p>
        <p class="d-none text-info text-lato mt-2" id="change-theme-success"></p>

        <hr/>
        <p class="text-lato mt-2">Preview:</p>
        <div v-if="showAlaska">
            <div class="row mt-2">
                <div class="col-4" align="center">
                    <img :src="`${publicPath}images/cat-delighted-0.png`" width="100%" />
                    <p>Sleeping Alaska</p>
                </div>

                <div class="col-4" align="center">
                    <img :src="`${publicPath}images/cat-happy-0.png`" width="100%" />
                    <p>Happy Alaska</p>
                </div>

                <div class="col-4" align="center">
                    <img :src="`${publicPath}images/cat-crying-0.png`" width="100%" />
                    <p>Crying Alaska</p>
                </div>
            </div>
        </div>
        <div v-if="showCharcole">
            <div class="row mt-2">
                <div class="col-4" align="center">
                    <img :src="`${publicPath}images/cat-delighted-1.png`" width="100%" />
                    <p>Sleeping Charcole</p>
                </div>

                <div class="col-4" align="center">
                    <img :src="`${publicPath}images/cat-happy-1.png`" width="100%" />
                    <p>Happy Charcole</p>
                </div>

                <div class="col-4" align="center">
                    <img :src="`${publicPath}images/cat-crying-1.png`" width="100%" />
                    <p>Crying Charcole</p>
                </div>
            </div>
        </div>
        <div v-if="showTabby">
            <div class="row mt-2">
                <div class="col-4" align="center">
                    <img :src="`${publicPath}images/cat-delighted-2.png`" width="100%" />
                    <p>Sleeping Tabby</p>
                </div>

                <div class="col-4" align="center">
                    <img :src="`${publicPath}images/cat-happy-2.png`" width="100%" />
                    <p>Happy Tabby</p>
                </div>

                <div class="col-4" align="center">
                    <img :src="`${publicPath}images/cat-crying-2.png`" width="100%" />
                    <p>Crying Tabby</p>
                </div>
            </div>
        </div>
    </div>
</template>