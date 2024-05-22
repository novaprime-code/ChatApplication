import { createApp } from "vue";
import VueChatScroll from "vue3-chat-scroll";
import MainComponent from "./components/MainComponent.vue";
import UserListComponent from "./components/UserListComponent.vue";
import ChatComponent from "./components/ChatComponent.vue";
import VideoComponent from "./components/VideoComponent.vue";
import LoginComponent from "./components/VideoComponent.vue";
import Echo from "laravel-echo";
import Pusher from "pusher-js";
import axios from "axios";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

window.Pusher = Pusher;
window.Echo = new Echo({
  broadcaster: "pusher",
  key: "aaa172717ea6ff3d50f49a6",
  cluster: "mt1",
  wsHost: window.location.hostname,
  wsPort: 6001,
  wssPort: 6001,
  forceTLS: false,
  disableStats: true,
});

const app = createApp({
  created() {
    window.Echo.channel("updateUserStatus").listen(
      "UpdateUserOnlineStatus",
      (e) => {
        console.log(e);
      }
    );
  },
});

app.use(VueChatScroll);

app.component("main-component", MainComponent);
app.component("login-component", LoginComponent);
app.component("user-list-component", UserListComponent);
app.component("chat-component", ChatComponent);
app.component("video-component", VideoComponent);

app.mount("#app");
