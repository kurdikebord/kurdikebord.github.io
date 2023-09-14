<template>
  <!-- <div class="fixed-sidebar" :class="{ menu: menu }"> -->
    <simplebar class="fixed-sidebar" :class="{ menu: menu }" data-simplebar-auto-hide="true">
      <div class="menu_wraper">
        <router-link to="/" exact class="btn_style">
          <span>جميع القرّاء</span>
          <span class="btn_icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-book-open"
            >
              <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
              <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
            </svg>
          </span>
        </router-link>
        <router-link to="/live" class="btn_style">
          <span>البث المباشر</span>
          <span class="btn_icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <circle cx="12" cy="12" r="2"></circle>
              <path
                d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14"
              ></path>
            </svg>
          </span>
        </router-link>
        <router-link to="/favorite" class="btn_style">
          <span> القرّاء المفضلون</span>
          <span class="btn_icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-heart"
            >
              <path
                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"
              ></path>
            </svg>
          </span>
        </router-link>
        <router-link to="/playlist" class="btn_style">
          <span>قائمة الانتظار</span>
          <span class="btn_icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-list"
            >
              <line x1="8" y1="6" x2="21" y2="6"></line>
              <line x1="8" y1="12" x2="21" y2="12"></line>
              <line x1="8" y1="18" x2="21" y2="18"></line>
              <line x1="3" y1="6" x2="3.01" y2="6"></line>
              <line x1="3" y1="12" x2="3.01" y2="12"></line>
              <line x1="3" y1="18" x2="3.01" y2="18"></line>
            </svg>
          </span>
        </router-link>
        <div class="profile_controls">
          <a
            href="#login"
            class="btn_login modal-trigger"  v-if="!logged">
            <span>تسجيل الدخول</span>
            <span class="btn_icon">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-log-in"
              >
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                <polyline points="10 17 15 12 10 7"></polyline>
                <line x1="15" y1="12" x2="3" y2="12"></line>
              </svg>
            </span>
          </a>
          <a href="#register" class="btn_login modal-trigger" v-if="!logged">
            <span>حساب جديد</span>
            <span class="btn_icon">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-user-plus"
              >
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="8.5" cy="7" r="4"></circle>
                <line x1="20" y1="8" x2="20" y2="14"></line>
                <line x1="23" y1="11" x2="17" y2="11"></line>
              </svg>
            </span>
          </a>
          <transition name="slide-fade" mode="out-in">
              <div class="row" v-if="logged">
                <div class="col s12 m6">
                  <div class="card">
                    <div class="card-image">
                      <img :src="user.imgUrl ? user.imgUrl : defaultImage" />
                      <a class="btn-floating halfway-fab waves-effect waves-light dropdown-trigger" data-target='dropdownProfile'
                        ><i class="material-icons blue">build</i></a
                      >
                    </div>
                    <div class="card-content">
                          <span class="user_name">{{ user.name }}</span>
                    </div>
                  </div>
                  <ul id='dropdownProfile' class='dropdown-content'>
                    <li @click="openProfile()"><a href="javascript: ;" class="blue-text"><i class="material-icons -text">persone</i></a></li>
                    <li class="divider" tabindex="-1"></li>
                    <li @click="logout()"><a href="javascript: ;" class="blue-text"><i class="material-icons -text">power_settings_new</i></a></li>
                  </ul>
                </div>
              </div>
          </transition>
        </div>
      </div>
      <div class="footer">
        <small>
          Developed with
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="12"
            height="10"
            viewBox="0 0 24 24"
            fill="red"
            stroke="red"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <path
              d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"
            ></path>
          </svg>
          by <a href="https://www.linkedin.com/in/redaawwad/" target="_blank">Reda Awaad</a> | {{ year }}
        </small>
        <small>
          نسألكم الدعاء لوالديَ
        </small>
      </div>
    </simplebar>
  <!-- </div> -->
</template>

<script>
import simplebar from "simplebar-vue";
import "simplebar/dist/simplebar.min.css";
import defaultImg from '@/assets/imgs/default-profile.png';
import { mapState } from "vuex";

export default {
  name: "Sidebar",
  components: {simplebar},
  props: ["menu"],
  data() {
    return {
      defaultImage: defaultImg
    }
  }, 
  computed: {
    ...mapState(['logged', 'user']),
    year() {
      return new Date()?.getFullYear() || '2020';
    }
  },
  methods: {
    changeLanguage() {
      this.$store.state.loading = true;

      const currLang = this.$i18n.locale;

      setTimeout(() => {
        if (currLang === "en") this.$i18n.locale = "ar";
        else this.$i18n.locale = "en";

        document.querySelector("body").classList.toggle("rtl");

        this.$store.state.loading = false;
      }, 2500);
    },
    openProfile() {
      if(this.$store.state.logged) {
        this.$router.replace('/profile');
      }
    },
    logout() {

    }
  },
};
</script>

