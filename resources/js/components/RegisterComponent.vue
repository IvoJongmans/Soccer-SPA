<template>
  <div class="container">
    <div id="registercontainer">
      <form>
        <div class="form-group">
          <h3>Register</h3>
        </div>
        <div class="form-group">
          <label for="name">Name</label>
          <input
            type="text"
            class="form-control"
            id="name"
            aria-describedby="name"
            placeholder="John Doe"
            v-model="name"
          />
          <small v-for="error in name_error" style="color:red" v-bind:key="error">{{error}}</small>
        </div>
        <div class="form-group">
          <label for="email">Email address</label>
          <input
            type="email"
            class="form-control"
            id="email"
            aria-describedby="email"
            placeholder="example@mail.com"
            v-model="email"
          />
          <small v-for="error in email_error" style="color:red" v-bind:key="error">{{error}}</small>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input
            type="password"
            class="form-control"
            id="password"
            placeholder="Password"
            v-model="password"
            required
          />
          <small v-for="error in password_error" style="color:red" v-bind:key="error">{{error}}<br/></small>
        </div>
        <div class="form-group">
          <label for="password_confirmation">Repeat Password</label>
          <input
            type="password"
            class="form-control"
            id="password_confirmation"
            placeholder="Repeat Password"
            v-model="password_confirmation"
            required
          />
        </div>
        <div class="form-group"></div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary" @click.prevent="register">Submit</button>
        </div>
        <div class="form-group">
          <small>
            Back to
            <router-link to="/">login</router-link>.
          </small>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      //validation errors
      name_error: "",
      email_error: "",
      password_error: ""
    };
  },
  methods: {
    register() {
      var data = {
        name: this.name,
        email: this.email,
        password: this.password,
        password_confirmation: this.password_confirmation
      };
      axios
        .post("/register", data)
        .then(response => {
          if (response.data.name) {
            this.name_error = response.data.name;
          } else {
            this.name_error = "";
          }
          if (response.data.email) {
            this.email_error = response.data.email;
          } else {
            this.email_error = "";
          }
          if (response.data.password) {
            this.password_error = response.data.password;
          } else {
            this.password_error = "";
          }
          if (response.data == "user_created") {
              this.$router.push('/')
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    logout() {
      axios.post("/logout");
    }
  }
};
</script>

<style scoped>
#registercontainer {
  padding: 30px;
  background-color: #f1f3f4;
  border-radius: 25px;
  box-shadow: 10px 10px 10px 5px lightgray;
  margin-top: 50px;
}
</style>
