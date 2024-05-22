<template>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h2 class="card-title">Login</h2>
          <form @submit.prevent="login">
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" v-model="email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" v-model="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
          </form>
      <div v-if="loginErrorMessage" class="alert alert-danger" role="alert">
        {{ loginErrorMessage }}
      </div>
        </div>
      </div>
    </div>
  </div>
</div>

</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      email: '',
      password: '',
        loginErrorMessage: '',
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('api/login', {
          email: this.email,
          password: this.password,
        });

        console.log(response.data);
        // Handle successful login
         this.$emit('loggedIn');
      } catch (error) {
        console.error(error);
        // Handle login error
          this.loginErrorMessage = 'Login failed. Please check your credentials and try again.\n'+error;

      }
    },
  },
  mounted() {
    axios.get('api/sanctum/csrf-cookie').then(response => {
      // Add XSRF-TOKEN to every request
      axios.defaults.headers.common['X-XSRF-TOKEN'] = response.config.headers['X-XSRF-TOKEN'];
    });
  },
};
</script>