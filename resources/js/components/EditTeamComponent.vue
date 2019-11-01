<template>
  <div class="container-fluid">

    <div class="row">

      <div id="pitch" class="col-6 container">

        <div id="goalkeeper_row" class="row d-flex">
          <div
            v-for="(int, index) in setup_goalkeepers"
            :id="'goalkeeper' + int"
            class="goalkeeper flex-grow-1"
            v-bind:key="int.index"
            v-on:drop="drop($event)"
            v-on:dragover="allowDrop($event)"
          >
            <div class="text-center">
                <img src="/images/jersey_yellow.png" class="jersey" />
            </div>
            <div v-for="goalkeeper in team.goalkeepers['slot' + int]" v-bind:key="goalkeeper.id" class="text-center player_info">
              {{goalkeeper.first_name}} <br/>
              <button
                :class="goalkeeper.position"
                @click="returnToPlayers($event); save_team()"
                :id="index"
              >X</button>
            </div>
          </div>
        </div>

        <div id="defender_row" class="row d-flex">
          <div
            v-for="(int, index) in setup_defenders"
            :id="'defender' + int"
            class="defender flex-grow-1"
            v-bind:key="int.index"
            v-on:drop="drop($event)"
            v-on:dragover="allowDrop($event)"
          >
            <div class="text-center">
                <img src="/images/jersey_blue.png" class="jersey" />
            </div>
            <div v-for="defender in team.defenders['slot' + int]" v-bind:key="defender.id" class="text-center player_info">
              {{defender.first_name}} <br/>
              <button
                :class="defender.position"
                @click="returnToPlayers($event); save_team()"
                :id="index"
              >X</button>
            </div>
          </div>
        </div>

        <div id="midfielder_row" class="row d-flex">
          <div
            v-for="(int, index) in setup_midfielders"
            :id="'midfielder' + int"
            class="midfielder flex-grow-1"
            v-bind:key="int.index"
            v-on:drop="drop($event)"
            v-on:dragover="allowDrop($event)"
          >
            <div class="text-center">
                <img src="/images/jersey_blue.png" class="jersey" />
            </div>
            <div v-for="midfielder in team.midfielders['slot' + int]" v-bind:key="midfielder.id" class="text-center player_info">
              {{midfielder.first_name}} <br/>
              <button
                :class="midfielder.position"
                @click="returnToPlayers($event); save_team()"
                :id="index"
              >X</button>
            </div>
          </div>
        </div>

        <div id="striker_row" class="row d-flex">
          <div
            v-for="(int, index) in setup_strikers"
            :id="'striker' + int"
            class="striker flex-grow-1"
            v-bind:key="int.index"
            v-on:drop="drop($event)"
            v-on:dragover="allowDrop($event)"
          >
            <div class="text-center">
                <img src="/images/jersey_blue.png" class="jersey" />
            </div>
            <div v-for="striker in team.strikers['slot' + int]" v-bind:key="striker.id" class="text-center player_info">
              {{striker.first_name}} <br/>
              <button
                :class="striker.position"
                @click="returnToPlayers($event); save_team()"
                :id="index"
              >X</button>
            </div>
          </div>
        </div>

      </div>

      <div class="col-6">
          <div id="search" class="row">
              Place filter here
          </div>

          <div id="players" class="row" style="width=100%">

              <ul class="list-group">
        <li class="list-group-item">
            <div class="row">
            <div class="col-4"><strong>Name:</strong></div>
            <div class="col-4 text-center"><strong>Rating:</strong></div>
            <div class="col-4"><strong>Position:</strong></div>

        </div>
        </li>
        <li class="list-group-item"
          draggable="true"
          v-on:dragstart="dragstart($event)"
          v-on:dragend="dragend($event)"
          v-for="(player, index) in players"
          v-bind:key="player.id"
          :id="index"
          :class="player.position"
        ><div class="row">
            <div class="col-4">{{player.first_name}} {{player.last_name}}</div>
            <div class="col-4 text-center">{{player.rating}}</div>
            <div class="col-4">{{player.position}}</div>

        </div>
        </li>
              </ul>

          </div>

      </div>

    </div>

  </div>
</template>

