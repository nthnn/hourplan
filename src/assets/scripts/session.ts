import $ from "jquery";
import env from "@/assets/scripts/config";
import { type Router, type RouteLocationNormalizedLoaded } from "vue-router";
import {
    validateUuid
} from "@/assets/scripts/validator";

function checkSession() {
    const hash = localStorage.getItem("hash");
    return hash != "null";
}

function validateCurrentSession(router: Router) {
    if(!checkSession()) {
        router.push("/login");
        return;
    }

    $.post(
        env.host + "/account.php",
        {
            action: "validate",
            hash: localStorage.getItem("hash")
        },
        (data)=> {
            if(data.status == 0) {
                localStorage.setItem("username", "null");
                localStorage.setItem("theme", "null");
                localStorage.setItem("hash", "null");

                router.push("/login");
                return;
            }
        }
    );
}

function validateSession(router: Router, routeLoc: RouteLocationNormalizedLoaded) {
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

                router.push("/home");
                return;
            }

            console.log(routeLoc.name);
            console.log(routeLoc.name != "login");
            if(routeLoc.name != "login")
                router.push("/login");
        }
    );
}

export {
    validateCurrentSession,
    validateSession
};