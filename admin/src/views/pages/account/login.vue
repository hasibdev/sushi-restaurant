<template>
  <div>
    <div class="home-btn d-none d-sm-block">
      <a href="/">
        <i class="mdi mdi-home-variant h2 text-white"></i>
      </a>
    </div>
    <div>
      <div class="container-fluid p-0">
        <div class="row no-gutters">
          <div class="col-lg-4">
            <div
              class="authentication-page-content p-4 d-flex align-items-center min-vh-100"
            >
              <div class="w-100">
                <div class="row justify-content-center">
                  <div class="col-lg-9">
                    <div>
                      <div class="text-center">
                        <div>
                          <a href="/" class="logo">
                            <img
                              src="@/assets/images/logo.png"
                              height="40"
                              alt="logo"
                            />
                          </a>
                        </div>

                        <h4 class="font-size-18 mt-4">Welcome Back !</h4>
                        <p class="text-muted">
                          Sign in to continue to iWebDesigner.
                        </p>
                      </div>

                      <b-alert
                        variant="danger"
                        class="mt-3"
                        v-if="notification.message"
                        show
                        dismissible
                        >{{ notification.message }}</b-alert
                      >

                      <div class="p-2 mt-5">
                        <!-- <p v-if="error" class="text-danger">{{error}}</p> -->
                        <b-alert v-if="error" show variant="danger">{{
                          error
                        }}</b-alert>
                        <form
                          class="form-horizontal"
                          @submit.prevent="tryToLogIn"
                        >
                          <div class="form-group auth-form-group-custom mb-4">
                            <i class="ri-mail-line auti-custom-input-icon"></i>
                            <label for="email">Email</label>
                            <input
                              type="email"
                              v-model="email"
                              class="form-control"
                              id="email"
                              placeholder="Enter email"
                              :class="{
                                'is-invalid': submitted && $v.email.$error,
                              }"
                            />
                            <div
                              v-if="submitted && $v.email.$error"
                              class="invalid-feedback"
                            >
                              <span v-if="!$v.email.required"
                                >Email is required.</span
                              >
                              <span v-if="!$v.email.email"
                                >Please enter valid email.</span
                              >
                            </div>
                          </div>

                          <div class="form-group auth-form-group-custom mb-4">
                            <i
                              class="ri-lock-2-line auti-custom-input-icon"
                            ></i>
                            <label for="userpassword">Password</label>
                            <input
                              v-model="password"
                              type="password"
                              class="form-control"
                              id="userpassword"
                              placeholder="Enter password"
                              :class="{
                                'is-invalid': submitted && $v.password.$error,
                              }"
                            />
                            <div
                              v-if="submitted && !$v.password.required"
                              class="invalid-feedback"
                            >
                              Password is required.
                            </div>
                          </div>

                          <div class="custom-control custom-checkbox">
                            <input
                              v-model="rememberme"
                              type="checkbox"
                              class="custom-control-input"
                              id="customControlInline"
                            />
                            <label
                              class="custom-control-label"
                              for="customControlInline"
                              >Remember me</label
                            >
                          </div>

                          <div class="mt-4 text-center">
                            <primary-button
                              @click="tryToLogIn"
                              :loading="loginState"
                              class="mx-2"
                              >Login</primary-button
                            >
                          </div>

                          <div class="mt-4 text-center">
                            <router-link
                              to="/forgot-password"
                              class="text-muted"
                            >
                              <i class="mdi mdi-lock mr-1"></i> Forgot your
                              password?
                            </router-link>
                          </div>
                        </form>
                      </div>

                      <div class="mt-5 text-center">
                        <!-- <p>
                                                    Don't have an account ?
                                                    <router-link to="/register" class="font-weight-medium text-primary">Register</router-link>
                                                </p> -->
                        <p>© {{ year }} iWebDesigner.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-8">
            <div class="authentication-bg">
              <div class="bg-overlay"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { required, email } from "vuelidate/lib/validators";
import PrimaryButton from "@/components/custom/PrimaryButton";
export default {
  name: "Login",
  components: {
    PrimaryButton,
  },
  data() {
    return {
      email: "",
      password: "",
      submitted: false,
      loginState: false,
      rememberme: false,
      error: "",
      year: new Date().getFullYear(),
    };
  },
  computed: {
    notification() {
      return this.$store ? this.$store.state.notification : null;
    },
  },
  created() {
    document.body.classList.add("auth-body-bg");
  },
  validations: {
    email: { required, email },
    password: { required },
  },

  methods: {
    async tryToLogIn() {
      this.submitted = true;
      this.error = "";
      // stop here if form is invalid
      this.$v.$touch();

      if (this.$v.$invalid) {
        return;
      } else {
        this.loginState = true;
        try {
          const res = await this.axios.post("signin", {
            user: this.email,
            password: this.password,
          });
          const expiredIn = this.rememberme ? 30 : 0;
          window.jara.setCookie("token", res.data.payload.token, expiredIn);
          window.jara.setCookie("userid", res.data.payload.id, expiredIn);
          this.$toast.success("Login Successfull!");
        } catch (error) {
          console.log("Login error", error);
          this.error = "Wrong credentials.";
          this.$toast.error("Login Fail!");
        } finally {
          this.loginState = false;
          window.location.href = "/";
          window.location.reload();
        }
      }
    },
  },
};
</script>
