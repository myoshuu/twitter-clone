<script setup>
import Layout from "@/layouts/Layout.vue";
</script>
<template>
  <Layout>
    <div class="overflow-y-auto">
      <div class="">
        <div>
          <h3 class="font-bold text-xl p-5 border-b-2">Home</h3>
        </div>
        <div class="h-[6px] bg-grey"></div>
      </div>
      <div v-for="tweet in tweetData">
        <div class="flex gap-5 border-b-2">
          <div class="pl-16 mt-8">
            <Avatar
              placeholder="placeholder"
              img-w="16"
              :data="tweet.user.profile"
              :img-src="`${this.url}/${tweet.user.profile}`"
              :name="subStrName"
            />
          </div>
          <div class="mt-8 mb-10 w-full mr-10">
            <div class="flex justify-between items-center">
              <div class="flex items-center">
                <div class="flex gap-3">
                  <p class="font-semibold">{{ tweet.user.name }}</p>
                  <p class="text-darkGrey">@{{ tweet.user.username }}</p>
                </div>
              </div>
              <div
                v-if="tweet.user.id == userId"
                class="flex gap-3 text-darkGrey"
              >
                <label
                  for="my-modal-3"
                  class="text-darkGrey cursor-pointer"
                  @click="openUpdateTweetModal(tweet.id)"
                  ><i class="fa-solid fa-pen-to-square"></i>
                </label>
                <label
                  for="my-modal-3"
                  class="text-darkGrey cursor-pointer"
                  @click="deleteTweet(tweet.id)"
                  ><i class="fa-solid fa-trash"></i>
                </label>
              </div>
            </div>
            <div>
              <p>{{ tweet.tweet }}</p>
              <img
                v-if="tweet.media"
                class="mt-5 rounded-lg max-w-[350px]"
                :src="`${this.url}/${tweet.media}`"
                alt=""
              />
            </div>
            <div class="mt-3">
              <label
                for="my-modal-3"
                class="text-darkGrey cursor-pointer"
                @click="openCreateCommentModal(tweet.id)"
              >
                <i class="fa-solid fa-comment text-darkGrey text-xl"></i>
              </label>

              <!-- Recent comment -->
              <div class="mt-5">
                <p class="text-[#c7c7c7]">Comment :</p>
                <div v-for="comment in tweet.comment">
                  <div class="flex justify-between mt-3">
                    <div class="flex gap-3">
                      <p class="font-medium">{{ comment.user.name }}</p>
                      <p class="text-darkGrey">@{{ comment.user.username }}</p>
                    </div>
                    <div
                      v-if="tweet.user.id == userId"
                      class="flex gap-3 text-darkGrey"
                    >
                      <label
                        for="my-modal-3"
                        class="text-darkGrey cursor-pointer"
                        @click="openUpdateCommentModal(tweet.id, comment.id)"
                        ><i class="fa-solid fa-pen-to-square"></i>
                      </label>
                      <label
                        for="my-modal-3"
                        class="text-darkGrey cursor-pointer"
                        @click="deleteComment(comment.id)"
                        ><i class="fa-solid fa-trash"></i>
                      </label>
                    </div>
                  </div>
                  <p>{{ comment.comment }}</p>
                  <img
                    class="max-w-[250px] mt-3"
                    :src="`${this.url}/${comment.media}`"
                    alt=""
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal edit tweet -->
      <div class="modal" id="updateTweet">
        <div class="modal-box relative">
          <label
            for="my-modal-3"
            class="btn btn-sm btn-circle absolute right-3 top-2"
            @click="closeUpdateTweetModal"
            >✕</label
          >
          <form
            action=""
            enctype="multipart/form-data"
            @submit.prevent="updateTweet"
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
            <label for="updateTweetMedia">
              <i
                class="fa-solid fa-image text-xl flex justify-end cursor-pointer hover:text-primary transition-colors duration-300"
              ></i>
              <input
                @change="onFileChange"
                type="file"
                name="updateTweetMedia"
                id="updateTweetMedia"
                accept="image/*"
                hidden
              />
            </label>
            <button class="bg-primary text-white py-2 px-8 rounded-full">
              Update
            </button>
          </form>
        </div>
      </div>

      <!-- Modal create comment -->
      <div class="modal" id="createComment">
        <div class="modal-box relative">
          <label
            for="my-modal-3"
            class="btn btn-sm btn-circle absolute right-3 top-2"
            @click="closeCreateCommentModal"
            >✕</label
          >
          <form
            action=""
            enctype="multipart/form-data"
            @submit.prevent="createComment"
          >
            <input
              type="text"
              placeholder="Comment"
              class="input input-bordered w-full"
              v-model="formComment.comment"
            />
            <!-- Image preview -->
            <div class="max-w-full min-h-[200px] rounded-lg border mt-3">
              <img
                v-if="imageUrlComment"
                :src="imageUrlComment"
                class="rounded-lg"
              />
            </div>
            <label for="createCommentMedia">
              <i
                class="fa-solid fa-image text-xl flex justify-end cursor-pointer hover:text-primary transition-colors duration-300"
              ></i>
              <input
                @change="onFileChangeComment"
                type="file"
                name="createCommentMedia"
                id="createCommentMedia"
                accept="image/*"
                hidden
              />
            </label>
            <button class="bg-primary text-white py-2 px-8 rounded-full">
              Reply
            </button>
          </form>
        </div>
      </div>

      <!-- Modal update comment -->
      <div class="modal" id="updateComment">
        <div class="modal-box relative">
          <label
            for="my-modal-3"
            class="btn btn-sm btn-circle absolute right-3 top-2"
            @click="closeUpdateCommentModal"
            >✕</label
          >
          <form
            action=""
            enctype="multipart/form-data"
            @submit.prevent="updateComment"
          >
            <input
              type="text"
              placeholder="Comment"
              class="input input-bordered w-full"
              v-model="formComment.comment"
            />
            <!-- Image preview -->
            <div class="max-w-full min-h-[200px] rounded-lg border mt-3">
              <img
                v-if="imageUrlComment"
                :src="imageUrlComment"
                class="rounded-lg"
              />
            </div>
            <label for="updateCommentMedia">
              <i
                class="fa-solid fa-image text-xl flex justify-end cursor-pointer hover:text-primary transition-colors duration-300"
              ></i>
              <input
                @change="onFileChangeComment"
                type="file"
                name="updateCommentMedia"
                id="updateCommentMedia"
                accept="image/*"
                hidden
              />
            </label>
            <button class="bg-primary text-white py-2 px-8 rounded-full">
              Reply
            </button>
          </form>
        </div>
      </div>
    </div>
  </Layout>
