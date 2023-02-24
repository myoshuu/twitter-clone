<template>
  <div class="flex justify-center items-center w-[100vw] h-[100vh]">
    <div>
      <form action="" @submit.prevent="register">
        <div>
          <img class="mb-10" src="@/assets/twitter-logo.svg" alt="" />
          <div class="text-[35px] leading-10 font-medium">
            <h2>Create your</h2>
            <h2>account</h2>
          </div>
          <p class="mt-3 mb-5 text-[#747474]">
            Register your account to get started.
          </p>
        </div>

        <!-- Start form input -->
        <div class="flex flex-col gap-5">
          <input
            type="text"
            placeholder="Name"
            class="input input-bordered w-full max-w-xs"
            v-model="formData.name"
            required
          />
          <input
            type="text"
            placeholder="Username"
            class="input input-bordered w-full max-w-xs"
            v-model="formData.username"
            required
          />
          <input
            type="text"
            placeholder="Email"
            class="input input-bordered w-full max-w-xs"
            v-model="formData.email"
            required
          />
          <input
            type="text"
            placeholder="Bio"
            class="input input-bordered w-full max-w-xs"
            v-model="formData.bio"
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
          Register
        </button>
        <p class="mt-8">
          Already have an account?
          <router-link class="text-blue-600" :to="{ name: 'login' }"
            >Login</router-link
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
        name: "",
        username: "",
        email: "",
        bio: "",
        password: "",
      },
    };
  },

  methods: {
    async register() {
      await axios
        .post(`${this.apiUrl}/auth/register`, this.formData)
        .then((res) => {
          this.$router.push({ name: "login" });
        })
        .catch((err) => {
          alert(err.response.data.message);
        });
    },
  },
};
</script>
