import $ from "jquery";
import env from "@/assets/scripts/config";
import { type RouteLocationNormalizedLoaded } from "vue-router";
import {
    validateUuid
} from "@/assets/scripts/validator";

function checkSession(): boolean {
    return localStorage.getItem("hash") != "null";
}

function validateCurrentSession(): void {
    if(!checkSession()) {
        window.location.href = "/login";
        return;
    }

    $.post(
        env.host + "/account.php",
        {
            action: "validate",
            hash: localStorage.getItem("hash") as string
        },
        (data)=> {
            if(data.status == 0) {
                localStorage.setItem("username", "null");
                localStorage.setItem("theme", "null");
                localStorage.setItem("hash", "null");

                window.location.href = "/login";
                return;
            }
        }
    );
}

function validateSession(
    routeLoc: RouteLocationNormalizedLoaded
): void {
    const hash: string = localStorage.getItem("hash") as string;
    if(hash == "null" || !validateUuid(hash))
        return;

    $.post(
        env.host + "/account.php",
        {
            action: "validate",
            hash: hash
        },
        (data)=> {
            if(data.status == 1) {
                localStorage.setItem("username", data.username);
                localStorage.setItem("theme", data.theme);

                window.location.href = "/home";
                return;
            }

            if(routeLoc.name != "login")
                window.location.href = "/login";
        }
    );
}

export {
    validateCurrentSession,
    validateSession
};