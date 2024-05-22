<template>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h2 class="card-title">Register</h2>
          <form @submit.prevent="register">
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control" id="name" v-model="name" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" v-model="email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" v-model="password" required>
            </div>
            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="password_confirmation" v-model="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
          </form>
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
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
    };
  },
  methods: {
    async register() {
      try {
        const response = await axios.post('/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
        });

        console.log(response.data);
        // Handle successful registration
      } catch (error) {
        console.error(error);
        // Handle registration error
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