import $ from "jquery";
import env from "@/assets/scripts/config";
import Session from "@/assets/scripts/user";
import {
    validateUuid
} from "@/assets/scripts/validator";

function checkSession() {
    const hash = localStorage.getItem("hash");
    return hash != "null";
}

function validateCurrentSession() {
    if(!checkSession()) {
        location.href = "/";
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

                location.href = "/";
                return;
            }
        }
    );
}

function validateSession() {
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

                location.href = "/home";
                return;
            }

            location.href = "/";
        }
    );
}

export {
    validateCurrentSession,
    validateSession
};