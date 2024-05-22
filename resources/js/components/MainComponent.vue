<template>
  <div>
    <div v-if="!isLoggedIn">
      <!-- Render the LoginComponent here -->
      <login-component @loggedIn="handleLogin"></login-component>
    </div>
    <div v-else>
      <div class="h-100">
        <div class="row m-0 p-0 h-100" v-if="!isCallAccepted">
          <user-list-component @showChat="showUserChat"></user-list-component>
          <chat-component v-if="receiver" v-on:callAccepted="showVideo" :receiver="receiver" :sender="loggedInUser" :isCalling="isUserCalling"></chat-component>
        </div>
        <call-modal-component v-if="isUserCalling" v-on:callAccepted="showVideo" :caller="caller" :callReceiver="callReceiver" @declineCall="declineCall"></call-modal-component>
      </div>
      <video-component v-if="isCallAccepted" v-on:endCall="endCall" :pusherKey="pusherKey" :pusherCluster="pusherCluster" :caller="caller" :callReceiver="callReceiver" :logged_user_id="loggedInUser"></video-component>
    </div>
  </div>
</template>

<script>
import CallModalComponent from './CallComponent.vue';
import ChatComponent from './ChatComponent.vue';
import UserListComponent from './UserListComponent.vue';
import LoginComponent from './LoginComponent.vue';
import axios from 'axios';
import Echo from 'laravel-echo';

export default {
  components: {
    CallModalComponent,
    ChatComponent,
    UserListComponent,
    LoginComponent,
  },
  props: ['loggedInUser', 'pusherKey', 'pusherCluster'],
  data() {
    return {
      isLoggedIn: false,
      receiver: null,
      isUserCalling: false,
      caller: null,
      callReceiver: null,
      isCallAccepted: false,
            showRegisterLink: true, // or false, depending on your requirements
      showRegister: false, // or false, depending on your requirements
    };
  },
  created() {
    // ... (existing code)
    this.fetchLoggedInUser();

  },
  methods: {

    handleLogin() {
      this.isLoggedIn = true;
    },
    async fetchLoggedInUser() {
      try {
        const response = await axios.get('/api/me');
        console.log("Profile ME")
        this.loggedin_user = response.data;
      } catch (error) {
        console.error('Error fetching logged in user:', error);
      }
    },

    showUserChat(receiver) {
      this.receiver = receiver;
    },
    declineCall() {
      this.isUserCalling = false;
    },
    showVideo(user) {
      if (user.callReceiver) {
        this.caller = user.caller;
        this.callReceiver = user.callReceiver;
      }
      this.isUserCalling = false;
      this.isCallAccepted = true;
    },
    endCall() {
      this.isUserCalling = false;
      this.isCallAccepted = false;
      let userId = this.loggedInUser.id == this.caller.id ? this.callReceiver.id : this.caller.id;
      axios.post('/end-call', { userId: userId })
        .then((res) => {
          console.log(res);
        });
    },
  },
computed: {
  isLoggedIn() {
    // Return a computed value based on some condition
    return this.loggedInUser !== null;
  },
  showRegisterLink() {
    // Return a computed value based on some condition
    return !this.isLoggedIn;
  },
  showRegister() {
    // Return a computed value based on some condition
    return !this.isLoggedIn;
  }
}
};
</script>