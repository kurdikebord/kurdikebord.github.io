<template>
  <div id="register" class="modal">
    <div class="modal-content">
      <h6 class="modal-title">تسجيل حساب جديد</h6>
      <div class="row">
        <form class="col s12" @submit.prevent="register()">
          <div class="row">
            <div class="input-field col m6">
              <i class="material-icons blue-text prefix">person</i>
              <input
                
                type="text"
                class="validate"
                :class="validationsError.fullName ? 'no-validation' : ''"
                @blur="validations('fullName')"
                v-model="user.fullName"
                @focus="resetRegisterError()"
              />
              <label for="icon_prefix">الاسم كاملآ</label>
              <span class="helper-text red-text">{{ validationsError.fullName  }}</span>
            </div>
            <div class="input-field col m6">
              <i class="material-icons blue-text prefix">email</i>
              <input
                type="email"
                class="validate"
                :class="validationsError.email ? 'no-validation' : ''"
                @blur="validations('email')"
                v-model="user.email"
                @focus="resetRegisterError()"
              />
              <label for="icon_prefix">الأيميل</label>
              <span class="helper-text red-text">{{ validationsError.email  }}</span>
            </div>
            <div class="input-field col m6">
              <i class="material-icons blue-text prefix">lock_outline</i>
              <input
                type="password"
                class="validate"
                :class="validationsError.password ? 'no-validation' : ''"
                @blur="validations('password')"
                v-model="user.password"
                @focus="resetRegisterError()"
              />
              <label for="icon_telephone">كلمة المرور</label>
              <span class="helper-text red-text">{{ validationsError.password }}</span>
            </div>
            <div class="input-field col m6">
              <i class="material-icons blue-text prefix">lock</i>
              <input
                type="password"
                class="validate"
                :class="validationsError.confirmPass ? 'no-validation' : ''"
                @blur="validations('confirmPass')"
                v-model="user.confirmPass"
                @focus="resetRegisterError()"
              />
              <label for="icon_telephone">تأكيد كلمة المرور</label>
              <span class="helper-text red-text">{{ validationsError.confirmPass }}</span>
            </div>
              <div class="file-field input-field col s12">
              <div class="btn blue">
                <span>أفتار</span>
                <input type="file" accept="jpg, png, svg"
                @blur="validations('file')"
                @focus="resetRegisterError()"
                >
              </div>
              <div class="file-path-wrapper">
                <input class="file-path validate" type="text" placeholder="اختر صورتك الشخصية">
              </div>
            </div>
            <div class="col s12">
              <button type="submit" :disabled="fetchingData" class="btn_style btn_login_modal">
                تسجيل حساب
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
      <span class="helper-text red-text">{{ registerError.msg }}</span>
      <a
        href="javascript:;"
        class="modal-close waves-effect blue-text waves-green btn-flat"
        >إغلاق</a
      >
    </div>
  </div>
</template>

<script>
  export default {
    name: 'RegisterModal',
    data() {
      return {
        fetchingData: false,
        user: {
          fullName: null,
          email: null,
          password: null,
          confirmPass: null,
          imgUrl: null
        },
        validationsError: {
          status: false,
          fullName: null,
          email: null,
          password: null,
          confirmPass: null,
          imgUrl: null
        },
        registerError: {
          status: false,
          msg: null
        },
        required: '!هذا الحقل مطلوب',
      }
    },
    methods: {
      validations(input) {
        if(input === 'fullName') {

          if(!this.user.fullName) {
              this.validationsError.status =  true;
              this.validationsError.fullName = this.required;
          } else this.validationsError.fullName = null;

        } else if (input === 'email') {

          if(!this.user.email) {
            this.validationsError.status = true;
            this.validationsError.email = this.required;
          } else if (! /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.user.email)) {
            this.validationsError.status = true;
            this.validationsError.email = 'ادخل ايميلا صحيحا';

          } else this.validationsError.email = null;

        } else if (input === 'password') {

          if(!this.user.password) {
              this.validationsError.status = true;
              this.validationsError.password = this.required;
            } else if (this.user.password.length < 6) {
              this.validationsError.status = true;
              this.validationsError.password = 'يجب أن تكون كلمة المرور ستة خانات على الأقل';
            }  else this.validationsError.password = null;

        } else if (input === 'confirmPass') {

          if(!this.user.confirmPass) {
            this.validationsError.status = true;
            this.validationsError.confirmPass = this.required;

            } else if (this.user.password && this.user.confirmPass !== this.user.password) {
              this.validationsError.status = true;
              this.validationsError.confirmPass = 'كلمتا السر غير متطابقتان';

              }  else if (!this.user.password && this.user.confirmPass) {
                this.validationsError.status = true;
                this.validationsError.confirmPass = 'ادخل كلمة السر أولا';

              } else this.validationsError.confirmPass = null;

        } else if (input === 'file') {

        }

      },
      register() {

        if(this.user.fullName && this.user.email && this.user.password && this.user.password === this.user.confirmPass) {

          this.fetchingData = true;

          // fb.auth().createUserWithEmailAndPassword(this.user.email, this.user.password)
          // .then((user) => {

          //   const userId  = user.user.uid ;

          //   setDataCollection('profiles', userId, {
          //     name: this.user.fullName,
          //     uid: userId,
          //     email: user.user.email,
          //     createdAt: new Date(),
          //     lastEdit: null,
          //     imgUrl: this.user.imgUrl,
          //     settings: {
          //       theme: window.localStorage.getItem('theme_for_qurani'),
          //       lang: 'ar'
          //     }
          //   }).then((res) => {
          //     this.$store.dispatch("GetUserData", );

          //     this.fetchingData = false;

          //     this.$router.replace('/profile');

          //       M.toast({html:   'تم تسجيل حسابك بنجاح، أهلا بك يا ' + this.user.fullName });
          //   }).catch(err => {
          //     console.log(err);
          //     this.fetchingData = false;
          //   })

          // }).catch((error) => {

          //   if(error.message.match('email address is already in use')) error.message = 'هذا الأيميل مسجل من قبل';

          //   this.registerError = {
          //         status: true,
          //         msg: error.message
          //       }
          //   this.fetchingData = false;
          // });

        } else {
          this.registerError = {
              status: true,
              msg: '!تحقق من بيانات الإدخال'
            }
        }
        
      },
      resetRegisterError() {
        this.registerError = {
            status: false,
            msg: null
          }
      }
    }
  }
</script>
