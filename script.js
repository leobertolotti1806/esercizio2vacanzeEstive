let currentUser = null;
let currentChatUser = null;

function showLogin() {
    document.getElementById("login").classList.remove("hidden");
    document.getElementById("register").classList.add("hidden");
}

function showRegister() {
    document.getElementById("register").classList.remove("hidden");
    document.getElementById("login").classList.add("hidden");
}

function selectUser(user) {
    currentChatUser = user;
    loadMessages();
}

async function loadUsers() {
    let response = await fetch("./PHP/controller.php?action=getUsers");

    let users = await response.json();
    let usersDiv = document.getElementById("users");
    usersDiv.innerHTML = "";

    for (let user of users) {
        if (user.id !== currentUser.id) {
            let userDiv = document.createElement("div");
            userDiv.classList.add("user");
            userDiv.innerHTML = user[1];
            userDiv.onclick = () => selectUser(user);
            usersDiv.appendChild(userDiv);
        }
    }
}

async function loadMessages() {
    let response = await fetch(`./PHP/controller.php?action=getMessages&mittente_id=${currentUser.id}&destinatario_id=${currentChatUser[0]}`);

    let messages = await response.json();
    let divMessaggi = document.getElementById("messages");

    divMessaggi.innerHTML = "";

    if (messages.length > 0) {
        for (let msg of messages) {
            let msgDiv = document.createElement("div");
            msgDiv.classList.add("message");
            msgDiv.classList.add(msg[1] == currentUser.id ? "sent" : "received");
            msgDiv.innerHTML = msg[3];
            divMessaggi.appendChild(msgDiv);
        }
    } else {
        alert("Non sono presenti messaggi!!!");
    }
}

document.getElementById("showRegister").addEventListener("click", showRegister);
document.getElementById("showLogin").addEventListener("click", showLogin);

document.getElementById("loginBtn").addEventListener("click", async function () {
    let username = document.getElementById("login-username").value;
    let password = document.getElementById("login-password").value;

    let response = await fetch(`./PHP/controller.php?action=login&username=${username}&password=${password}`);

    let data = await response.json();

    if (data.error != undefined) {
        alert("ERRORE: " + data.error);
    } else if (data.id != undefined) {
        currentUser = data;
        document.getElementById("login").classList.add("hidden");
        document.getElementById("chat").classList.remove("hidden");
        loadUsers();
    } else {
        alert("Credenziali sbagliate/non esistenti!!!");
    }
});

document.getElementById("registerBtn").addEventListener("click", async function () {
    let username = document.getElementById("register-username").value;
    let password = document.getElementById("register-password").value;

    let response = await fetch(`./PHP/controller.php?action=register&username=${username}&password=${password}`);

    let data = await response.json();

    if (data.error != undefined) {
        alert("ERRORE: " + data.error);
    } else {
        alert("Registrazione avvenuta con successo!");
        showLogin();
    }
});

document.getElementById("sendBtn").addEventListener("click", async function () {
    let messageInput = document.getElementById("message-input");
    let message = messageInput.value;

    if (message.trim() !== "") {
        let response = await fetch(
            `./PHP/controller.php?action=addMessage&mittente_id=${currentUser.id}&destinatario_id=${currentChatUser[0]}&messaggio=${message}`);

        let data = await response.text();

        if (data.error) {
            alert(data.error);
        } else {
            messageInput.value = "";
            loadMessages();
        }
    } else {
        alert("Messaggio vuoto!!!");
    }
});