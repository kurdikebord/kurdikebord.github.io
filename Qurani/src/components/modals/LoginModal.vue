<template>
  <div id="login" class="modal">
    <div class="modal-content">
      <h6 class="modal-title">الدخول إلى حسابى</h6>
      <div class="row">
        <form class="col s12" @submit.prevent="login()">
          <div class="row">
            <div class="input-field col m6">
              <i class="material-icons blue-text prefix">email</i>
              <input
                id="icon_prefix"
                type="email"
                class="validate"
                :class="emailError.status ? 'no-validation' : ''"
                @blur="emailValidation()"
                v-model="email"
                @focus="resetLoginError()"
              />
              <label for="icon_prefix">الأيميل</label>
              <span class="helper-text red-text">{{ emailError.msg }}</span>
            </div>
            <div class="input-field col m6">
              <i class="material-icons blue-text prefix">lock</i>
              <input
                id="icon_telephone"
                type="password"
                class="validate"
                :class="passError.status ? 'no-validation' : ''"
                @blur="paswordValidation()"
                v-model="password"
                @focus="resetLoginError()"
              />
              <label for="icon_telephone">كلمة المرور</label>
              <span class="helper-text red-text">{{ passError.msg }}</span>
            </div>
            <div class="col s12">
              <button type="submit" :disabled="fetchingData" class="btn_style btn_login_modal">
                تسجيل الدخول
                <div class="progress" v-if="fetchingData">
                    <div class="indeterminate"></div>
                </div>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="modal-footer">
      <span class="helper-text red-text">{{ loginErrMsg.msg }}</span>
      <a
        href="javascript:;"
        @click="resetInputs()"
        class="modal-close waves-effect blue-text waves-green btn-flat"
        >إغلاق</a
      >
    </div>
  </div>
</template>

<script>
export default {
  name: "LoginModal",
  data() {
    return {
      fetchingData: false,
      email: null,
      emailError: {
        status: false,
        msg: null,
      },
      password: null,
      passError: {
        status: false,
        msg: null,
      },
      loginErrMsg: {
        status: false,
        msg: null,
      },
    };
  },
  methods: {
    emailValidation() {
      if (!this.email) {
        this.emailError = {
          status: true,
          msg: "هذا الحقل مطلوب",
        };
      } else {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email)) {
          this.emailError = {
            status: false,
            msg: null,
          };
        } else {
          this.emailError = {
            status: true,
            msg: "أدخل ايميلا صحيحا",
          };
        }
      }
    },
    paswordValidation() {
      if (!this.password) {
        this.passError = {
          status: true,
          msg: "أدخل كلمة المرور",
        };
      } else {
        this.passError = {
          status: false,
          msg: null,
        };
      }
    },
    async login() {
      // this.emailValidation();
      // this.paswordValidation();
      // if (!this.emailError.status && !this.passError.status) {

      //   this.fetchingData = true;
        
      //   firebase.auth()
      //     .signInWithEmailAndPassword(this.email, this.password)
      //     .then((res) => {
      //       // Reset Form
      //       this.email = null;
      //       this.password = null;

      //       this.fetchingData = false;

      //       // Get User Data
      //       this.$store.dispatch("GetUserData");

      //       this.$router.replace('/profile');

      //       M.toast({html:   'تم تسجيل الدخول، أهلآ بك  ' });
      //     })
      //     .catch((error) => {
      //       let errMsg = error.message;

      //       if (errMsg.match("no user record")) {
      //         errMsg = "هذا المستخدم غير موجود";
      //       } else if (errMsg.match("password is invalid")) {
      //         errMsg = "كلمة المرور غير صحيحة";
      //       } 

      //       this.loginErrMsg = {
      //         status: true,
      //         msg: errMsg,
      //       };

      //       this.fetchingData = false;
      //     });
      // }
    },
    resetLoginError() {
      this.loginErrMsg = {
        status: false,
        msg: null,
      };
    },
    resetInputs() {
      this.emailError = {
        status: false,
        msg: null,
      }

      this.passError = {
        status: false,
        msg: null,
      }
    }
  },
};
</script>
