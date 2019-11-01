<template>
  <div class="container">
    <div id="logincontainer">
      <form autocomplete="off">
          <div class="form-group">
              <h3>Login</h3>
          </div>
        <div class="form-group">
          <label for="email">Mail adress</label>
          <input
            type="email"
            class="form-control"
            id="email"
            aria-describedby="email"
            placeholder="email"
            v-model="email"
          />
          <small v-for="error in email_error" style="color:red" v-bind:key="error">{{error}}</small>
          <small v-if="user_error" style="color:red">{{user_error}}</small>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" placeholder="Password" v-model="password"/>
           <small v-for="error in password_error" style="color:red" v-bind:key="error">{{error}}<br/></small>

        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary" @click.prevent="login">Submit</button>
        </div>
        <div class="form-group">
            <small>Don't have an account yet? <router-link to="/register">Register.</router-link></small>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
    data() {
       return {
           email: '',
           password: '',
           //validation errors

      email_error: "",
      password_error: "",
      user_error: "",
       }
    },
    methods: {
        login() {
            var data = {
                email: this.email,
                password: this.password
            }
            axios.post('/api/login', data)
            .then(response => {
                if(response.data.email) {
                    this.email_error = response.data.email
                }
                else {
                    this.email_error = ''
                }
                if(response.data.password) {
                    this.password_error = response.data.password
                }
                else {
                    this.password_error = ''
                }
                if(response.data.error) {
                    this.user_error = response.data.message
                }
                else {
                    this.user_error = ''
                }
                if(response.data.access_token){
                document.cookie = "access_token=" + response.data.access_token
                this.$router.push('/home')
                }
            })
        }
    }
};
</script>

<style scoped>
    #logincontainer {
        padding: 30px;
        background-color: #F1F3F4;
        border-radius: 25px;
        box-shadow: 10px 10px 10px 5px lightgray;
        margin-top: 50px;
    }
</style>
