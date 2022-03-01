import axios from "axios";

import router from "@/router";

// import store from '@/state/store'
const instance = axios.create({
  baseURL: process.env.VUE_APP_API_URL,
  maxContentLength: 100000000,
  maxBodyLength: 1000000000,
});

// Add a request interceptor
instance.interceptors.request.use(
  function (config) {
    // Do something before request is sent
    const token = window.jara.getCookie("token");
    const userid = window.jara.getCookie("userid");
    if (token && userid) {
      config.headers.common["Authorization"] = `${token || ""}`;
      config.headers.common["X-User"] = userid;
    }

    return config;
  },
  function (error) {
    console.log("Axios Request Error: ", { ...error });
    // Do something with request error
    return Promise.reject(error);
  }
);

instance.interceptors.response.use(
  function (response) {
    return response;
  },
  function (error) {
    if (error.response.status == 401) {
      window.jara.removeCookie("token");
      window.jara.removeCookie("userid");
      router.replace("/login");
    }

    return Promise.reject(error);
  }
);

export default instance;
