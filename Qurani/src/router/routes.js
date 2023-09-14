import Home from "@/views/Home.vue";
import Reciter from "@/views/Reciter.vue";
import Live from "@/views/Live.vue";
import Favorite from "@/views/Favorite.vue";
import Playlist from "@/views/Playlist.vue";
import Store from "@/store";

const routes = [
  { path: "/", name: "home", component: Home },
  { path: "/reciter", name: "reciter", component: Reciter },
  { path: "/live", name: "live", component: Live },
  { path: "/favorite", name: "favorite", component: Favorite },
  { path: "/playlist", name: "playlist", component: Playlist },
  { 
    path: "/profile", 
    name: "profile", 
    // lazy load profile page component
    component: () => import(/* webpackChunkName: "profile" */ '@/views/Profile.vue'),
    beforeEnter(to, from, next) {
      try {
        if (Store.state.logged) {
          next()
        }
      } catch (e) {
        next({
        path: '/'
        })
      }
    }  
  },
  { path: "*", component: Home },
];

export default routes;