<script setup>
import Layout from "@/layouts/Layout.vue";
</script>

<template>
  <Layout>
    <div class="overflow-y-auto">
      <div>
        <div>
          <h3 class="font-bold text-xl p-5 border-b-2">Profile</h3>
        </div>
        <div class="h-[200px] bg-slate-200 relative">
          <div class="avatar placeholder p-10 absolute top-28">
            <div class="w-24 rounded-full ring bg-grey">
              <span class="text-4xl"></span>

              <img :src="`${this.url}/${authUserData.profile}`" />
            </div>
          </div>
          <div class="absolute bottom-0 right-20 top-60">
            <label
              for="my-modal-3"
              class="py-2 px-5 rounded-full text-primary font-semibold border-primary border-2 hover:bg-primary hover:text-white transition-colors duration-300 cursor-pointer"
              @click="openModalProfile"
              >Edit profile</label
            >
            <!-- Modal Edit Profile -->
            <div class="modal" id="profileModal">
              <div class="modal-box relative">
                <label
                  for="my-modal-3"
                  class="btn btn-sm btn-circle absolute right-2 top-2"
                  @click="closeModalProfile"
                  >✕</label
                >

                <!-- Form Modal Insert Profile -->
                <form
                  @submit.prevent="updateProfile(authUserData.id)"
                  action=""
                  enctype="multipart/form-data"
                  class="w-[90%] relative"
                >
                  <input
                    type="text"
                    name="name"
                    placeholder="Name"
                    class="input input-bordered w-full mb-5"
                    v-model="formData.name"
                    required
                  />
                  <!-- Image Preview -->
                  <div class="max-w-full min-h-[200px] rounded-lg border">
                    <img :src="imageUrl" alt="" class="rounded-lg w-full" />
                  </div>

                  <label for="profile">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="absolute right-0 w-6 h-6 cursor-pointer hover:text-primary transition-colors duration-300 mt-1"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"
                      />
                    </svg>
                    <input
                      type="file"
                      accept=".jpg,.png"
                      class="hidden"
                      name="profile"
                      id="profile"
                      @change="onFileChange"
                    />
                  </label>
                  <input
                    type="text"
                    name="bio"
                    placeholder="Bio"
                    class="input input-bordered w-full mt-10"
                    v-model="formData.bio"
                    required
                  />
                  <button
                    class="py-2 px-8 mt-5 border rounded-full text-white font-semibold bg-primary disabled:bg-gray-300 disabled:cursor-not-allowed"
                  >
                    Post
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="text-lg mt-20 pl-10">
          <p class="font-medium">{{ authUserData.name }}</p>
          <p class="text-base text-[#CECECE]">@{{ authUserData.username }}</p>
          <p class="text-lg mt-2">{{ authUserData.bio }}</p>
        </div>
      </div>
    </div>
  </Layout>
</template>
<script>
export default {
  data() {
    return {
      authUserData: {},
      formData: {
        name: "",
        profile: "",
        bio: "",
      },
      imageUrl: "",
    };
  },
  methods: {
    /**
     * Get the authenticated user data
     */
    me() {
      this.axios
        .get(`${this.apiUrl}/auth/me`)
        .then((res) => {
          this.authUserData = res.data;
          this.subStrName = res.data.name.substring(0, 1);
        })
        .catch((err) => {
          alert(err.response.data.message);
        });
    },

    /**
     * Edit Profile Modal
     */
    async updateProfile(id) {
      const formData = new FormData();
      formData.append("name", this.formData.name);
      formData.append("_method", "put");
      formData.append("bio", this.formData.bio);
      if (this.formData.profile) {
        formData.append("profile", this.formData.profile);
      }

      await this.axios
        .post(`${this.apiUrl}/auth/profile/${id}`, formData)
        .then((res) => {
          this.me();
          this.closeModalTweet();
          this.$emitter.emit("refreshData");
          this.$emitter.emit("refreshUser");
        })
        .catch((err) => {
          alert(err.response.data.message);
        });
    },

    onFileChange(e) {
      const file = e.target.files[0];
      this.imageUrl = URL.createObjectURL(file);
      this.formData.profile = file;
    },

    openModalProfile() {
      document.getElementById("profileModal").classList.add("modal-open");
    },
    closeModalProfile() {
      document.getElementById("profileModal").classList.remove("modal-open");
    },
  },

  created() {
    this.me();
  },
};
</script>