<script>
import getCookie from "./helpers/getCookie.js";
import getAllPlayers from "./helpers/getAllPlayers.js";
import getUserPlayers from "./helpers/getUserPlayers.js";
import removeFromAllPlayers from "./helpers/removeFromAllPlayers.js";
export default {
  data() {
    return {
      setup_goalkeepers: [1],
      setup_defenders: [1, 2, 3, 4],
      setup_midfielders: [1, 2, 3],
      setup_strikers: [1, 2, 3],
      drag_id: null,
      players: [],
      user_players: [],
      team: {
        goalkeepers: {
          slot1: []
        },
        defenders: {
          slot1: [],
          slot2: [],
          slot3: [],
          slot4: []
        },
        midfielders: {
          slot1: [],
          slot2: [],
          slot3: []
        },
        strikers: {
          slot1: [],
          slot2: [],
          slot3: []
        }
      }
    };
  },
  beforeCreate() {
    getAllPlayers.getAllPlayers().then(response => {
      this.players = response;
    });
    getUserPlayers.getUserPlayers().then(response => {
      var goalkeeper_slot = 1;
      var defender_slot = 1;
      var midfielder_slot = 1;
      var striker_slot = 1;
      for (let i = 0; i < response.length; i++) {
        this.user_players.push(response[i]);
        if (response[i].position == "goalkeeper") {
          this.team.goalkeepers["slot" + goalkeeper_slot].push(response[i]);
          goalkeeper_slot += 1;
        }
        if (response[i].position == "defender") {
          this.team.defenders["slot" + defender_slot].push(response[i]);
          defender_slot += 1;
        }
        if (response[i].position == "midfielder") {
          this.team.midfielders["slot" + midfielder_slot].push(response[i]);
          midfielder_slot += 1;
        }
        if (response[i].position == "striker") {
          this.team.strikers["slot" + striker_slot].push(response[i]);
          striker_slot += 1;
        }
      }
    });
  },
  computed: {

  },
  methods: {
    dragstart(event) {
      this.drag_id = event.target.id;
      let player = JSON.stringify(this.players[event.target.id]);
      let div_class = this.players[event.target.id].position;
      event.dataTransfer.setData("player", player);
    },
    allowDrop(event) {
      event.preventDefault();
    },
    drop(event) {
      let data = JSON.parse(event.dataTransfer.getData("player"));
      let position_player = data.position;
      let position_target_with_int = event.target.id;
      let slot = position_target_with_int[position_target_with_int.length - 1];
      let position_target_without_int = position_target_with_int.substring(
        0,
        position_target_with_int.length - 1
      );
      if (position_target_without_int == position_player) {
        event.preventDefault();
        this.players.splice(this.drag_id, 1);
        eval("this.team." + position_player + "s.slot" + slot).push(data);
      }
    },
    dragend(event) {
      let div_class = event.target.className
      let splited = div_class.split(" ");
      var players = [];
      if (this.team.goalkeepers.slot1[0]) {
        players.push(this.team.goalkeepers.slot1[0].id);
      }
      if (this.team.defenders.slot1[0]) {
        players.push(this.team.defenders.slot1[0].id);
      }
      if (this.team.defenders.slot2[0]) {
        players.push(this.team.defenders.slot2[0].id);
      }
      if (this.team.defenders.slot3[0]) {
        players.push(this.team.defenders.slot3[0].id);
      }
      if (this.team.defenders.slot4[0]) {
        players.push(this.team.defenders.slot4[0].id);
      }
      if (this.team.midfielders.slot1[0]) {
        players.push(this.team.midfielders.slot1[0].id);
      }
      if (this.team.midfielders.slot2[0]) {
        players.push(this.team.midfielders.slot2[0].id);
      }
      if (this.team.midfielders.slot3[0]) {
        players.push(this.team.midfielders.slot3[0].id);
      }
      if (this.team.strikers.slot1[0]) {
        players.push(this.team.strikers.slot1[0].id);
      }
      if (this.team.strikers.slot2[0]) {
        players.push(this.team.strikers.slot2[0].id);
      }
      if (this.team.strikers.slot3[0]) {
        players.push(this.team.strikers.slot3[0].id);
      }
      let token = getCookie.getCookie("access_token");
      let config = {
        headers: { Authorization: "Bearer " + token }
      };
      axios.post("/api/edit_players", players, config).then(response => {
      });
    },
    returnToPlayers(event) {
      let player = eval(
        "this.team." +
          event.target.className +
          "s.slot" +
          (parseInt(event.target.id) + 1)
      ).splice(0, 1);
      this.players.push(player[0]);
    },
    save_team() {
      var players = [];
      if (this.team.goalkeepers.slot1[0]) {
        players.push(this.team.goalkeepers.slot1[0].id);
      }
      if (this.team.defenders.slot1[0]) {
        players.push(this.team.defenders.slot1[0].id);
      }
      if (this.team.defenders.slot2[0]) {
        players.push(this.team.defenders.slot2[0].id);
      }
      if (this.team.defenders.slot3[0]) {
        players.push(this.team.defenders.slot3[0].id);
      }
      if (this.team.defenders.slot4[0]) {
        players.push(this.team.defenders.slot4[0].id);
      }
      if (this.team.midfielders.slot1[0]) {
        players.push(this.team.midfielders.slot1[0].id);
      }
      if (this.team.midfielders.slot2[0]) {
        players.push(this.team.midfielders.slot2[0].id);
      }
      if (this.team.midfielders.slot3[0]) {
        players.push(this.team.midfielders.slot3[0].id);
      }
      if (this.team.strikers.slot1[0]) {
        players.push(this.team.strikers.slot1[0].id);
      }
      if (this.team.strikers.slot2[0]) {
        players.push(this.team.strikers.slot2[0].id);
      }
      if (this.team.strikers.slot3[0]) {
        players.push(this.team.strikers.slot3[0].id);
      }
      let token = getCookie.getCookie("access_token");
      let config = {
        headers: { Authorization: "Bearer " + token }
      };
      axios.post("/api/edit_players", players, config).then(response => {
      });
    }
  }
};
</script>

<style scoped>
#goalkeeper_row,
#defender_row,
#midfielder_row,
#striker_row {
  height: 25vh;
  color: white;
  font-weight: bolder;
}

#pitch {
    background-image: url('/images/pitch.png');
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
}

#search {
    height: 10vh;
}

#players {
    height: 90vh;
    overflow-y: scroll;
    overflow-x: hidden;
}

.list-group-item {
    width: calc(50vw - 17px);
}

div .player_info button {
    border: 0;
    padding: 0;
    background-color: inherit;
    color: white;
    text-decoration: underline;
}
</style>
