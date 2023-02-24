<template>
  <div class="border overflow-y-auto w-100">
    <div class="">
      <form class="p-5" action="" @submit.prevent="filter">
        <div class="relative">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="#5B7083"
            class="w-6 h-6 absolute top-3 left-5"
          >
            <path
              fill-rule="evenodd"
              d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z"
              clip-rule="evenodd"
            />
          </svg>
          <input
            type="text"
            class="border-grey border-1 py-3 rounded-full pl-14 pr-10 bg-grey placeholder-darkGrey"
            placeholder="Search twitter"
            v-model="name"
          />
        </div>
        <button hidden>search</button>
      </form>
    </div>
    <div class="px-10">
      <p class="font-medium">Tweet's</p>
      <div class="mt-3">
        <div v-for="data in filteredData">
          <div v-for="tweet in data.tag_tweet">
            <div class="flex gap-4 mb-10">
              <Avatar
                placeholder="placeholder"
                img-w="12"
                :data="tweet.tweet.user.profile"
                :img-src="`${this.url}/${tweet.tweet.user.profile}`"
                :name="tweet.tweet.user.name.substring(0, 1)"
                class="absolute"
              />
              <div class="ml-16">
                <div class="flex gap-2">
                  <p class="font-medium">{{ tweet.tweet.user.name }}</p>
                  <p class="text-darkGrey">@{{ tweet.tweet.user.username }}</p>
                </div>
                <p class="mb-3">{{ tweet.tweet.tweet }}</p>
                <img
                  class="rounded-lg"
                  :src="`${this.url}/${tweet.tweet.user.profile}`"
                  alt=""
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="px-10">
      <p class="font-medium">Comment's</p>
      <div class="mt-3">
        <div v-for="data in filteredData">
          <div v-for="comment in data.tag_comment">
            <div class="flex gap-4 mb-10">
              <Avatar
                placeholder="placeholder"
                img-w="12"
                :data="comment.comment.user.profile"
                :img-src="`${this.url}/${comment.comment.user.profile}`"
                :name="comment.comment.user.name.substring(0, 1)"
                class="absolute"
              />
              <div class="ml-16">
                <div class="flex gap-2">
                  <p class="font-medium">{{ comment.comment.user.name }}</p>
                  <p class="text-darkGrey">
                    @{{ comment.comment.user.username }}
                  </p>
                </div>
                <p class="mb-3">{{ comment.comment.comment }}</p>
                <img
                  class="rounded-lg"
                  :src="`${this.url}/${comment.comment.user.profile}`"
                  alt=""
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Avatar from "../components/Avatar.vue";

export default {
  data() {
    return {
      name: "",
      filteredData: {},
    };
  },
  methods: {
    async filter() {
      await this.axios
        .post(`${this.apiUrl}/tweet/filter`, { name: this.name })
        .then((res) => {
          this.filteredData = res.data;
        })
        .catch((err) => {
          console.error(err);
        });
    },
  },
  components: { Avatar },
};
</script>
