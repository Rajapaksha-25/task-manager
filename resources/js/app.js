import './bootstrap';

const API = "http://127.0.0.1:8000/api";

async function register() {
    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const password_confirmation = document.getElementById("password_confirmation").value;

    console.log("Register clicked:", {name, email, password, password_confirmation});

    const res = await fetch(API + "/register", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: JSON.stringify({
            name, email, password, password_confirmation
        })
    });

    const data = await res.json();
    console.log("Response:", data);

    if (res.ok) {
        localStorage.setItem("token", data.access_token);
        window.location.href = "dashboard.php";
    } else {
        alert(data.message || JSON.stringify(data));
    }
}

async function login() {
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    console.log("Login clicked:", {email, password});

    const res = await fetch(API + "/login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: JSON.stringify({email, password})
    });

    const data = await res.json();
    console.log("Response:", data);

    if (res.ok) {
        localStorage.setItem("token", data.access_token);
        window.location.href = "dashboard.php";
    } else {
        alert(data.message || JSON.stringify(data));
    }
}

function logout() {
    localStorage.removeItem("token");
    window.location.href = "login.php";
}

function checkAuth() {
    const token = localStorage.getItem("token");
    if (!token) {
        window.location.href = "login.php";
    }
}

// Make functions global so HTML buttons can call them
window.register = register;
window.login = login;
window.logout = logout;
window.checkAuth = checkAuth;