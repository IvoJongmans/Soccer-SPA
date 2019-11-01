<template>
  <div class="container">
    <div id="homecontainer">
      <div>Welkom {{user.name}}</div>
      <div v-if="no_team">
        <form>
          <div class="form-group">
            <p>Let's start by giving your team a name!</p>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name="team_name" v-model="create_team_name" />
            <small v-for="error in create_team_name_error" v-bind:key="error" style="color:red">{{create_team_name_error}}<br/></small>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit" @click.prevent="create_team">Create team!</button>
          </div>
        </form>
      </div>

      <div v-if="team_name">{{team_name}}
          <br/><button class="btn btn-primary" @click.prevent="edit_team">Edit team</button>

        <button class="btn btn-primary" @click="logout">Logout</button>
      </div>
    </div>
  </div>
</template>

<script>
import getCookie from "./helpers/getCookie.js";
export default {
  data() {
    return {
      user: {},
      no_team: false,
      team_name: "",
      create_team_name: "",
      create_team_name_error: {}
    };
  },
  beforeCreate() {
    let token = getCookie.getCookie("access_token");
    let config = {
      headers: { Authorization: "Bearer " + token }
    };
    axios.get("/api/user", config).then(response => {
      this.user = response.data;
    });
    axios.get("/api/team", config).then(response => {
      if (response.data == "no_team") {
        this.no_team = true;
      } else {
        this.team_name = response.data;
      }
    });
  },
  methods: {
    logout() {
      let token = getCookie.getCookie("access_token");
      let config = {
        headers: { Authorization: "Bearer " + token }
      };
      axios.get("/api/logout", config).then(response => {
        if (response.data == "token_revoked") {
          this.$router.push("/");
        }
      });
    },
    create_team() {
      let token = getCookie.getCookie("access_token");
      let data = {
          'team_name': this.create_team_name,
      }
      let config = {
        headers: { Authorization: "Bearer " + token }
      };
      axios.post('/api/create_team', data, config)
      .then(response => {
          if(response.data.team_name) {
              this.create_team_name_error = response.data.team_name
          }
          else {
              this.no_team = false;

              this.team_name = response.data;
          }
      })
    },
    edit_team() {
        this.$router.push('/edit_team')
    }
  }
};
</script>

<style scoped>
#homecontainer {
  padding: 30px;
  background-color: #f1f3f4;
  border-radius: 25px;
  box-shadow: 10px 10px 10px 5px lightgray;
  margin-top: 50px;
}
</style>