</template>
<script>
import Avatar from "@/components/Avatar.vue";

export default {
  data() {
    return {
      tweetData: {},
      subStrName: "",
      userId: localStorage.userId,

      formTweet: {
        tweet: "",
        media: "",
      },

      formComment: {
        comment: "",
        media: "",
        tweetId: "",
      },

      imageUrl: "",
      imageUrlComment: "",
    };
  },

  methods: {
    /**
     * Get all tweet
     */
    async getTweet() {
      this.axios
        .get(`${this.apiUrl}/tweet`)
        .then((res) => {
          this.tweetData = res.data;
          this.subStrName = res.data[0].user.name.substring(0, 1);
        })
        .catch((err) => {
          alert(err.response.data.message);
        });
    },

    /**
     * Update tweet
     */
    async updateTweet() {
      const id = localStorage.tweetId;
      const formData = new FormData();
      formData.append("_method", "put");
      formData.append("tweet", this.formTweet.tweet);
      if (this.formTweet.media) {
        formData.append("media", this.formTweet.media);
      }

      this.axios
        .post(`${this.apiUrl}/tweet/${id}`, formData)
        .then((res) => {
          this.getTweet();
          this.closeUpdateTweetModal();
        })
        .catch((err) => {
          alert(err.response.data.message);
        });
    },

    /**
     * Delete tweet
     */
    async deleteTweet(id) {
      this.axios
        .delete(`${this.apiUrl}/tweet/${id}`)
        .then((res) => {
          this.$emitter.emit("refreshTweet");
        })
        .catch((err) => {
          alert(err.response.data.message);
        });
    },

    /**
     * Create comment
     */
    async createComment() {
      const formData = new FormData();
      formData.append("comment", this.formComment.comment);
      formData.append("tweet_id", this.formComment.tweetId);
      if (this.formComment.media) {
        formData.append("media", this.formComment.media);
      }

      this.axios
        .post(`${this.apiUrl}/comment`, formData)
        .then((res) => {
          this.getTweet();
          this.closeCreateCommentModal();
        })
        .catch((err) => {
          alert(err.response.data.message);
        });
    },

    /**
     * Update comment
     */
    async updateComment() {
      const id = localStorage.commentId;
      const formData = new FormData();
      formData.append("_method", "put");
      formData.append("comment", this.formComment.comment);
      if (this.formComment.media) {
        formData.append("media", this.formComment.media);
      }

      this.axios
        .post(`${this.apiUrl}/comment/${id}`, formData)
        .then((res) => {
          this.getTweet();
          this.closeUpdateCommentModal();
        })
        .catch((err) => {
          alert(err.response.data.message);
        });
    },

    /**
     * Delete comment
     */
    async deleteComment(id) {
      this.axios
        .delete(`${this.apiUrl}/comment/${id}`)
        .then((res) => {
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
      const file = e.currentTarget.files[0];
      this.imageUrl = URL.createObjectURL(file);
      this.formTweet.media = file;
    },

    /**
     * On file change image (comment)
     * For preview
     */
    onFileChangeComment(e) {
      const file = e.currentTarget.files[0];
      this.imageUrlComment = URL.createObjectURL(file);
      this.formComment.media = file;
    },

    /**
     * Modal function (for open and close modal also for edit data)
     */

    openUpdateTweetModal(tweetId) {
      document.getElementById("updateTweet").classList.add("modal-open");
      localStorage.setItem("tweetId", tweetId);
      const tweet = this.tweetData.find((tweet) => tweet.id == tweetId);
      if (!tweet) {
        return;
      }

      this.formTweet.tweet = tweet.tweet;
      this.imageUrl = `http://localhost:8000/${tweet.media}`;
    },
    closeUpdateTweetModal() {
      document.getElementById("updateTweet").classList.remove("modal-open");
    },

    openCreateCommentModal(tweetId) {
      document.getElementById("createComment").classList.add("modal-open");
      this.formComment.tweetId = tweetId;
    },
    closeCreateCommentModal() {
      document.getElementById("createComment").classList.remove("modal-open");
    },

    openUpdateCommentModal(tweetId, commentId) {
      console.log(commentId);
      document.getElementById("updateComment").classList.add("modal-open");
      localStorage.setItem("commentId", commentId);
      const tweet = this.tweetData.find((t) => t.id === tweetId);
      if (!tweet) return;

      const comment = tweet.comment.find((comment) => comment.id == commentId);
      console.log(this.tweetData);
      if (!comment) {
        return;
      }

      this.formComment.comment = comment.comment;
      this.imageUrlComment = `http://localhost:8000/${comment.media}`;
    },
    closeUpdateCommentModal() {
      document.getElementById("updateComment").classList.remove("modal-open");
    },
    /******/
  },

  created() {
    this.getTweet();
  },

  mounted() {
    this.$emitter.on("refreshTweet", this.getTweet);
  },

  unmounted() {
    this.$emitter.off("refreshTweet", this.getTweet);
  },

  components: {
    Avatar,
  },
};
</script>
