<template>
    <nav class="navbar_container white">
    <div class="nav-wrapper">
      <router-link to="/" class="brand-logo blue-text">{{ $t('logo') }}</router-link>
      <ul class="right">
        <li><a href="javascript:;" @click="toggleMenu" class="blue-text"><i class="material-icons">menu</i></a></li>
        <li><a href="javascript:;" class="blue-text dropdown-trigger" data-target='dropdown1'><i class="material-icons">more_vert</i></a></li>
        <!-- Dropdown Structure -->
        <ul id='dropdown1' class='dropdown-content'>
          <!-- <li><a href="#!" class="blue-text">English</a></li>
          <li><a href="#!" class="blue-text">عربى</a></li>
          <li class="divider" tabindex="-1"></li> -->
          <li @click="changeTheme('light')"><a href="javascript: ;" class="blue-text">ساطع</a></li>
          <li @click="changeTheme('dark')"><a href="javascript: ;" class="black-text">غامق</a></li>
        </ul>
      </ul>
    </div>
  </nav>
</template>

<script>
  import { mapState } from 'vuex';

  export default {
    name: 'Navbar',
    data() {
      return {
        // data here
      }
    },
    computed: {
      ...mapState(['logged', 'user'])
    },
    methods: {
      toggleMenu() {
        this.$emit('toggleMenu')
      },
      changeTheme(theme) {
          if(theme === 'dark' && window.localStorage.getItem('theme_for_qurani') !== 'dark') {
            this.$emit('reLoadThePage', false);
            window.localStorage.setItem('theme_for_qurani', theme);

            this.$store.dispatch('triggerThemeData');

            // if(this.logged) {

            //     updateDataCollection('profiles', this.user.uid, {
            //         settings: {theme: theme}
            //     } ).then( res => {

            //     M.toast({html:   res });

            //     }).catch(err => console.log(err));
            // }

            setTimeout(() => this.$emit('reLoadThePage', true), 2000);

          } else if(theme === 'light' && window.localStorage.getItem('theme_for_qurani') !== 'light') {
              this.$emit('reLoadThePage', false);
              window.localStorage.setItem('theme_for_qurani', theme);

            this.$store.dispatch('triggerThemeData');

            if(this.logged) {

                updateDataCollection('profiles', this.user.uid, {
                    settings: { theme: theme }
                } ).then( res=> {

                M.toast({html:   res });
                
                  }).catch(err => console.log(err));
            }
            setTimeout(() => this.$emit('reLoadThePage', true), 2000);
          }
      }
    },
    mounted() {
      this.$store.dispatch('triggerThemeData');

      
    }
  }
</script>
