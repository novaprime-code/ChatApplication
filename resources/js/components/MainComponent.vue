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
          <chat-component 
            v-if="receiver" 
            v-on:callAccepted="showVideo" 
            :receiver="receiver" 
            :sender="loggedInUser" 
            :isCalling="isUserCalling">
          </chat-component>
        </div>
        <call-modal-component 
          v-if="isUserCalling" 
          v-on:callAccepted="showVideo" 
          :caller="caller" 
          :callReceiver="callReceiver" 
          @declineCall="declineCall">
        </call-modal-component>
      </div>
      <video-component 
        v-if="isCallAccepted" 
        v-on:endCall="endCall" 
        :pusherKey="pusherKey" 
        :pusherCluster="pusherCluster" 
        :caller="caller" 
        :callReceiver="callReceiver" 
        :logged_user_id="loggedInUser.id">
      </video-component>
    </div>
  </div>
</template>

<script>
import CallModalComponent from './CallComponent.vue';
import ChatComponent from './ChatComponent.vue';
import UserListComponent from './UserListComponent.vue';
import LoginComponent from './LoginComponent.vue';
import axios from 'axios';

export default {
  components: {
    CallModalComponent,
    ChatComponent,
    UserListComponent,
  },
  props: ['loggedin_user','pusherKey', 'pusherCluster'],
  data() {
    return {
      loggedInUser: null,
      receiver: null,
      isUserCalling: false,
      caller: null,
      callReceiver: null,
      isCallAccepted: false,
      isCallAccepted: false,
    };
  },
  created() {
    this.fetchLoggedInUser();
  },
  methods: {
    handleLogin() {
      this.fetchLoggedInUser(); // Fetch user data after login
    },
    async fetchLoggedInUser() {
      try {
        const response = await axios.get('/api/me');
        console.log("Profile ME", response.data);
        this.loggedInUser = response.data;
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
      return this.loggedInUser !== null;
    },
  }
};
</script>

<style scoped>
/* Add your styles here */
</style>
