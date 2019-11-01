import getCookie from "./getCookie.js";

async function getUserPlayers() {
    var user_players = ''
    let token = getCookie.getCookie("access_token");
    let config = {
      headers: { Authorization: "Bearer " + token }
    };
    await axios.get("/api/retrieve_players", config)
    .then(response => {
        return user_players = response.data;
    });
    return user_players
  };

  export default { getUserPlayers };
