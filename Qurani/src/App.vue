<template>
  <simplebar class="app" data-simplebar-auto-hide="true">
    <preloader v-if="!page_loaded" />
    <navbar v-on:toggleMenu="toggleMenu" v-on:reLoadThePage="reLoadThePage" />
    <section class="page_wraper" :class="{ collapsed: menu }">
      <div class="row">
        <div class="col l5">
          <div class="player_container">
            <transition name="slide-fade" mode="out-in">
              <div class="reloading_player" v-if="!playing">
                <img
                  src="https://media.giphy.com/media/6036p0cTnjUrNFpAlr/giphy.gif"
                  alt="loading"
                />
              </div>
              <player
                class="player_now"
                :playerStream="playerStream"
                :getStreamTitle="getStreamTitle"
                v-if="playing"
              />
            </transition>
            <transition name="slide-fade" mode="out-in">
              <div class="info_card" v-if="playing">
                <span>{{ $t("info") }}</span>
                <a href="https://www.mp3quran.net/" target="_blank"
                  >mp3quran.net</a
                >
              </div>
            </transition>
          </div>
        </div>
        <div class="col l7">
          <div class="row pages_container">
            <transition name="slide-fade" mode="out-in">
              <router-view
                :menu="menu"
                :streamLink="streamLink"
                v-on:updateStream="updateStream($event)"
                v-on:reFirePlayer="reFirePlayer"
              />
            </transition>
          </div>
        </div>
      </div>
    </section>
    <sidebar :menu="menu" />
    <!-- Modal Structure For Login and Register -->
    <div v-if="!logged">
      <login-modal />
      <register-modal />
    </div>
  </simplebar>
</template>

<script>
import Materialize from "materialize-css";
import simplebar from "simplebar-vue";
import "simplebar/dist/simplebar.min.css";
import Preloader from "@/components/global/Preloader";
import Navbar from "@/components/global/Navbar";
import Sidebar from "@/components/global/Sidebar";
import LoginModal from "@/components/modals/LoginModal";
import RegisterModal from "@/components/modals/RegisterModal";
import Player from "@/components/Player";
import { mapState } from "vuex";

export default {
  name: 'App',
  components: {
    simplebar,
    Preloader,
    Navbar,
    Sidebar,
    Player,
    LoginModal,
    RegisterModal,
  },
  data() {
    return {
      page_loaded: false,
      menu: true,
      streamLink: "https://server13.mp3quran.net/basit_mjwd/001.mp3",
      streamTitle: "الفاتحة",
      playing: true,
    };
  },
  computed: {
    ...mapState(["logged"]),
    playerStream() {
      return this.streamLink;
    },
    getStreamTitle() {
      return this.streamTitle;
    },
  },
  methods: {
    reLoadThePage(newStatus) {
      this.page_loaded = newStatus;
    },
    toggleMenu() {
      this.menu = !this.menu;
      document.querySelector("body").classList.toggle("openMenu");
    },
    updateStream(track) {
      this.streamLink = track.link || track.radio_url;
      this.streamTitle = track.name;
    },
    reFirePlayer() {
      this.playing = false;
      setTimeout(() => (this.playing = true), 2000);
    },
  },
  mounted() {
    Materialize.AutoInit();

    window.addEventListener("load", () => (this.page_loaded = true));
  },
}
</script>
