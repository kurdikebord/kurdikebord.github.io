<template>
  <div class="playlist">
    <search />
    <div class="section_content" v-if="!logged">
          <div class="no_login_msg">
            يجب تسجيل الدخول أولا 
          </div>
    </div>
    <div class="section_content" v-if="logged">
          <div class="no_login_msg">
            لا توجد نتائج
          </div>
    </div>
  </div>
</template>

<script>
  import Search from '@/components/global/Search';
  import {mapState} from 'vuex';

  export default {
    name: 'Playlist',
    components: {Search},
    computed: {
      ...mapState(['logged', 'user'])
    }
  }
</script>
