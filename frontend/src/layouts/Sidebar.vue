<template>
  <div class="py-20 border-r-2 h-[100vh] flex">
    <div class="flex flex-col justify-between">
      <div>
        <img src="@/assets/twitter-logo.svg" alt="" />
        <div class="mt-10 flex flex-col gap-5">
          <router-link :to="{ name: 'home' }">
            <div
              class="flex items-center gap-5 text-[20px] font-medium"
              :class="
                this.$route.name === 'home' ? 'text-primary' : 'text-black'
              "
            >
              <i class="fa-solid fa-house text-2xl"></i>
              <p>Home</p>
            </div>
          </router-link>
          <router-link :to="{ name: 'profile' }">
            <div
              class="flex items-center gap-7 text-[20px] font-medium"
              :class="
                this.$route.name === 'profile' ? 'text-primary' : 'text-black'
              "
            >
              <i class="fas fa-user text-2xl"></i>
              <p>Profile</p>
            </div>
          </router-link>
        </div>
        <!-- Modal create tweet -->
        <div class="mt-8">
          <label
            for="my-modal-3"
            class="bg-primary text-white py-2 px-10 rounded-lg font-semibold cursor-pointer"
            @click="openCreateTweetModal"
            >Tweet</label
          >
        </div>

        <!-- Tweet modal body -->
        <div class="modal" id="createTweet">
          <div class="modal-box relative">
            <label
              for="my-modal-3"
              class="btn btn-sm btn-circle absolute right-3 top-2"
              @click="closeCreateTweetModal"
              >✕</label
            >
            <form
              action=""
              enctype="multipart/form-data"
              @submit.prevent="createTweet"
            >
              <input
                type="text"
                placeholder="Tweet"
                class="input input-bordered w-full"
                v-model="formTweet.tweet"
              />
              <!-- Image preview -->
              <div class="max-w-full min-h-[200px] rounded-lg border mt-3">
                <img v-if="imageUrl" :src="imageUrl" class="rounded-lg" />
              </div>
              <label for="createTweetMedia">
                <i
                  class="fa-solid fa-image text-xl flex justify-end cursor-pointer hover:text-primary transition-colors duration-300"
                ></i>
                <input
                  @change="onFileChange"
                  type="file"
                  name="createTweetMedia"
                  id="createTweetMedia"
                  accept="image/*"
                  hidden
                />
              </label>
              <button class="bg-primary text-white py-2 px-8 rounded-full">
                Tweet
              </button>
            </form>
          </div>
        </div>
      </div>

      <div class="mt-8"></div>
      <!-- Profile Start -->
      <div class="flex gap-8 items-center justify-around relative">
        <Avatar
          placeholder="placeholder"
          img-w="12"
          :data="authUserData.profile"
          :img-src="`${this.url}/${authUserData.profile}`"
          :name="subStrName"
        />
        <div>
          <p class="text-lg font-semibold">{{ authUserData.name }}</p>
          <p class="text-sm text-gray-500">@{{ authUserData.username }}</p>
        </div>
        <i
          @click="showModalLogout"
          class="fa-solid fa-gear text-xl cursor-pointer hover:text-primary transition-colors duration-300"
        ></i>

        <!-- Modal for logout -->
        <div
          class="absolute top-[-4rem] right-0 flex hidden flex-col rounded-lg cursor-pointer border"
          id="modalLogout"
        >
          <div
            @click.prevent="logout"
            class="flex items-center gap-5 hover:bg-red-400 hover:text-white transition-colors duration-300 px-8 py-2 rounded-lg"
          >
            <i class="fa-solid fa-right-from-bracket"></i>
            <p class="font-medium">Logout</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Avatar from "@/components/Avatar.vue";
import { getTransitionRawChildren } from "vue";

export default {
  data() {
    return {
      authUserData: {},
      subStrName: "",

      // Create tweet
      formTweet: {
        tweet: "",
        media: "",
      },

      // For preview image
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
     * Logout system
     * Clear all item in localstorage
     */
    logout() {
      this.axios
        .post(`${this.apiUrl}/auth/logout`)
        .then((res) => {
          this.$router.push({ name: "home" });
          localStorage.clear();
        })
        .catch((err) => {
          alert(err.response.data.message);
        });
    },

    /**
     * Create tweet
     * Insert data into database based on user input
     */
    createTweet() {
      const formData = new FormData();
      formData.append("tweet", this.formTweet.tweet);
      formData.append("media", this.formTweet.media);
      if (!this.formTweet.media) {
        formData.append("media", "");
      }

      this.axios
        .post(`${this.apiUrl}/tweet`, formData)
        .then((res) => {
          this.closeCreateTweetModal();
          this.$emitter.emit("refreshTweet");
        })
        .catch((err) => {
          alert(err.response.data.message);
        });
    },

    /**
     * On file change image
     * For preview
     */
    onFileChange(e) {
      const file = e.target.files[0];
      this.imageUrl = URL.createObjectURL(file);
      this.formTweet.media = file;
    },

    /**
     * Modal function (for open and close modal)
     */

    openCreateTweetModal() {
      document.getElementById("createTweet").classList.add("modal-open");
    },
    closeCreateTweetModal() {
      document.getElementById("createTweet").classList.remove("modal-open");
    },

    showModalLogout() {
      document.getElementById("modalLogout").classList.toggle("hidden");
    },

    /******/
  },

  mounted() {},

  created() {
    this.me();
  },
  components: { Avatar },
};
</script>
