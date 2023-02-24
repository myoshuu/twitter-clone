<template>
  <div class="flex justify-center items-center w-[100vw] h-[100vh]">
    <div>
      <div>
        <img class="mb-10" src="@/assets/twitter-logo.svg" alt="" />
        <div class="text-[35px] leading-10 font-medium">
          <h2>Welcome back</h2>
          <h2>to <span class="text-primary font-bold">Twitter</span></h2>
        </div>
        <p class="mt-3 mb-5 text-[#747474]">Login to your account below.</p>
      </div>

      <form action="" @submit.prevent="login">
        <!-- Start form input -->
        <div class="flex flex-col gap-5">
          <input
            type="text"
            placeholder="Username"
            class="input input-bordered w-full max-w-xs"
            v-model="formData.username"
            required
          />
          <input
            type="password"
            placeholder="Password"
            class="input input-bordered w-full max-w-xs"
            v-model="formData.password"
            required
          />
        </div>
        <button
          class="py-2 px-8 border rounded-lg mt-5 text-white bg-primary hover:bg-[#46b4f8] transition-colors duration-300"
        >
          Login
        </button>
        <p class="mt-8">
          Don't have an account?
          <router-link class="text-blue-600" :to="{ name: 'register' }"
            >Register</router-link
          >
        </p>
      </form>
    </div>
  </div>
</template>
<script>
import axios from "axios";
export default {
  data() {
    return {
      formData: {
        username: "",
        password: "",
      },
    };
  },

  methods: {
    /**
     * Login user
     * Get user input and validate with api url and store token in local storage
     */
    async login() {
      await this.axios
        .post(`${this.apiUrl}/auth/login`, this.formData)
        .then((res) => {
          localStorage.setItem("token", res.data.token);
          localStorage.setItem("userId", res.data.user.id);

          this.axios.defaults.headers.common[
            "Authorization"
          ] = `Bearer ${localStorage.token}`;

          this.$router.push({ name: "home" });
        })
        .catch((err) => {
          alert(err.response.data.message);
        });
    },
  },
};
</script>
