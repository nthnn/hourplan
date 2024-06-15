<script setup lang="ts">
import { detectMobile } from '@/assets/scripts/mobile_detect';
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

        <div align="right">
            <button type="button" class="btn outlined-secondary text-lato mt-2">Save Changes</button>
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

        <div align="right">
            <button type="button" class="btn outlined-secondary text-lato mt-2">Change Password</button>
        </div>
    </div>
    <br/>
</template>