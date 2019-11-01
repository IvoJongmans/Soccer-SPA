import getCookie from "./getCookie.js";

async function getAllPlayers() {
    var players = []
    let token = getCookie.getCookie("access_token");
    let config = {
      headers: { Authorization: "Bearer " + token }
    };
    await axios.get("/api/get_players", config)
    .then(response => {
        return players = response.data;
    });
    return players
  };

  export default { getAllPlayers };
